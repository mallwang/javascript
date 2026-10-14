import { mkdirSync } from 'node:fs';
import { dirname } from 'node:path';
import Database from 'better-sqlite3';
import { hashPassword } from './password';

export function openDb(file: string, admin?: { email: string; password: string }) {
  if (file !== ':memory:') mkdirSync(dirname(file), { recursive: true });
  const db = new Database(file);
  db.pragma('foreign_keys = ON');
  db.exec(`
    CREATE TABLE IF NOT EXISTS users (
      id INTEGER PRIMARY KEY, nickname TEXT NOT NULL UNIQUE COLLATE NOCASE,
      email TEXT NOT NULL UNIQUE COLLATE NOCASE, password TEXT NOT NULL,
      is_admin INTEGER NOT NULL DEFAULT 0
    );
    CREATE TABLE IF NOT EXISTS sessions (
      token_hash TEXT PRIMARY KEY,
      user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
      expires_at INTEGER NOT NULL
    );
    CREATE TABLE IF NOT EXISTS places (
      id INTEGER PRIMARY KEY,
      category TEXT NOT NULL CHECK (category IN ('sight', 'park', 'museum')),
      state TEXT NOT NULL, city TEXT NOT NULL, name TEXT NOT NULL,
      description TEXT NOT NULL DEFAULT '', lat REAL NOT NULL, lng REAL NOT NULL,
      status TEXT NOT NULL DEFAULT 'pending' CHECK (status IN ('pending', 'approved')),
      submitted_by INTEGER REFERENCES users(id) ON DELETE SET NULL
    );
    CREATE TABLE IF NOT EXISTS books (
      id INTEGER PRIMARY KEY, title TEXT NOT NULL, author TEXT NOT NULL DEFAULT '',
      year INTEGER, category TEXT NOT NULL DEFAULT '', format TEXT NOT NULL DEFAULT ''
    );
    CREATE TABLE IF NOT EXISTS support (
      id INTEGER PRIMARY KEY, subject TEXT NOT NULL, email TEXT NOT NULL, text TEXT NOT NULL,
      created_at INTEGER NOT NULL
    );
  `);
  // Without mail verification the admin cannot be "whoever registers first": seed it from env.
  if (admin?.email && admin.password) {
    db.prepare(
      `INSERT OR IGNORE INTO users (nickname, email, password, is_admin) VALUES ('admin', ?, ?, 1)`,
    ).run(admin.email, hashPassword(admin.password));
  }
  return db;
}

let db: Database.Database | undefined;
export const getDb = () =>
  (db ??= openDb(process.env.DB_PATH ?? 'data/app.db', {
    email: process.env.ADMIN_EMAIL ?? '',
    password: process.env.ADMIN_PASSWORD ?? '',
  }));
