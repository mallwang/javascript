import Database from 'better-sqlite3';
import { mkdirSync } from 'node:fs';
import { dirname } from 'node:path';
import type { Fill } from './calc';

export interface Reading extends Fill {
  ts: string;
  distanceCm: number;
}

export function openDb(path: string) {
  if (path !== ':memory:') mkdirSync(dirname(path), { recursive: true });
  const db = new Database(path);
  db.exec(`CREATE TABLE IF NOT EXISTS readings (
    ts TEXT PRIMARY KEY, distance_cm REAL NOT NULL, level_cm REAL NOT NULL,
    liters REAL NOT NULL, percent REAL NOT NULL)`);
  const insert = db.prepare('INSERT INTO readings VALUES (?, ?, ?, ?, ?)');
  const select = db.prepare(
    `SELECT ts, distance_cm AS distanceCm, level_cm AS levelCm, liters, percent FROM readings
     WHERE ts >= ? AND ts <= ? ORDER BY ts LIMIT 100000`,
  );
  return {
    insert(r: Reading) {
      insert.run(r.ts, r.distanceCm, r.levelCm, r.liters, r.percent);
    },
    list(from = '', to = '￿') {
      return select.all(from, to) as Reading[];
    },
    close: () => db.close(),
  };
}

export type Db = ReturnType<typeof openDb>;
