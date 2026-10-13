import { mkdirSync } from 'node:fs';
import { dirname } from 'node:path';
import Database from 'better-sqlite3';

export function openDb(file: string) {
  if (file !== ':memory:') mkdirSync(dirname(file), { recursive: true });
  const db = new Database(file);
  db.pragma('foreign_keys = ON');
  db.exec(`
    CREATE TABLE IF NOT EXISTS clubs (
      id INTEGER PRIMARY KEY, name TEXT NOT NULL,
      short_text TEXT NOT NULL DEFAULT '', long_text TEXT NOT NULL DEFAULT '',
      history TEXT NOT NULL DEFAULT ''
    );
    CREATE TABLE IF NOT EXISTS users (
      id INTEGER PRIMARY KEY, email TEXT NOT NULL UNIQUE, password TEXT NOT NULL,
      role TEXT NOT NULL CHECK (role IN ('admin', 'coach', 'player')),
      club_id INTEGER NOT NULL REFERENCES clubs(id) ON DELETE CASCADE,
      first_name TEXT NOT NULL, last_name TEXT NOT NULL
    );
    CREATE TABLE IF NOT EXISTS sessions (
      token_hash TEXT PRIMARY KEY,
      user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
      expires_at INTEGER NOT NULL
    );
    CREATE TABLE IF NOT EXISTS teams (
      id INTEGER PRIMARY KEY,
      club_id INTEGER NOT NULL REFERENCES clubs(id) ON DELETE CASCADE,
      name TEXT NOT NULL DEFAULT '', type_id INTEGER NOT NULL,
      formation TEXT NOT NULL DEFAULT '4-3-3'
    );
    CREATE TABLE IF NOT EXISTS team_coaches (
      user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
      team_id INTEGER NOT NULL REFERENCES teams(id) ON DELETE CASCADE,
      PRIMARY KEY (user_id, team_id)
    );
    CREATE TABLE IF NOT EXISTS players (
      user_id INTEGER PRIMARY KEY REFERENCES users(id) ON DELETE CASCADE,
      team_id INTEGER REFERENCES teams(id) ON DELETE SET NULL,
      birthdate TEXT NOT NULL DEFAULT '', slot INTEGER
    );
    CREATE TABLE IF NOT EXISTS events (
      id INTEGER PRIMARY KEY,
      club_id INTEGER NOT NULL REFERENCES clubs(id) ON DELETE CASCADE,
      team_id INTEGER NOT NULL REFERENCES teams(id) ON DELETE CASCADE,
      type TEXT NOT NULL, title TEXT NOT NULL, location TEXT NOT NULL DEFAULT '',
      opponent TEXT NOT NULL DEFAULT '', start_at TEXT NOT NULL, end_at TEXT NOT NULL,
      team_goals INTEGER, opponent_goals INTEGER
    );
    CREATE TABLE IF NOT EXISTS tournament_matches (
      id INTEGER PRIMARY KEY,
      event_id INTEGER NOT NULL REFERENCES events(id) ON DELETE CASCADE,
      opponent TEXT NOT NULL, start_at TEXT NOT NULL,
      team_goals INTEGER, opponent_goals INTEGER
    );
    CREATE TABLE IF NOT EXISTS attendance (
      event_id INTEGER NOT NULL REFERENCES events(id) ON DELETE CASCADE,
      user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
      value INTEGER NOT NULL, PRIMARY KEY (event_id, user_id)
    );
  `);
  return db;
}

let db: Database.Database | undefined;
export const getDb = () => (db ??= openDb(process.env.DB_PATH ?? 'data/app.db'));
