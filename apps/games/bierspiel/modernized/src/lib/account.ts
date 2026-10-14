import { createHash, randomBytes } from 'node:crypto';
import type { Db } from './db';
import { assert } from './errors';
import { LOCATIONS } from './goods';
import { hashPassword, verifyPassword } from './password';

const SESSION_MS = 7 * 24 * 60 * 60 * 1000;
/** Pending account deletions are purged by the tick after this grace period. */
export const DELETION_GRACE_MS = 7 * 24 * 60 * 60 * 1000;
export const START_BALANCE = 200_000;

const sha256 = (s: string) => createHash('sha256').update(s).digest('hex');

export type User = { id: number; nickname: string; companyId: number; companyName: string };

const checkNickname = (n: string) => assert(/^\w[\w .-]{1,29}$/.test(n), 'Invalid nickname.');
const checkEmail = (e: string) =>
  assert(/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(e), 'Invalid e-mail address.');
const checkPassword = (p: string) =>
  assert(p.length >= 8, 'Password must have at least 8 characters.');
const checkCompany = (n: string) =>
  assert(/^[\w][\w .&'-]{1,39}$/.test(n), 'Invalid company name.');

export function register(
  db: Db,
  i: {
    nickname: string;
    email: string;
    password: string;
    password2: string;
    company: string;
    hq: number;
  },
  now = Date.now(),
): number {
  const nickname = i.nickname.trim();
  const email = i.email.trim().toLowerCase();
  const company = i.company.trim();
  checkNickname(nickname);
  checkEmail(email);
  checkPassword(i.password);
  assert(i.password === i.password2, 'Passwords do not match.');
  checkCompany(company);
  assert(Number.isInteger(i.hq) && i.hq >= 1 && i.hq <= LOCATIONS.length, 'Invalid location.');
  return db.transaction(() => {
    assert(
      !db.prepare('SELECT 1 FROM users WHERE nickname = ? OR email = ?').get(nickname, email),
      'Nickname or e-mail already in use.',
    );
    assert(
      !db.prepare('SELECT 1 FROM companies WHERE name = ?').get(company),
      'Company name already in use.',
    );
    const u = db
      .prepare('INSERT INTO users (nickname, email, password, created_at) VALUES (?, ?, ?, ?)')
      .run(nickname, email, hashPassword(i.password), now);
    db.prepare('INSERT INTO companies (user_id, name, hq, balance) VALUES (?, ?, ?, ?)').run(
      u.lastInsertRowid,
      company,
      i.hq,
      START_BALANCE,
    );
    return Number(u.lastInsertRowid);
  })();
}

/** Login by nickname or e-mail; a successful login cancels a pending account deletion. */
export function authenticate(db: Db, login: string, password: string, now = Date.now()): number {
  const l = login.trim();
  const u = db
    .prepare('SELECT id, password FROM users WHERE nickname = ? OR email = ?')
    .get(l, l.toLowerCase()) as { id: number; password: string } | undefined;
  assert(u && verifyPassword(password, u.password), 'Wrong login or password.');
  db.prepare('UPDATE users SET logins = logins + 1, last_login = ? WHERE id = ?').run(now, u.id);
  db.prepare('DELETE FROM deletions WHERE user_id = ?').run(u.id);
  return u.id;
}

export function createSession(db: Db, userId: number, now = Date.now()): string {
  const token = randomBytes(32).toString('hex');
  db.prepare('DELETE FROM sessions WHERE expires_at < ?').run(now);
  db.prepare('INSERT INTO sessions VALUES (?, ?, ?)').run(sha256(token), userId, now + SESSION_MS);
  return token;
}

export const deleteSession = (db: Db, token: string) =>
  void db.prepare('DELETE FROM sessions WHERE token_hash = ?').run(sha256(token));

export function getSession(db: Db, token: string, now = Date.now()): User | undefined {
  const u = db
    .prepare(
      `SELECT u.id, u.nickname, c.id companyId, c.name companyName FROM sessions s
       JOIN users u ON u.id = s.user_id JOIN companies c ON c.user_id = u.id
       WHERE s.token_hash = ? AND s.expires_at > ?`,
    )
    .get(sha256(token), now) as User | undefined;
  return u;
}

/** Users with a session that is still valid (replaces the legacy `status` flag). */
export const onlineUsers = (db: Db, now = Date.now()) =>
  db
    .prepare(
      'SELECT DISTINCT u.nickname FROM sessions s JOIN users u ON u.id = s.user_id WHERE s.expires_at > ? ORDER BY u.nickname',
    )
    .all(now) as { nickname: string }[];

function checkPasswordOf(db: Db, userId: number, password: string) {
  const u = db.prepare('SELECT password FROM users WHERE id = ?').get(userId) as {
    password: string;
  };
  assert(verifyPassword(password, u.password), 'Wrong password.');
}

export function changePassword(db: Db, userId: number, current: string, next: string) {
  checkPasswordOf(db, userId, current);
  checkPassword(next);
  db.prepare('UPDATE users SET password = ? WHERE id = ?').run(hashPassword(next), userId);
}

export function changeEmail(db: Db, userId: number, password: string, raw: string) {
  checkPasswordOf(db, userId, password);
  const email = raw.trim().toLowerCase();
  checkEmail(email);
  assert(
    !db.prepare('SELECT 1 FROM users WHERE email = ? AND id <> ?').get(email, userId),
    'E-mail already in use.',
  );
  db.prepare('UPDATE users SET email = ? WHERE id = ?').run(email, userId);
}

export function renameCompany(db: Db, userId: number, raw: string) {
  const name = raw.trim();
  checkCompany(name);
  assert(
    !db.prepare('SELECT 1 FROM companies WHERE name = ? AND user_id <> ?').get(name, userId),
    'Company name already in use.',
  );
  db.prepare('UPDATE companies SET name = ? WHERE user_id = ?').run(name, userId);
}

export function requestDeletion(db: Db, userId: number, password: string, now = Date.now()) {
  checkPasswordOf(db, userId, password);
  db.prepare('INSERT OR REPLACE INTO deletions VALUES (?, ?)').run(userId, now);
}

/** Removes accounts whose deletion was requested before the grace period; cascades to all game data. */
export function purgeDeletions(db: Db, now = Date.now()): number {
  return db
    .prepare(
      'DELETE FROM users WHERE id IN (SELECT user_id FROM deletions WHERE requested_at <= ?)',
    )
    .run(now - DELETION_GRACE_MS).changes;
}
