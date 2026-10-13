import { createHash, randomBytes } from 'node:crypto';
import type { Database } from 'better-sqlite3';
import { hashPassword, verifyPassword } from './password';
import { validateEmail, validateNickname, validatePassword } from './validate';

export type User = { id: number; nickname: string; email: string };
type Row = User & { password: string; activation_key: string | null };
type Result<T> = T | { error: string };

const DAY = 24 * 60 * 60 * 1000;
export const DELETION_GRACE = 3 * DAY;
const SESSION_TTL = 7 * DAY;

export function migrate(db: Database) {
  db.exec(`
    CREATE TABLE IF NOT EXISTS users (
      id INTEGER PRIMARY KEY,
      nickname TEXT NOT NULL UNIQUE COLLATE NOCASE,
      email TEXT NOT NULL UNIQUE,
      password TEXT NOT NULL,
      activation_key TEXT UNIQUE,
      deletion_requested_at INTEGER
    );
    CREATE TABLE IF NOT EXISTS sessions (
      token_hash TEXT PRIMARY KEY,
      user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
      expires_at INTEGER NOT NULL
    );
  `);
}

const sha256 = (s: string) => createHash('sha256').update(s).digest('hex');
const publicUser = ({ id, nickname, email }: Row): User => ({ id, nickname, email });

export function createUser(
  db: Database,
  { nickname, email, password }: { nickname: string; email: string; password: string },
): Result<{ id: number; key: string }> {
  const error = validateNickname(nickname) ?? validateEmail(email) ?? validatePassword(password);
  if (error) return { error };
  email = email.toLowerCase();
  const taken = db
    .prepare('SELECT 1 FROM users WHERE nickname = ? OR email = ?')
    .get(nickname, email);
  if (taken) return { error: 'Nickname or e-mail address is already registered' };
  const key = randomBytes(16).toString('hex');
  const { lastInsertRowid } = db
    .prepare('INSERT INTO users (nickname, email, password, activation_key) VALUES (?, ?, ?, ?)')
    .run(nickname, email, hashPassword(password), key);
  return { id: Number(lastInsertRowid), key };
}

export function activate(db: Database, key: string): boolean {
  if (!key) return false;
  return (
    db.prepare('UPDATE users SET activation_key = NULL WHERE activation_key = ?').run(key)
      .changes === 1
  );
}

/** Login by nickname or e-mail. A successful login cancels a pending account deletion. */
export function authenticate(
  db: Database,
  login: string,
  password: string,
): Result<{ user: User }> {
  const row = db
    .prepare('SELECT * FROM users WHERE nickname = ? OR email = ?')
    .get(login, login.toLowerCase()) as Row | undefined;
  if (!row || !verifyPassword(password, row.password)) return { error: 'Wrong login or password' };
  if (row.activation_key) return { error: 'Account is not activated yet, check your e-mail' };
  db.prepare('UPDATE users SET deletion_requested_at = NULL WHERE id = ?').run(row.id);
  return { user: publicUser(row) };
}

function checkPassword(db: Database, id: number, password: string) {
  const row = db.prepare('SELECT password FROM users WHERE id = ?').get(id) as
    Pick<Row, 'password'> | undefined;
  return !!row && verifyPassword(password, row.password);
}

export function changePassword(
  db: Database,
  id: number,
  current: string,
  next: string,
): string | null {
  if (!checkPassword(db, id, current)) return 'Current password is wrong';
  const error = validatePassword(next);
  if (error) return error;
  db.prepare('UPDATE users SET password = ? WHERE id = ?').run(hashPassword(next), id);
  db.prepare('DELETE FROM sessions WHERE user_id = ?').run(id);
  return null;
}

export function changeEmail(
  db: Database,
  id: number,
  password: string,
  email: string,
): string | null {
  if (!checkPassword(db, id, password)) return 'Password is wrong';
  const error = validateEmail(email);
  if (error) return error;
  email = email.toLowerCase();
  if (db.prepare('SELECT 1 FROM users WHERE email = ? AND id != ?').get(email, id))
    return 'E-mail address is already registered';
  db.prepare('UPDATE users SET email = ? WHERE id = ?').run(email, id);
  return null;
}

export function requestDeletion(db: Database, id: number, now = Date.now()) {
  db.prepare('UPDATE users SET deletion_requested_at = ? WHERE id = ?').run(now, id);
}

/** Replaces the external purge job of the legacy app. */
export function purgeDeleted(db: Database, now = Date.now()) {
  db.prepare('DELETE FROM users WHERE deletion_requested_at < ?').run(now - DELETION_GRACE);
  db.prepare('DELETE FROM sessions WHERE expires_at < ?').run(now);
}

export function createSession(db: Database, userId: number, now = Date.now()): string {
  const token = randomBytes(32).toString('hex');
  db.prepare('INSERT INTO sessions (token_hash, user_id, expires_at) VALUES (?, ?, ?)').run(
    sha256(token),
    userId,
    now + SESSION_TTL,
  );
  return token;
}

export function getSession(db: Database, token: string, now = Date.now()): User | undefined {
  const row = db
    .prepare(
      `SELECT u.* FROM sessions s JOIN users u ON u.id = s.user_id
       WHERE s.token_hash = ? AND s.expires_at > ?`,
    )
    .get(sha256(token), now) as Row | undefined;
  return row && publicUser(row);
}

export function deleteSession(db: Database, token: string) {
  db.prepare('DELETE FROM sessions WHERE token_hash = ?').run(sha256(token));
}
