import { readFileSync } from 'node:fs';
import { describe, expect, it } from 'vitest';
import { monthly, yearly } from '../src/lib/consumption';
import { openDb } from '../src/lib/db';
import { getReadings, importWorkbook, parseRows, storeReadings } from '../src/lib/importer';

const readings = [
  { date: '2023-11-30', reading: 100 },
  { date: '2023-12-31', reading: 130.5 },
  { date: '2024-01-31', reading: 160 },
  { date: '2024-02-29', reading: 200 },
];

describe('consumption', () => {
  it('derives monthly consumption from consecutive readings; the first has none', () => {
    expect(monthly(readings)).toEqual([
      { period: '2023-12', consumption: 30.5 },
      { period: '2024-01', consumption: 29.5 },
      { period: '2024-02', consumption: 40 },
    ]);
  });

  it('sums monthly consumption per calendar year', () => {
    expect(yearly(readings)).toEqual([
      { period: '2023', consumption: 30.5 },
      { period: '2024', consumption: 69.5 },
    ]);
  });

  it('needs two readings', () => {
    expect(monthly([readings[0]])).toEqual([]);
    expect(yearly([])).toEqual([]);
  });
});

describe('import', () => {
  it('keeps only rows with a date and a number, sorted by date', () => {
    const rows = [
      ['Date', 'Reading'],
      [new Date('2024-02-29T00:00:00Z'), 200],
      [new Date('2024-01-31T00:00:00Z'), 160],
      [null, 5],
      [new Date('2024-03-31T00:00:00Z'), 'n/a'],
    ];
    expect(parseRows(rows)).toEqual([
      { date: '2024-01-31', reading: 160 },
      { date: '2024-02-29', reading: 200 },
    ]);
  });

  it('replaces stored readings on re-import', () => {
    const db = openDb(':memory:');
    storeReadings(db, 'strom', readings);
    storeReadings(db, 'wasser', [{ date: '2024-01-01', reading: 1 }]);
    storeReadings(db, 'strom', readings.slice(2));
    expect(getReadings(db, 'strom')).toEqual(readings.slice(2));
    expect(getReadings(db, 'wasser')).toHaveLength(1);
  });
});

describe('workbook import', () => {
  it('reads known sheets from an .xlsx file (synthetic fixture)', async () => {
    const db = openDb(':memory:');
    const file = readFileSync(new URL('./fixtures/sample.xlsx', import.meta.url));
    expect(await importWorkbook(db, file)).toEqual({ strom: 3, wasser: 1 });
    expect(yearly(getReadings(db, 'strom'))).toEqual([{ period: '2024', consumption: 74.75 }]);
  });
});
