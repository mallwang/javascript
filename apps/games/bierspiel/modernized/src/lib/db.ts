import { mkdirSync } from 'node:fs';
import { dirname } from 'node:path';
import Database from 'better-sqlite3';
import { CATEGORIES, GOODS, round2, type Category } from './goods';
import { seedWorkers } from './labour';

export type Db = Database.Database;

/** Company id 1 is the city/NPC company (no user, excluded from interest). */
export const NPC_COMPANY = 1;

export function openDb(file: string): Db {
  if (file !== ':memory:') mkdirSync(dirname(file), { recursive: true });
  const db = new Database(file);
  db.pragma('foreign_keys = ON');
  db.exec(`
    CREATE TABLE IF NOT EXISTS users (
      id INTEGER PRIMARY KEY, nickname TEXT NOT NULL UNIQUE COLLATE NOCASE,
      email TEXT NOT NULL UNIQUE COLLATE NOCASE, password TEXT NOT NULL,
      logins INTEGER NOT NULL DEFAULT 0, created_at INTEGER NOT NULL, last_login INTEGER
    );
    CREATE TABLE IF NOT EXISTS sessions (
      token_hash TEXT PRIMARY KEY,
      user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
      expires_at INTEGER NOT NULL
    );
    CREATE TABLE IF NOT EXISTS deletions (
      user_id INTEGER PRIMARY KEY REFERENCES users(id) ON DELETE CASCADE,
      requested_at INTEGER NOT NULL
    );
    CREATE TABLE IF NOT EXISTS companies (
      id INTEGER PRIMARY KEY,
      user_id INTEGER UNIQUE REFERENCES users(id) ON DELETE CASCADE,
      name TEXT NOT NULL UNIQUE COLLATE NOCASE, hq INTEGER NOT NULL DEFAULT 1,
      balance REAL NOT NULL, loan REAL NOT NULL DEFAULT 0, credit_limit REAL NOT NULL DEFAULT 200000,
      salary_account REAL NOT NULL DEFAULT 0, week INTEGER NOT NULL DEFAULT 0,
      e_building_sale REAL NOT NULL DEFAULT 0, e_rebuild REAL NOT NULL DEFAULT 0,
      e_sales REAL NOT NULL DEFAULT 0, e_interest REAL NOT NULL DEFAULT 0,
      a_building REAL NOT NULL DEFAULT 0, a_rebuild REAL NOT NULL DEFAULT 0,
      a_goods REAL NOT NULL DEFAULT 0, a_wages REAL NOT NULL DEFAULT 0,
      a_interest REAL NOT NULL DEFAULT 0
    );
    CREATE TABLE IF NOT EXISTS buildings (
      id INTEGER PRIMARY KEY,
      company_id INTEGER NOT NULL REFERENCES companies(id) ON DELETE CASCADE,
      kind TEXT NOT NULL, name TEXT NOT NULL, finished_at INTEGER NOT NULL, cost REAL NOT NULL,
      size INTEGER NOT NULL DEFAULT 250, location INTEGER NOT NULL, district INTEGER NOT NULL,
      popularity INTEGER NOT NULL, style TEXT NOT NULL DEFAULT 'keinen',
      pzg TEXT NOT NULL DEFAULT 'keine', szg TEXT NOT NULL DEFAULT 'keine',
      seats INTEGER NOT NULL DEFAULT 24, wc_m INTEGER NOT NULL DEFAULT 1, wc_w INTEGER NOT NULL DEFAULT 1
    );
    CREATE TABLE IF NOT EXISTS building_goods (
      building_id INTEGER NOT NULL REFERENCES buildings(id) ON DELETE CASCADE,
      good TEXT NOT NULL, offered INTEGER NOT NULL DEFAULT 0, price REAL NOT NULL, stock REAL NOT NULL DEFAULT 0,
      PRIMARY KEY (building_id, good)
    );
    CREATE TABLE IF NOT EXISTS market_prices (good TEXT PRIMARY KEY, price REAL NOT NULL);
    CREATE TABLE IF NOT EXISTS districts (
      id INTEGER PRIMARY KEY, teen REAL, young REAL, family REAL, senior REAL, adult REAL
    );
    CREATE TABLE IF NOT EXISTS demand (
      grp TEXT PRIMARY KEY, beer REAL, mix REAL, soft REAL, juice REAL, spirits REAL, cocktail REAL, wine REAL
    );
    CREATE TABLE IF NOT EXISTS warehouse (
      company_id INTEGER NOT NULL REFERENCES companies(id) ON DELETE CASCADE,
      ware TEXT NOT NULL, amount REAL NOT NULL DEFAULT 0, PRIMARY KEY (company_id, ware)
    );
    CREATE TABLE IF NOT EXISTS offers (
      id INTEGER PRIMARY KEY, ware TEXT NOT NULL,
      seller_id INTEGER NOT NULL REFERENCES companies(id) ON DELETE CASCADE,
      amount REAL NOT NULL, quality INTEGER NOT NULL, price REAL NOT NULL
    );
    CREATE TABLE IF NOT EXISTS messages (
      id INTEGER PRIMARY KEY, subject TEXT NOT NULL, text TEXT NOT NULL, sent_at INTEGER NOT NULL,
      from_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
      to_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
      sender_deleted INTEGER NOT NULL DEFAULT 0, recipient_deleted INTEGER NOT NULL DEFAULT 0
    );
    CREATE TABLE IF NOT EXISTS workers (
      id INTEGER PRIMARY KEY, art INTEGER NOT NULL, area INTEGER NOT NULL,
      first_name TEXT NOT NULL, last_name TEXT NOT NULL, age INTEGER NOT NULL,
      base_value INTEGER NOT NULL, talent INTEGER NOT NULL, salary REAL NOT NULL,
      employer INTEGER REFERENCES companies(id) ON DELETE SET NULL,
      satisfaction INTEGER NOT NULL DEFAULT 71, work_value INTEGER NOT NULL, tenure INTEGER NOT NULL DEFAULT 0
    );
    CREATE TABLE IF NOT EXISTS bids (
      id INTEGER PRIMARY KEY,
      worker_id INTEGER NOT NULL REFERENCES workers(id) ON DELETE CASCADE,
      company_id INTEGER NOT NULL REFERENCES companies(id) ON DELETE CASCADE,
      amount REAL NOT NULL
    );
  `);
  seed(db);
  return db;
}

// Seed data is not in the legacy code; values are invented within the documented ranges.
function seed(db: Db) {
  db.prepare(`INSERT OR IGNORE INTO companies (id, name, balance) VALUES (?, 'City', 0)`).run(
    NPC_COMPANY,
  );
  const price = db.prepare('INSERT OR IGNORE INTO market_prices (good, price) VALUES (?, ?)');
  for (const x of GOODS) {
    const [lo, hi] = CATEGORIES[x.cat as Category].range;
    price.run(x.key, round2((lo + hi) / 2));
  }
  const district = db.prepare('INSERT OR IGNORE INTO districts VALUES (?, ?, ?, ?, ?, ?)');
  district.run(1, 0.25, 0.3, 0.1, 0.1, 0.25);
  district.run(2, 0.15, 0.15, 0.3, 0.2, 0.2);
  district.run(3, 0.05, 0.25, 0.05, 0.1, 0.55);
  const demand = db.prepare('INSERT OR IGNORE INTO demand VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
  demand.run('teen', 0.05, 0.2, 0.3, 0.25, 0.05, 0.15, 0.02);
  demand.run('young', 0.25, 0.2, 0.15, 0.1, 0.15, 0.3, 0.1);
  demand.run('family', 0.1, 0.05, 0.25, 0.25, 0.02, 0.02, 0.15);
  demand.run('senior', 0.2, 0.02, 0.15, 0.15, 0.08, 0, 0.25);
  demand.run('adult', 0.3, 0.1, 0.1, 0.1, 0.15, 0.1, 0.2);
  seedWorkers(db);
}

let db: Db | undefined;
export const getDb = () => (db ??= openDb(process.env.DB_PATH ?? 'data/app.db'));
