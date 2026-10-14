import { purgeDeletions } from './account';
import type { Building } from './company';
import { NPC_COMPANY, type Db } from './db';
import { labourTick } from './labour';
import {
  CATEGORIES,
  DRIFT,
  GOODS,
  GROUP_KEYS,
  GROUP_OF,
  distribution,
  priceFactor,
  randInt,
  round2,
  styleFactor,
  type Category,
  type GroupKey,
  type Rng,
} from './goods';

/** Tick part: +1 % on cash, 10 % on the loan (the NPC company is excluded). */
function interest(db: Db) {
  db.prepare(
    `UPDATE companies SET
       e_interest = e_interest + round(MAX(balance, 0) * 0.01, 2), a_interest = a_interest + round(loan * 0.1, 2),
       balance = balance + round(MAX(balance, 0) * 0.01, 2) - round(loan * 0.1, 2)
     WHERE id <> ?`,
  ).run(NPC_COMPANY);
}

/** Visitors per group for a building (target groups get a bonus; a lone secondary group becomes primary). */
export function visitors(
  b: Pick<Building, 'style' | 'pzg' | 'szg' | 'popularity'>,
  share: Record<GroupKey, number>,
) {
  const pzg = b.pzg !== 'keine' ? b.pzg : b.szg;
  const szg = b.pzg !== 'keine' ? b.szg : 'keine';
  const out = {} as Record<GroupKey, number>;
  for (const g of GROUP_KEYS) {
    const bonus = GROUP_OF[pzg] === g ? 1.3 : GROUP_OF[szg] === g ? 1.2 : 1;
    out[g] = Math.ceil(share[g] * styleFactor(b.style, g) * bonus * b.popularity);
  }
  return out;
}

/** Liters sold per offered good, in order; the 60 % of unmet demand spills over to the next good. */
export function sellVolumes(wants: number[], stocks: number[]): number[] {
  let carry = 0;
  return wants.map((want, i) => {
    const vol = Math.min(want + carry, stocks[i]!);
    carry = (want + carry - vol) * 0.6;
    return vol;
  });
}

function sales(db: Db, rng: Rng, now: number) {
  const demand = db.prepare('SELECT * FROM demand').all() as ({ grp: string } & Record<
    Category,
    number
  >)[];
  const districts = db.prepare('SELECT * FROM districts WHERE id = ?');
  const goods = db.prepare(
    'SELECT good, price, stock FROM building_goods WHERE building_id = ? AND offered = 1',
  );
  const setStock = db.prepare(
    'UPDATE building_goods SET stock = stock - ? WHERE building_id = ? AND good = ?',
  );
  const earn = db.prepare(
    'UPDATE companies SET balance = balance + ?, e_sales = e_sales + ? WHERE id = ?',
  );
  for (const b of db
    .prepare('SELECT * FROM buildings WHERE finished_at <= ?')
    .all(now) as Building[]) {
    const share = districts.get(b.district) as Record<GroupKey, number>;
    const v = visitors(b, share);
    const rows = goods.all(b.id) as { good: string; price: number; stock: number }[];
    let revenue = 0;
    for (const [cat, c] of Object.entries(CATEGORIES) as [
      Category,
      (typeof CATEGORIES)[Category],
    ][]) {
      const mine = rows.filter((r) => GOODS.find((x) => x.key === r.good)!.cat === cat);
      if (!mine.length) continue;
      const total = demand.reduce((s, d) => s + d[cat] * v[d.grp as GroupKey], 0);
      const dist = distribution(rng, mine.length);
      const wants = mine.map(
        (r, i) =>
          Math.ceil((total * 15 * dist[i]! * priceFactor(r.price / c.ref)) / c.unit) * c.unit,
      );
      const sold = sellVolumes(
        wants,
        mine.map((r) => r.stock),
      );
      mine.forEach((r, i) => {
        if (sold[i]! <= 0) return;
        setStock.run(sold[i], b.id, r.good);
        revenue += round2(sold[i]! * r.price);
      });
    }
    if (revenue > 0) earn.run(round2(revenue), round2(revenue), b.company_id);
  }
}

/** Random walk of the NPC market prices inside the category range. */
function drift(db: Db, rng: Rng) {
  const upd = db.prepare('UPDATE market_prices SET price = ? WHERE good = ?');
  for (const p of db.prepare('SELECT good, price FROM market_prices').all() as {
    good: string;
    price: number;
  }[]) {
    const [lo, hi] = CATEGORIES[GOODS.find((x) => x.key === p.good)!.cat].range;
    const next = round2(p.price * (1 + DRIFT[randInt(rng, 0, DRIFT.length - 1)]! / 100));
    upd.run(Math.min(Math.max(next, lo), hi), p.good);
  }
}

/** One game step; called by the protected /api/tick route. */
export function tick(db: Db, rng: Rng = Math.random, now = Date.now()) {
  db.transaction(() => {
    interest(db);
    sales(db, rng, now);
    drift(db, rng);
    labourTick(db, rng);
    purgeDeletions(db, now);
  })();
}
