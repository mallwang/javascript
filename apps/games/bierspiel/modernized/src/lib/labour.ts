// Labour market (ported from legacy v5, see docs/bierspiel.md Part B).
import type { Db } from './db';
import { assert } from './errors';
import { randInt, round2, type Rng } from './goods';

export const ART_LABEL = ['Apprentice', 'Unskilled worker', 'Skilled worker', 'Manager'];
export const AREA_LABEL = ['', 'Administration', 'Production', 'Hospitality', 'Other'];
const MAX_AMOUNT = 99_999;

const FIRST = [
  'Anna',
  'Lukas',
  'Maria',
  'Jonas',
  'Sophie',
  'Felix',
  'Laura',
  'Paul',
  'Lena',
  'Max',
  'Eva',
  'Tim',
];
const LAST = [
  'Huber',
  'Maier',
  'Schmid',
  'Bauer',
  'Wagner',
  'Fischer',
  'Weber',
  'Koch',
  'Richter',
  'Vogel',
];

/** [value, weight] pairs expanded like the legacy text files (duplicates = weight). */
const range = (lo: number, hi: number, w: number): [number, number][] =>
  Array.from({ length: hi - lo + 1 }, (_, i) => [lo + i, w]);
const APPRENTICE_VALUE = [5, 5, 4, 4, 3, 3, 2, 2, 1, 1].map((w, i): [number, number] => [i + 1, w]);
const UNSKILLED_VALUE = [
  ...range(35, 38, 8),
  ...range(39, 42, 6),
  ...range(43, 46, 4),
  ...range(47, 50, 2),
];
const SKILLED_VALUE = [
  ...range(45, 50, 5),
  ...range(51, 55, 7),
  ...range(56, 60, 9),
  ...range(61, 65, 7),
  ...range(66, 70, 5),
  ...range(71, 74, 3),
  ...range(75, 75, 3),
];
const SKILLED_AGE = [
  ...range(23, 26, 10),
  ...range(27, 30, 7),
  ...range(31, 35, 5),
  ...range(36, 40, 7),
  ...range(41, 45, 3),
  ...range(46, 55, 1),
];
const TALENT = [7, 5, 4, 2, 1].map((w, i): [number, number] => [i + 1, w]);

function pick<T>(rng: Rng, list: T[]): T {
  return list[randInt(rng, 0, list.length - 1)]!;
}
function weighted(rng: Rng, pairs: [number, number][]): number {
  let r = rng() * pairs.reduce((s, [, w]) => s + w, 0);
  for (const [v, w] of pairs) if ((r -= w) < 0) return v;
  return pairs[pairs.length - 1]![0];
}

/** Base salary for a work value (legacy `grundgehaltBerechnen`). */
export function baseSalary(art: number, value: number): number {
  return [800, 1300 + (value - 34) * 20, 1700 + (value - 44) * 45, 3200 + (value - 69) * 100][art]!;
}

/** Absolute satisfaction for a salary (legacy `neueZufr`; the < -50 % branch was a bug and is a penalty here). */
export function satisfaction(salary: number, base: number): number {
  const faktor = Math.trunc((salary / base) * 100 - 100);
  const change =
    faktor >= 0 ? Math.trunc(faktor / 2) : faktor < -50 ? -71 : Math.trunc(faktor / 0.71);
  return Math.min(Math.max(71 + change, 0), 100);
}

/** Work value for a satisfaction level (legacy `berechneArbeitswert`). */
export function workValue(base: number, sat: number, art: number): number {
  const m =
    sat > 90
      ? 1.1
      : sat > 80
        ? 1.05
        : sat > 70
          ? 1
          : sat > 50
            ? 0.95
            : sat > 30
              ? 0.75
              : sat > 0
                ? 0.5
                : 0;
  return Math.min(Math.floor(base * m), [30, 50, 75, 100][art]!);
}

export function insertWorker(db: Db, art: 0 | 1 | 2, rng: Rng = Math.random) {
  const value = weighted(rng, [APPRENTICE_VALUE, UNSKILLED_VALUE, SKILLED_VALUE][art]!);
  const age =
    art === 0
      ? randInt(rng, 16, 22)
      : art === 1
        ? randInt(rng, 23, 35)
        : weighted(rng, SKILLED_AGE);
  const area = art === 0 ? 1 : randInt(rng, 2, 4);
  db.prepare(
    `INSERT INTO workers (art, area, first_name, last_name, age, base_value, talent, salary, work_value)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)`,
  ).run(
    art,
    area,
    pick(rng, FIRST),
    pick(rng, LAST),
    age,
    value,
    weighted(rng, TALENT),
    baseSalary(art, value),
    value,
  );
}

export function seedWorkers(db: Db) {
  if ((db.prepare('SELECT COUNT(*) n FROM workers').get() as { n: number }).n > 0) return;
  for (const art of [0, 1, 2] as const) for (let i = 0; i < 30; i++) insertWorker(db, art);
}

type Worker = {
  id: number;
  art: number;
  base_value: number;
  salary: number;
  employer: number | null;
};
const worker = (db: Db, id: number) =>
  db.prepare('SELECT * FROM workers WHERE id = ?').get(id) as Worker | undefined;

function amountOf(raw: unknown): number {
  const n = Number(raw);
  assert(Number.isFinite(n) && n > 0 && n <= MAX_AMOUNT, 'Enter a valid amount.');
  return round2(n);
}

/** Bid for a skilled worker or manager (art 2/3); resolved at the next tick. */
export function placeBid(db: Db, companyId: number, workerId: number, raw: unknown) {
  const amount = amountOf(raw);
  const w = worker(db, workerId);
  assert(w && w.employer === null && w.art >= 2, 'Worker is not available for bids.');
  assert(amount > w.salary, 'Bid must exceed the asking salary.');
  const own = db
    .prepare('SELECT MAX(amount) m FROM bids WHERE worker_id = ? AND company_id = ?')
    .get(workerId, companyId) as { m: number | null };
  assert(own.m === null || amount > own.m, 'Bid must exceed your previous bid.');
  db.prepare('INSERT INTO bids (worker_id, company_id, amount) VALUES (?, ?, ?)').run(
    workerId,
    companyId,
    amount,
  );
}

/** Direct hire of an apprentice or unskilled worker; a replacement of the same kind appears on the market. */
export function hire(db: Db, companyId: number, workerId: number) {
  db.transaction(() => {
    const w = worker(db, workerId);
    assert(w && w.art <= 1, 'Worker cannot be hired directly.');
    const r = db
      .prepare(
        'UPDATE workers SET employer = ?, satisfaction = 71 WHERE id = ? AND employer IS NULL',
      )
      .run(companyId, workerId);
    assert(r.changes === 1, 'Worker is already employed.');
    insertWorker(db, w.art as 0 | 1);
  })();
}

/** Fire an own worker; severance is 10 monthly salaries, computed server-side. */
export function fire(db: Db, companyId: number, workerId: number) {
  db.transaction(() => {
    const w = worker(db, workerId);
    assert(w && w.employer === companyId, 'Not your employee.');
    const severance = round2(w.salary * 10);
    const c = db.prepare('SELECT balance FROM companies WHERE id = ?').get(companyId) as {
      balance: number;
    };
    assert(c.balance > severance, 'Not enough cash for the severance pay.');
    db.prepare(
      'UPDATE companies SET balance = balance - ?, a_wages = a_wages + ? WHERE id = ?',
    ).run(severance, severance, companyId);
    const base = baseSalary(w.art, w.base_value);
    db.prepare(
      'UPDATE workers SET employer = NULL, salary = ?, satisfaction = 71, work_value = ?, tenure = 0 WHERE id = ?',
    ).run(base, workValue(w.base_value, 71, w.art), workerId);
  })();
}

/** Raise or cut the salary of an own worker. */
export function setSalary(db: Db, companyId: number, workerId: number, raw: unknown) {
  const salary = amountOf(raw);
  const w = worker(db, workerId);
  assert(w && w.employer === companyId, 'Not your employee.');
  const sat = satisfaction(salary, baseSalary(w.art, w.base_value));
  db.prepare('UPDATE workers SET salary = ?, satisfaction = ?, work_value = ? WHERE id = ?').run(
    salary,
    sat,
    workValue(w.base_value, sat, w.art),
    workerId,
  );
}

/** Tick part 1: highest bid wins (ties: earliest bid), the market is refilled with a skilled worker. */
function resolveBids(db: Db, rng: Rng) {
  const ids = db
    .prepare('SELECT worker_id FROM bids GROUP BY worker_id ORDER BY MIN(id)')
    .all() as { worker_id: number }[];
  for (const { worker_id } of ids) {
    const win = db
      .prepare(
        'SELECT company_id, amount FROM bids WHERE worker_id = ? ORDER BY amount DESC, id LIMIT 1',
      )
      .get(worker_id) as { company_id: number; amount: number };
    const w = worker(db, worker_id)!;
    const sat = satisfaction(win.amount, baseSalary(w.art, w.base_value));
    db.prepare(
      'UPDATE workers SET salary = ?, satisfaction = ?, work_value = ?, employer = ?, tenure = 0 WHERE id = ?',
    ).run(win.amount, sat, workValue(w.base_value, sat, w.art), win.company_id, worker_id);
    db.prepare('DELETE FROM bids WHERE worker_id = ?').run(worker_id);
    insertWorker(db, 2, rng);
  }
}

/** Tick part 3: a quarter of the monthly salaries accrue weekly, the salary account is paid out in week 4. */
function payroll(db: Db) {
  const rows = db
    .prepare(
      'SELECT employer id, SUM(salary) total FROM workers WHERE employer IS NOT NULL GROUP BY employer',
    )
    .all() as { id: number; total: number }[];
  for (const { id, total } of rows) {
    const c = db.prepare('SELECT salary_account a, week w FROM companies WHERE id = ?').get(id) as {
      a: number;
      w: number;
    };
    const account = round2(c.a + total / 4);
    if (c.w + 1 >= 4) {
      db.prepare(
        'UPDATE companies SET balance = balance - ?, a_wages = a_wages + ?, salary_account = 0, week = 0 WHERE id = ?',
      ).run(account, account, id);
    } else {
      db.prepare('UPDATE companies SET salary_account = ?, week = week + 1 WHERE id = ?').run(
        account,
        id,
      );
    }
  }
}

export function labourTick(db: Db, rng: Rng = Math.random) {
  db.transaction(() => {
    resolveBids(db, rng);
    db.prepare('UPDATE workers SET tenure = tenure + 1 WHERE employer IS NOT NULL').run();
    payroll(db);
  })();
}
