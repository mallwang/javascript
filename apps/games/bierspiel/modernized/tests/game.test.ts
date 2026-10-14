import { beforeEach, describe, expect, it } from 'vitest';
import * as account from '../src/lib/account';
import * as company from '../src/lib/company';
import { openDb, type Db } from '../src/lib/db';
import { distribution, priceFactor } from '../src/lib/goods';
import * as labour from '../src/lib/labour';
import * as market from '../src/lib/market';
import * as messages from '../src/lib/messages';
import { sellVolumes, tick, visitors } from '../src/lib/settle';

let db: Db;
const reg = (n: string) =>
  account.register(db, {
    nickname: n,
    email: `${n}@x.de`,
    password: 'secret123',
    password2: 'secret123',
    company: `${n} GmbH`,
    hq: 1,
  });
const cid = (uid: number) =>
  (db.prepare('SELECT id FROM companies WHERE user_id = ?').get(uid) as { id: number }).id;
const balance = (c: number) =>
  (db.prepare('SELECT balance FROM companies WHERE id = ?').get(c) as { balance: number }).balance;
const house = { kind: 'kneipe', provider: 1, location: 1, district: 1, name: 'Pub' };
let a: number, ca: number;
beforeEach(() => {
  db = openDb(':memory:');
  a = reg('alice');
  ca = cid(a);
});

describe('account', () => {
  it('hashes passwords, logs in by nickname or e-mail, rejects duplicates', () => {
    expect(
      (db.prepare('SELECT password FROM users WHERE id = ?').get(a) as { password: string })
        .password,
    ).not.toContain('secret123');
    expect(account.authenticate(db, 'ALICE', 'secret123')).toBe(a);
    expect(account.authenticate(db, 'alice@x.de', 'secret123')).toBe(a);
    expect(() => account.authenticate(db, 'alice', 'wrong')).toThrow();
    expect(() => reg('alice')).toThrow(/in use/);
  });
  it('sessions expire; deletion is cancelled by login and purged after the grace period', () => {
    const t = account.createSession(db, a, 0);
    expect(account.getSession(db, t, 1)?.companyId).toBe(ca);
    expect(account.getSession(db, t, 8 * 24 * 3600 * 1000)).toBeUndefined();
    account.requestDeletion(db, a, 'secret123', 0);
    account.authenticate(db, 'alice', 'secret123');
    expect(account.purgeDeletions(db, 1e12)).toBe(0);
    account.requestDeletion(db, a, 'secret123', 0);
    expect(account.purgeDeletions(db, account.DELETION_GRACE_MS)).toBe(1);
    expect(db.prepare('SELECT 1 FROM companies WHERE id = ?').get(ca)).toBeUndefined();
  });
});

describe('buildings', () => {
  it('computes cost server-side, enforces cash and the building cap', () => {
    expect(company.build(db, ca, { ...house, provider: 2 }, 0)).toBeGreaterThan(0);
    expect(balance(ca)).toBe(200_000 - 120_000);
    expect(() => company.build(db, ca, { ...house, kind: 'gaststaette' })).toThrow(/cash/);
    expect(() => company.build(db, ca, { ...house, provider: 9 })).toThrow();
    db.prepare('UPDATE companies SET balance = 1e9 WHERE id = ?').run(ca);
    for (let i = 0; i < 19; i++) company.build(db, ca, house);
    expect(() => company.build(db, ca, house)).toThrow(/at most/);
  });
  it('sell refunds half, demolish nothing, both check ownership', () => {
    const id = company.build(db, ca, house);
    const b = cid(reg('bob'));
    expect(() => company.sell(db, b, id)).toThrow(/Not your/);
    expect(() => company.demolish(db, b, id)).toThrow(/Not your/);
    company.sell(db, ca, id);
    expect(balance(ca)).toBe(150_000);
    const id2 = company.build(db, ca, house);
    company.demolish(db, ca, id2);
    expect(balance(ca)).toBe(50_000);
  });
  it('rebuild cost: style, seats and toilets; no-op rejected', () => {
    const id = company.build(db, ca, house, 0);
    const b = { style: 'keinen', seats: 24, wc_m: 1, wc_w: 1 };
    expect(company.rebuildCost(b, { style: 'Edel', seatIndex: 3, wcM: 2, wcW: 1 })).toBe(
      10_000 + 2 * 50 + 500,
    );
    expect(company.rebuildCost(b, { style: 'keinen', seatIndex: 1, wcM: 1, wcW: 1 })).toBe(0);
    const same = { style: 'keinen', seatIndex: 1, wcM: 1, wcW: 1, pzg: 'keine', szg: 'keine' };
    expect(() => company.rebuild(db, ca, id, same, 1e12)).toThrow(/Nothing/);
    expect(() => company.rebuild(db, ca, id, { ...same, style: 'Edel' }, 0)).toThrow(
      /construction/,
    );
    company.rebuild(db, ca, id, { ...same, style: 'Edel' }, 1e12);
    expect(balance(ca)).toBe(100_000 - 10_000);
  });
  it('validates menu prices and ownership', () => {
    const id = company.build(db, ca, house);
    company.setMenu(db, ca, id, { pils: { offered: true, price: 2.5 } });
    expect(() => company.setMenu(db, ca, id, { pils: { offered: true, price: -1 } })).toThrow();
    expect(() => company.setMenu(db, ca, id, { nope: { offered: true, price: 1 } })).toThrow();
    expect(() => company.setMenu(db, cid(reg('bob')), id, {})).toThrow(/Not your/);
  });
});

describe('bank and market', () => {
  it('borrow/repay respect the limit and cash', () => {
    company.borrow(db, ca, 200_000);
    expect(() => company.borrow(db, ca, 1)).toThrow(/limit/);
    expect(() => company.repay(db, ca, 300_000)).toThrow();
    expect(() => company.repay(db, ca, -5)).toThrow();
    company.repay(db, ca, 100_000);
    expect(balance(ca)).toBe(300_000);
  });
  it('NPC purchase charges price per 20 l, rejects negative amounts and foreign buildings', () => {
    const id = company.build(db, ca, house);
    const price = (
      db.prepare("SELECT price FROM market_prices WHERE good = 'pils'").get() as { price: number }
    ).price;
    const before = balance(ca);
    market.buyGoods(db, ca, id, 'pils', 40);
    expect(balance(ca)).toBeCloseTo(before - 2 * price, 2);
    expect(() => market.buyGoods(db, ca, id, 'pils', -40)).toThrow();
    expect(() => market.buyGoods(db, cid(reg('bob')), id, 'pils', 1)).toThrow(/Not your/);
  });
  it('player offers: no self-buy, partial buy pays the seller', () => {
    const b = cid(reg('bob'));
    db.prepare(
      "INSERT INTO offers (ware, seller_id, amount, quality, price) VALUES ('hops', ?, 10, 1, 2)",
    ).run(b);
    expect(() => market.buyOffer(db, b, 1, 1)).toThrow(/own/);
    expect(() => market.buyOffer(db, ca, 1, 11)).toThrow();
    market.buyOffer(db, ca, 1, 4);
    expect(balance(b)).toBe(200_008);
    expect((db.prepare('SELECT amount FROM offers').get() as { amount: number }).amount).toBe(6);
  });
});

describe('settlement', () => {
  it('price factor and distribution', () => {
    expect(priceFactor(1)).toBe(1);
    expect(priceFactor(2.1)).toBe(0);
    expect(priceFactor(0.5)).toBe(1.7);
    const d = distribution(Math.random, 6);
    expect(d.reduce((s, x) => s + x, 0)).toBeCloseTo(1, 10);
  });
  it('visitors apply target-group bonuses; a lone secondary group becomes primary', () => {
    const share = { teen: 0.1, young: 0.1, family: 0.1, senior: 0.1, adult: 0.1 };
    const base = { style: 'Edel', popularity: 1000 };
    expect(visitors({ ...base, pzg: 'Familie', szg: 'keine' }, share).family).toBe(
      Math.ceil(0.1 * 1.05 * 1.3 * 1000),
    );
    expect(visitors({ ...base, pzg: 'keine', szg: 'Familie' }, share).family).toBe(
      Math.ceil(0.1 * 1.05 * 1.3 * 1000),
    );
  });
  it('unmet demand spills 60 % over; stock caps sales', () => {
    expect(sellVolumes([10, 5], [4, 100])).toEqual([4, 8.6]);
  });
  it('tick: interest, sales, price drift inside the range, NPC excluded', () => {
    db.prepare('UPDATE companies SET loan = 1000 WHERE id = ?').run(ca);
    const id = company.build(db, ca, house, 0);
    company.setMenu(db, ca, id, { pils: { offered: true, price: 2.3 } });
    db.prepare(
      "UPDATE building_goods SET stock = 1000 WHERE building_id = ? AND good = 'pils'",
    ).run(id);
    const before = balance(ca);
    tick(db, () => 0.5, 1e12);
    const c = db
      .prepare('SELECT e_sales, e_interest, a_interest FROM companies WHERE id = ?')
      .get(ca) as Record<string, number>;
    expect(c.e_interest).toBeCloseTo(before * 0.01, 2);
    expect(c.a_interest).toBe(100);
    expect(c.e_sales).toBeGreaterThan(0);
    expect(balance(1)).toBe(0);
    const p = (
      db.prepare("SELECT price FROM market_prices WHERE good = 'pils'").get() as { price: number }
    ).price;
    expect(p).toBeGreaterThanOrEqual(40);
    expect(p).toBeLessThanOrEqual(50);
  });
});

describe('labour', () => {
  it('salary, satisfaction and work value rules', () => {
    expect(labour.baseSalary(2, 44)).toBe(1700);
    expect(labour.satisfaction(1700, 1700)).toBe(71);
    expect(labour.satisfaction(2550, 1700)).toBe(71 + 25);
    expect(labour.satisfaction(100, 1700)).toBe(0);
    expect(labour.workValue(60, 71, 2)).toBe(60);
    expect(labour.workValue(60, 0, 2)).toBe(0);
  });
  it('hire is atomic and refills the market; fire checks ownership and cash', () => {
    const w = (db.prepare('SELECT id FROM workers WHERE art = 1 LIMIT 1').get() as { id: number })
      .id;
    const n = () =>
      (
        db.prepare('SELECT COUNT(*) n FROM workers WHERE art = 1 AND employer IS NULL').get() as {
          n: number;
        }
      ).n;
    const free = n();
    labour.hire(db, ca, w);
    expect(n()).toBe(free);
    expect(() => labour.hire(db, cid(reg('bob')), w)).toThrow(/already/);
    expect(() => labour.fire(db, cid(reg('carl')), w)).toThrow(/Not your/);
    labour.fire(db, ca, w);
    expect(
      (
        db.prepare('SELECT employer FROM workers WHERE id = ?').get(w) as {
          employer: number | null;
        }
      ).employer,
    ).toBeNull();
    expect(balance(ca)).toBeLessThan(200_000);
  });
  it('bids: must exceed asking and own previous bid; highest wins at tick', () => {
    const b = cid(reg('bob'));
    const w = db.prepare('SELECT id, salary FROM workers WHERE art = 2 LIMIT 1').get() as {
      id: number;
      salary: number;
    };
    expect(() => labour.placeBid(db, ca, w.id, w.salary)).toThrow();
    labour.placeBid(db, ca, w.id, w.salary + 10);
    expect(() => labour.placeBid(db, ca, w.id, w.salary + 5)).toThrow();
    labour.placeBid(db, b, w.id, w.salary + 50);
    labour.labourTick(db);
    expect(
      (
        db.prepare('SELECT employer, salary FROM workers WHERE id = ?').get(w.id) as {
          employer: number;
        }
      ).employer,
    ).toBe(b);
    expect(db.prepare('SELECT 1 FROM bids').get()).toBeUndefined();
  });
  it('payroll accrues weekly and is paid in week 4', () => {
    const w = (db.prepare('SELECT id FROM workers WHERE art = 1 LIMIT 1').get() as { id: number })
      .id;
    labour.hire(db, ca, w);
    const salary = (
      db.prepare('SELECT salary FROM workers WHERE id = ?').get(w) as { salary: number }
    ).salary;
    for (let i = 0; i < 3; i++) labour.labourTick(db);
    expect(balance(ca)).toBe(200_000);
    labour.labourTick(db);
    expect(balance(ca)).toBe(200_000 - salary);
  });
});

describe('messages', () => {
  it('send, per-side delete, row removed when both deleted, limits, ownership', () => {
    const b = reg('bob');
    messages.send(db, a, 'bob', 'Hi', 'text');
    const id = messages.inbox(db, b)[0]!.id;
    expect(() => messages.remove(db, reg('carl'), id)).toThrow();
    messages.remove(db, b, id);
    expect(messages.outbox(db, a)).toHaveLength(1);
    messages.remove(db, a, id);
    expect(db.prepare('SELECT 1 FROM messages').get()).toBeUndefined();
    for (let i = 0; i < 20; i++) messages.send(db, a, 'bob', 'x', 'y');
    expect(() => messages.send(db, a, 'bob', 'x', 'y')).toThrow(/full/);
    expect(() => messages.send(db, a, 'nobody', 'x', 'y')).toThrow(/not found/);
  });
});
