import { mkdirSync } from 'node:fs';
import { dirname } from 'node:path';
import Database from 'better-sqlite3';

export type Db = Database.Database;

export function openDb(file: string): Db {
  if (file !== ':memory:') mkdirSync(dirname(file), { recursive: true });
  const db = new Database(file);
  db.pragma('foreign_keys = ON');
  db.exec(`
    CREATE TABLE IF NOT EXISTS user (
      id INTEGER PRIMARY KEY,
      first_name TEXT NOT NULL,
      last_name TEXT NOT NULL,
      email TEXT NOT NULL UNIQUE,
      password_hash TEXT NOT NULL,
      role TEXT NOT NULL DEFAULT 'user' CHECK (role IN ('admin', 'user')),
      verified INTEGER NOT NULL DEFAULT 0,
      updated_at TEXT NOT NULL
    );
    CREATE TABLE IF NOT EXISTS book (
      id INTEGER PRIMARY KEY,
      title TEXT NOT NULL,
      author TEXT NOT NULL,
      isbn TEXT NOT NULL UNIQUE,
      year INTEGER NOT NULL,
      language TEXT NOT NULL,
      owner_id INTEGER NOT NULL REFERENCES user(id) ON DELETE CASCADE,
      updated_at TEXT NOT NULL
    );
    -- Only SHA-256 hashes of session and one-time tokens are stored.
    CREATE TABLE IF NOT EXISTS session (
      token_hash TEXT PRIMARY KEY,
      user_id INTEGER NOT NULL REFERENCES user(id) ON DELETE CASCADE,
      expires_at INTEGER NOT NULL
    );
    CREATE TABLE IF NOT EXISTS token (
      token_hash TEXT PRIMARY KEY,
      user_id INTEGER NOT NULL REFERENCES user(id) ON DELETE CASCADE,
      purpose TEXT NOT NULL CHECK (purpose IN ('verify', 'reset')),
      expires_at INTEGER NOT NULL
    );
  `);
  return db;
}

let db: Db | undefined;
export const getDb = () => (db ??= openDb(process.env.DB_PATH ?? 'data/app.db'));
