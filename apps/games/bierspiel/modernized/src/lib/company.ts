import type { Db } from './db';
import { assert } from './errors';
import {
  BUILDINGS,
  CATEGORIES,
  DISTRICTS,
  GOODS,
  GOOD_KEYS,
  GROUPS,
  LOCATIONS,
  MAX_BUILDINGS,
  PROVIDER_COST,
  PROVIDER_TIME,
  STYLES,
  round2,
} from './goods';

export type Building = {
  id: number;
  company_id: number;
  kind: string;
  name: string;
  finished_at: number;
  cost: number;
  location: number;
  district: number;
  popularity: number;
  style: string;
  pzg: string;
  szg: string;
  seats: number;
  wc_m: number;
  wc_w: number;
};

const getBuilding = (db: Db, companyId: number, id: number) =>
  db.prepare('SELECT * FROM buildings WHERE id = ? AND company_id = ?').get(id, companyId) as
    Building | undefined;
const balanceOf = (db: Db, companyId: number) =>
  (db.prepare('SELECT balance FROM companies WHERE id = ?').get(companyId) as { balance: number })
    .balance;
const inRange = (n: number, lo: number, hi: number) => Number.isInteger(n) && n >= lo && n <= hi;

/** Price and construction time are computed here, never taken from the client. */
export function buildCost(kind: string, provider: number) {
  const b = BUILDINGS[kind];
  assert(b && inRange(provider, 1, 5), 'Invalid building or provider.');
  return {
    cost: round2(b.cost * PROVIDER_COST[provider - 1]!),
    seconds: Math.round(b.minutes * PROVIDER_TIME[provider - 1]! * 60),
  };
}

export function build(
  db: Db,
  companyId: number,
  i: { kind: string; provider: number; location: number; district: number; name: string },
  now = Date.now(),
): number {
  const { cost, seconds } = buildCost(i.kind, i.provider);
  const name = i.name.trim();
  assert(/^[\w][\w .&'-]{0,39}$/.test(name), 'Invalid building name.');
  assert(
    inRange(i.location, 1, LOCATIONS.length) && inRange(i.district, 1, DISTRICTS.length),
    'Invalid location or district.',
  );
  return db.transaction(() => {
    const n = (
      db.prepare('SELECT COUNT(*) n FROM buildings WHERE company_id = ?').get(companyId) as {
        n: number;
      }
    ).n;
    assert(n < MAX_BUILDINGS, `You can own at most ${MAX_BUILDINGS} buildings.`);
    assert(balanceOf(db, companyId) >= cost, 'Not enough cash.');
    db.prepare(
      'UPDATE companies SET balance = balance - ?, a_building = a_building + ? WHERE id = ?',
    ).run(cost, cost, companyId);
    const r = db
      .prepare(
        `INSERT INTO buildings (company_id, kind, name, finished_at, cost, location, district, popularity)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?)`,
      )
      .run(
        companyId,
        i.kind,
        name,
        now + seconds * 1000,
        cost,
        i.location,
        i.district,
        BUILDINGS[i.kind]!.popularity,
      );
    const id = Number(r.lastInsertRowid);
    const ins = db.prepare(
      'INSERT INTO building_goods (building_id, good, price) VALUES (?, ?, ?)',
    );
    for (const g of GOODS) ins.run(id, g.key, CATEGORIES[g.cat].ref);
    return id;
  })();
}

/** Sell a building for half of its price. */
export function sell(db: Db, companyId: number, id: number) {
  db.transaction(() => {
    const b = getBuilding(db, companyId, id);
    assert(b, 'Not your building.');
    const refund = round2(b.cost / 2);
    db.prepare('DELETE FROM buildings WHERE id = ?').run(id);
    db.prepare(
      'UPDATE companies SET balance = balance + ?, e_building_sale = e_building_sale + ? WHERE id = ?',
    ).run(refund, refund, companyId);
  })();
}

/** Demolish without refund. */
export function demolish(db: Db, companyId: number, id: number) {
  const r = db.prepare('DELETE FROM buildings WHERE id = ? AND company_id = ?').run(id, companyId);
  assert(r.changes === 1, 'Not your building.');
}

/** Net rebuild cost for the change (legacy rules: toilet reductions cost money too). */
export function rebuildCost(
  b: Pick<Building, 'style' | 'seats' | 'wc_m' | 'wc_w'>,
  n: { style: string; seatIndex: number; wcM: number; wcW: number },
) {
  let cost = n.style !== b.style ? 10_000 : 0;
  const ds = n.seatIndex - (b.seats - 20) / 4;
  cost += ds > 0 ? ds * 50 : ds * 20;
  for (const d of [n.wcM - b.wc_m, n.wcW - b.wc_w]) cost += d > 0 ? d * 500 : -d * 200;
  return cost;
}

export function rebuild(
  db: Db,
  companyId: number,
  id: number,
  i: { style: string; seatIndex: number; wcM: number; wcW: number; pzg: string; szg: string },
  now = Date.now(),
) {
  assert((STYLES as readonly string[]).includes(i.style), 'Invalid style.');
  assert(
    (GROUPS as readonly string[]).includes(i.pzg) && (GROUPS as readonly string[]).includes(i.szg),
    'Invalid target group.',
  );
  assert(
    inRange(i.seatIndex, 1, 25) && inRange(i.wcM, 1, 6) && inRange(i.wcW, 1, 6),
    'Invalid rebuild values.',
  );
  db.transaction(() => {
    const b = getBuilding(db, companyId, id);
    assert(b, 'Not your building.');
    assert(b.finished_at <= now, 'The building is still under construction.');
    const cost = rebuildCost(b, i);
    const groupsChanged = i.pzg !== b.pzg || i.szg !== b.szg;
    assert(cost !== 0 || groupsChanged, 'Nothing to change.');
    if (cost > 0) {
      assert(balanceOf(db, companyId) >= cost, 'Not enough cash.');
      db.prepare(
        'UPDATE companies SET balance = balance - ?, a_rebuild = a_rebuild + ? WHERE id = ?',
      ).run(cost, cost, companyId);
    } else if (cost < 0) {
      db.prepare(
        'UPDATE companies SET balance = balance - ?, e_rebuild = e_rebuild - ? WHERE id = ?',
      ).run(cost, cost, companyId);
    }
    db.prepare(
      'UPDATE buildings SET style = ?, seats = ?, wc_m = ?, wc_w = ?, pzg = ?, szg = ? WHERE id = ?',
    ).run(i.style, 20 + 4 * i.seatIndex, i.wcM, i.wcW, i.pzg, i.szg, id);
  })();
}

/** Set which drinks are offered and at which price (41 goods). */
export function setMenu(
  db: Db,
  companyId: number,
  id: number,
  items: Record<string, { offered: boolean; price: number }>,
) {
  assert(getBuilding(db, companyId, id), 'Not your building.');
  db.transaction(() => {
    const upd = db.prepare(
      'UPDATE building_goods SET offered = ?, price = ? WHERE building_id = ? AND good = ?',
    );
    for (const [good, v] of Object.entries(items)) {
      assert(GOOD_KEYS.has(good), 'Unknown drink.');
      assert(Number.isFinite(v.price) && v.price > 0 && v.price <= 99.99, 'Invalid price.');
      upd.run(v.offered ? 1 : 0, round2(v.price), id, good);
    }
  })();
}

export function borrow(db: Db, companyId: number, raw: unknown) {
  const n = Number(raw);
  assert(Number.isFinite(n) && n > 0, 'Enter a valid amount.');
  const amount = round2(n);
  db.transaction(() => {
    const c = db
      .prepare('SELECT loan, credit_limit FROM companies WHERE id = ?')
      .get(companyId) as { loan: number; credit_limit: number };
    assert(amount <= c.credit_limit - c.loan, 'Credit limit exceeded.');
    db.prepare('UPDATE companies SET loan = loan + ?, balance = balance + ? WHERE id = ?').run(
      amount,
      amount,
      companyId,
    );
  })();
}

export function repay(db: Db, companyId: number, raw: unknown) {
  const n = Number(raw);
  assert(Number.isFinite(n) && n > 0, 'Enter a valid amount.');
  const amount = round2(n);
  db.transaction(() => {
    const c = db.prepare('SELECT loan, balance FROM companies WHERE id = ?').get(companyId) as {
      loan: number;
      balance: number;
    };
    assert(amount <= c.loan, 'You owe less than that.');
    assert(amount <= c.balance, 'Not enough cash.');
    db.prepare('UPDATE companies SET loan = loan - ?, balance = balance - ? WHERE id = ?').run(
      amount,
      amount,
      companyId,
    );
  })();
}
