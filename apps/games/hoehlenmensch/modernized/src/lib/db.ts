import { mkdirSync } from 'node:fs';
import { dirname } from 'node:path';
import Database from 'better-sqlite3';

// Invented seed data: the legacy lookup tables (13/21/20/4 rows) are not in the repo.
const FOOD: Record<string, [string, number][]> = {
  small_animal: [
    ['Hare', 12],
    ['Squirrel', 6],
    ['Pheasant', 9],
    ['Mouse', 3],
    ['Frog', 4],
    ['Partridge', 8],
    ['Rabbit', 10],
    ['Hedgehog', 5],
  ],
  plant: [
    ['Blackberry', 4],
    ['Hazelnut', 7],
    ['Apple', 5],
    ['Wild garlic', 3],
    ['Acorn', 6],
    ['Raspberry', 4],
    ['Wild carrot', 5],
    ['Rose hip', 3],
  ],
  fish: [
    ['Trout', 9],
    ['Pike', 12],
    ['Carp', 10],
    ['Perch', 7],
    ['Roach', 5],
    ['Eel', 11],
    ['Bream', 6],
    ['Salmon', 14],
  ],
  mushroom: [
    ['Chanterelle', 5],
    ['Porcini', 9],
    ['Parasol', 6],
    ['Morel', 8],
  ],
};

export function openDb(file: string) {
  if (file !== ':memory:') mkdirSync(dirname(file), { recursive: true });
  const db = new Database(file);
  db.pragma('foreign_keys = ON');
  db.exec(`
    CREATE TABLE IF NOT EXISTS users (
      id INTEGER PRIMARY KEY,
      nickname TEXT NOT NULL UNIQUE COLLATE NOCASE,
      password TEXT NOT NULL
    );
    CREATE TABLE IF NOT EXISTS sessions (
      token_hash TEXT PRIMARY KEY,
      user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
      expires_at INTEGER NOT NULL
    );
    CREATE TABLE IF NOT EXISTS player (
      user_id INTEGER PRIMARY KEY REFERENCES users(id) ON DELETE CASCADE,
      xp INTEGER NOT NULL DEFAULT 0,
      activity INTEGER NOT NULL DEFAULT 0,
      duration INTEGER NOT NULL DEFAULT 0,
      ends_at INTEGER NOT NULL DEFAULT 0
    );
    -- resources, crafted items and skills as (kind, name, qty) rows
    CREATE TABLE IF NOT EXISTS stock (
      user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
      kind TEXT NOT NULL CHECK (kind IN ('resource', 'item', 'skill')),
      name TEXT NOT NULL,
      qty REAL NOT NULL DEFAULT 0,
      PRIMARY KEY (user_id, kind, name)
    );
    CREATE TABLE IF NOT EXISTS food (kind TEXT NOT NULL, name TEXT NOT NULL, points INTEGER NOT NULL);
  `);
  if (!db.prepare('SELECT 1 FROM food').get()) {
    const ins = db.prepare('INSERT INTO food VALUES (?, ?, ?)');
    for (const [kind, rows] of Object.entries(FOOD)) for (const [n, p] of rows) ins.run(kind, n, p);
  }
  return db;
}

let db: Database.Database | undefined;
export const getDb = () => (db ??= openDb(process.env.DB_PATH ?? 'data/app.db'));
