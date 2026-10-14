import { readSheet } from 'read-excel-file/node';
import type { Reading } from './consumption';
import type { Db } from './db';

export const meters = {
  strom: { label: 'Strom', unit: 'kWh', sheet: process.env.SHEET_STROM ?? 'Strombezug' },
  wasser: { label: 'Wasser', unit: 'm³', sheet: process.env.SHEET_WASSER ?? 'Zaehler Wasser' },
} as const;
export type Meter = keyof typeof meters;
export const isMeter = (s: string): s is Meter => s in meters;

/** Keeps rows with a date in column A and a number in column B (like the legacy parser); sorted by date. */
export function parseRows(rows: unknown[][]): Reading[] {
  const readings: Reading[] = [];
  for (const [date, reading] of rows) {
    if (date instanceof Date && !isNaN(+date) && typeof reading === 'number') {
      readings.push({ date: date.toISOString().slice(0, 10), reading });
    }
  }
  return readings.sort((a, b) => a.date.localeCompare(b.date));
}

/** Replaces all stored readings of a meter (re-import is idempotent). */
export function storeReadings(db: Db, meter: Meter, readings: Reading[]): number {
  const insert = db.prepare(
    'INSERT OR REPLACE INTO meter_reading (meter, date, reading) VALUES (?, ?, ?)',
  );
  db.transaction(() => {
    db.prepare('DELETE FROM meter_reading WHERE meter = ?').run(meter);
    for (const r of readings) insert.run(meter, r.date, r.reading);
  })();
  return readings.length;
}

export const getReadings = (db: Db, meter: Meter): Reading[] =>
  db
    .prepare('SELECT date, reading FROM meter_reading WHERE meter = ? ORDER BY date')
    .all(meter) as Reading[];

/** Imports every known meter whose sheet exists in the workbook; returns the row count per meter. */
export async function importWorkbook(
  db: Db,
  file: Buffer,
): Promise<Partial<Record<Meter, number>>> {
  const counts: Partial<Record<Meter, number>> = {};
  for (const meter of Object.keys(meters) as Meter[]) {
    try {
      const rows = await readSheet(file, meters[meter].sheet);
      counts[meter] = storeReadings(db, meter, parseRows(rows));
    } catch (e) {
      if (!(e instanceof Error && e.name === 'SheetNotFoundError')) throw e;
    }
  }
  return counts;
}
