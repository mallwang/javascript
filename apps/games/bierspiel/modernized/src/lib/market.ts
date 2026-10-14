import type { Db } from './db';
import { assert } from './errors';
import { GOOD_KEYS, MARKET_LITERS, WARES, round2 } from './goods';

const MAX_LITERS = 100_000;

/** Buy drinks from the NPC market into the stock of an own building; price is per MARKET_LITERS. */
export function buyGoods(
  db: Db,
  companyId: number,
  buildingId: number,
  good: string,
  rawLiters: unknown,
) {
  const liters = Number(rawLiters);
  assert(GOOD_KEYS.has(good), 'Unknown drink.');
  assert(Number.isFinite(liters) && liters > 0 && liters <= MAX_LITERS, 'Enter a valid amount.');
  db.transaction(() => {
    assert(
      db
        .prepare('SELECT 1 FROM buildings WHERE id = ? AND company_id = ?')
        .get(buildingId, companyId),
      'Not your building.',
    );
    const p = db.prepare('SELECT price FROM market_prices WHERE good = ?').get(good) as {
      price: number;
    };
    const cost = round2((p.price * liters) / MARKET_LITERS);
    const c = db.prepare('SELECT balance FROM companies WHERE id = ?').get(companyId) as {
      balance: number;
    };
    assert(c.balance >= cost, 'Not enough cash.');
    db.prepare(
      'UPDATE companies SET balance = balance - ?, a_goods = a_goods + ? WHERE id = ?',
    ).run(cost, cost, companyId);
    db.prepare(
      'UPDATE building_goods SET stock = stock + ? WHERE building_id = ? AND good = ?',
    ).run(liters, buildingId, good);
  })();
}

/** Player market for raw materials: buy (part of) an offer of another company. */
export function buyOffer(db: Db, companyId: number, offerId: number, rawAmount: unknown) {
  const amount = Number(rawAmount);
  assert(Number.isFinite(amount) && amount > 0, 'Enter a valid amount.');
  db.transaction(() => {
    const o = db.prepare('SELECT * FROM offers WHERE id = ?').get(offerId) as
      { id: number; ware: string; seller_id: number; amount: number; price: number } | undefined;
    assert(o && (WARES as readonly string[]).includes(o.ware), 'Offer not found.');
    assert(o.seller_id !== companyId, 'You cannot buy your own offer.');
    assert(amount <= o.amount, 'Not enough on offer.');
    const cost = round2(o.price * amount);
    const c = db.prepare('SELECT balance FROM companies WHERE id = ?').get(companyId) as {
      balance: number;
    };
    assert(c.balance >= cost, 'Not enough cash.');
    db.prepare('UPDATE companies SET balance = balance - ? WHERE id = ?').run(cost, companyId);
    db.prepare('UPDATE companies SET balance = balance + ? WHERE id = ?').run(cost, o.seller_id);
    db.prepare(
      'INSERT INTO warehouse VALUES (?, ?, ?) ON CONFLICT (company_id, ware) DO UPDATE SET amount = amount + excluded.amount',
    ).run(companyId, o.ware, amount);
    if (amount === o.amount) db.prepare('DELETE FROM offers WHERE id = ?').run(offerId);
    else db.prepare('UPDATE offers SET amount = amount - ? WHERE id = ?').run(amount, offerId);
  })();
}
