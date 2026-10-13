import { mkdirSync } from 'node:fs';
import { dirname } from 'node:path';
import Database from 'better-sqlite3';
import { migrate, purgeDeleted } from './users';

let db: Database.Database | undefined;

export function getDb() {
  if (!db) {
    const file = process.env.DB_PATH ?? 'data/app.db';
    mkdirSync(dirname(file), { recursive: true });
    db = new Database(file);
    db.pragma('foreign_keys = ON');
    migrate(db);
  }
  purgeDeleted(db);
  return db;
}
