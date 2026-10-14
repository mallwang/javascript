import { createHash, randomBytes } from 'node:crypto';
import type { Db } from './db';
import { assert } from './errors';
import { hashPassword, verifyPassword } from './password';

const SESSION_MS = 7 * 24 * 60 * 60 * 1000;
const RESET_MS = 60 * 60 * 1000;

const sha256 = (s: string) => createHash('sha256').update(s).digest('hex');
const token = () => randomBytes(32).toString('hex');

export type User = { id: number; name: string };
export type Profile = {
  firstName: string;
  lastName: string;
  birthDate: string;
  place: string;
  hobbies: string;
  description: string;
};

const checkName = (n: string) => assert(/^\w[\w .-]{1,29}$/.test(n), 'Invalid nickname.');
const checkEmail = (e: string) =>
  assert(e.length <= 50 && /^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(e), 'Invalid e-mail address.');
const checkPassword = (p: string) =>
  assert(p.length >= 8, 'Password must have at least 8 characters.');

/** Creates an inactive account; returns the id and the activation key to send to the user. */
export function register(
  db: Db,
  i: { name: string; email: string; password: string; password2: string },
): { id: number; key: string } {
  const name = i.name.trim();
  const email = i.email.trim().toLowerCase();
  checkName(name);
  checkEmail(email);
  checkPassword(i.password);
  assert(i.password === i.password2, 'Passwords do not match.');
  assert(
    !db.prepare('SELECT 1 FROM users WHERE name = ? OR email = ?').get(name, email),
    'Nickname or e-mail already in use.',
  );
  const key = token();
  const r = db
    .prepare('INSERT INTO users (name, email, password, activation_key) VALUES (?, ?, ?, ?)')
    .run(name, email, hashPassword(i.password), sha256(key));
  return { id: Number(r.lastInsertRowid), key };
}

export function activate(db: Db, key: string) {
  const r = db
    .prepare('UPDATE users SET activation_key = NULL WHERE activation_key = ?')
    .run(sha256(key));
  assert(r.changes === 1, 'Invalid or already used activation link.');
}

/** Login by nickname or e-mail. */
export function authenticate(db: Db, login: string, password: string): number {
  const l = login.trim();
  const u = db
    .prepare('SELECT id, password, activation_key FROM users WHERE name = ? OR email = ?')
    .get(l, l.toLowerCase()) as { id: number; password: string; activation_key: string | null };
  assert(u && verifyPassword(password, u.password), 'Wrong login or password.');
  assert(!u.activation_key, 'Account not activated yet. Use the activation link.');
  return u.id;
}

export function createSession(db: Db, userId: number, now = Date.now()): string {
  const t = token();
  db.prepare('DELETE FROM sessions WHERE expires_at < ?').run(now);
  db.prepare('INSERT INTO sessions VALUES (?, ?, ?)').run(sha256(t), userId, now + SESSION_MS);
  return t;
}

export const deleteSession = (db: Db, t: string) =>
  void db.prepare('DELETE FROM sessions WHERE token_hash = ?').run(sha256(t));

export const getSession = (db: Db, t: string, now = Date.now()) =>
  db
    .prepare(
      `SELECT u.id, u.name FROM sessions s JOIN users u ON u.id = s.user_id
       WHERE s.token_hash = ? AND s.expires_at > ?`,
    )
    .get(sha256(t), now) as User | undefined;

/** Returns a reset token, or undefined for unknown e-mails (callers must not reveal which). */
export function requestReset(db: Db, email: string, now = Date.now()): string | undefined {
  const u = db
    .prepare('SELECT id FROM users WHERE email = ? AND activation_key IS NULL')
    .get(email.trim().toLowerCase()) as { id: number } | undefined;
  if (!u) return undefined;
  const t = token();
  db.prepare('INSERT INTO resets VALUES (?, ?, ?)').run(sha256(t), u.id, now + RESET_MS);
  return t;
}

function setPassword(db: Db, userId: number, password: string) {
  db.prepare('UPDATE users SET password = ? WHERE id = ?').run(hashPassword(password), userId);
  db.prepare('DELETE FROM sessions WHERE user_id = ?').run(userId);
  db.prepare('DELETE FROM resets WHERE user_id = ?').run(userId);
}

export function resetPassword(db: Db, t: string, password: string, now = Date.now()) {
  checkPassword(password);
  const r = db
    .prepare('SELECT user_id FROM resets WHERE token_hash = ? AND expires_at > ?')
    .get(sha256(t), now) as { user_id: number } | undefined;
  assert(r, 'Invalid or expired reset link.');
  setPassword(db, r.user_id, password);
}

function checkPasswordOf(db: Db, userId: number, password: string) {
  const u = db.prepare('SELECT password FROM users WHERE id = ?').get(userId) as {
    password: string;
  };
  assert(verifyPassword(password, u.password), 'Wrong password.');
}

/** Ends all sessions of the user; the caller starts a new one. */
export function changePassword(db: Db, userId: number, current: string, next: string) {
  checkPasswordOf(db, userId, current);
  checkPassword(next);
  setPassword(db, userId, next);
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

export const getEmail = (db: Db, userId: number) =>
  (db.prepare('SELECT email FROM users WHERE id = ?').get(userId) as { email: string }).email;

export function getProfile(db: Db, userId: number) {
  return db
    .prepare(
      `SELECT name, first_name firstName, last_name lastName, birth_date birthDate,
              place, hobbies, description FROM users WHERE id = ?`,
    )
    .get(userId) as (Profile & { name: string }) | undefined;
}

export function updateProfile(db: Db, userId: number, p: Profile) {
  assert(
    p.birthDate === '' ||
      (/^\d{4}-\d{2}-\d{2}$/.test(p.birthDate) && !isNaN(Date.parse(p.birthDate))),
    'Invalid birth date.',
  );
  for (const [v, max] of [
    [p.firstName, 30],
    [p.lastName, 30],
    [p.place, 50],
    [p.hobbies, 200],
    [p.description, 2000],
  ] as const)
    assert(v.length <= max, 'A profile field is too long.');
  db.prepare(
    `UPDATE users SET first_name = ?, last_name = ?, birth_date = ?, place = ?, hobbies = ?,
     description = ? WHERE id = ?`,
  ).run(p.firstName, p.lastName, p.birthDate, p.place, p.hobbies, p.description, userId);
}
