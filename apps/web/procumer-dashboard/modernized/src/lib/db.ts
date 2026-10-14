import { mkdirSync } from 'node:fs';
import { dirname } from 'node:path';
import Database from 'better-sqlite3';

export type Db = Database.Database;

export function openDb(file: string): Db {
  if (file !== ':memory:') mkdirSync(dirname(file), { recursive: true });
  const db = new Database(file);
  db.exec(`
    CREATE TABLE IF NOT EXISTS meter_reading (
      meter TEXT NOT NULL CHECK (meter IN ('strom', 'wasser')),
      date TEXT NOT NULL, -- YYYY-MM-DD
      reading REAL NOT NULL,
      PRIMARY KEY (meter, date)
    );
  `);
  return db;
}

let db: Db | undefined;
export const getDb = () => (db ??= openDb(process.env.DB_PATH ?? 'data/app.db'));
