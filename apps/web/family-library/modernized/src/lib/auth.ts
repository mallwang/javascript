import type { Db } from './db';
import { hashPassword, newToken, sha256, verifyPassword } from './security';
import { normalizeEmail, type RegistrationInput } from './validate';

export type Role = 'admin' | 'user';
export type SessionUser = {
  id: number;
  firstName: string;
  lastName: string;
  email: string;
  role: Role;
};

const HOUR = 3_600_000;
export const SESSION_TTL = 7 * 24 * HOUR;
const VERIFY_TTL = 24 * HOUR;
const RESET_TTL = HOUR;

// Compared against when the email is unknown, so login timing does not reveal registered emails.
const DUMMY_HASH = hashPassword('dummy');

type Purpose = 'verify' | 'reset';

function issueToken(db: Db, userId: number, purpose: Purpose, ttl: number): string {
  const token = newToken();
  db.prepare('DELETE FROM token WHERE user_id = ? AND purpose = ?').run(userId, purpose);
  db.prepare(
    'INSERT INTO token (token_hash, user_id, purpose, expires_at) VALUES (?, ?, ?, ?)',
  ).run(sha256(token), userId, purpose, Date.now() + ttl);
  return token;
}

/** Single use: the token is deleted whether or not it is still valid. */
function consumeToken(db: Db, token: string, purpose: Purpose): number | null {
  const hash = sha256(token);
  const row = db
    .prepare('SELECT user_id, expires_at FROM token WHERE token_hash = ? AND purpose = ?')
    .get(hash, purpose) as { user_id: number; expires_at: number } | undefined;
  if (!row) return null;
  db.prepare('DELETE FROM token WHERE token_hash = ?').run(hash);
  return row.expires_at > Date.now() ? row.user_id : null;
}

/** Creates an unverified `user` account; returns the verification token, or null if the email is taken. */
export function register(db: Db, i: RegistrationInput): string | null {
  const result = db
    .prepare(
      `INSERT OR IGNORE INTO user (first_name, last_name, email, password_hash, updated_at)
       VALUES (?, ?, ?, ?, ?)`,
    )
    .run(i.firstName, i.lastName, i.email, hashPassword(i.password), new Date().toISOString());
  return result.changes
    ? issueToken(db, Number(result.lastInsertRowid), 'verify', VERIFY_TTL)
    : null;
}

/** `adminEmail` (env ADMIN_EMAIL) becomes admin once its address is verified. */
export function verifyEmail(db: Db, token: string, adminEmail = ''): boolean {
  const userId = consumeToken(db, token, 'verify');
  if (userId === null) return false;
  db.prepare('UPDATE user SET verified = 1 WHERE id = ?').run(userId);
  if (adminEmail) {
    db.prepare("UPDATE user SET role = 'admin' WHERE id = ? AND email = ?").run(
      userId,
      normalizeEmail(adminEmail),
    );
  }
  return true;
}

/** Returns a verification token for an existing unverified account, otherwise null. */
export function resendVerification(db: Db, email: string): string | null {
  const u = db.prepare('SELECT id FROM user WHERE email = ? AND verified = 0').get(email) as
    { id: number } | undefined;
  return u ? issueToken(db, u.id, 'verify', VERIFY_TTL) : null;
}

/** Returns a session token, or null for wrong credentials and unverified accounts. */
export function login(db: Db, email: string, password: string): string | null {
  const u = db
    .prepare('SELECT id, password_hash, verified FROM user WHERE email = ?')
    .get(email) as { id: number; password_hash: string; verified: number } | undefined;
  const ok = verifyPassword(password, u?.password_hash ?? DUMMY_HASH);
  if (!u || !ok || !u.verified) return null;
  const token = newToken();
  db.prepare('INSERT INTO session (token_hash, user_id, expires_at) VALUES (?, ?, ?)').run(
    sha256(token),
    u.id,
    Date.now() + SESSION_TTL,
  );
  return token;
}

export function sessionUser(db: Db, token: string): SessionUser | null {
  const hash = sha256(token);
  const row = db
    .prepare(
      `SELECT u.id, u.first_name AS firstName, u.last_name AS lastName, u.email, u.role,
              s.expires_at AS expiresAt
       FROM session s JOIN user u ON u.id = s.user_id WHERE s.token_hash = ?`,
    )
    .get(hash) as (SessionUser & { expiresAt: number }) | undefined;
  if (!row) return null;
  if (row.expiresAt <= Date.now()) {
    logout(db, token);
    return null;
  }
  return {
    id: row.id,
    firstName: row.firstName,
    lastName: row.lastName,
    email: row.email,
    role: row.role,
  };
}

export function logout(db: Db, token: string): void {
  db.prepare('DELETE FROM session WHERE token_hash = ?').run(sha256(token));
}

/** Returns a reset token for a verified account, otherwise null. */
export function forgotPassword(db: Db, email: string): string | null {
  const u = db.prepare('SELECT id FROM user WHERE email = ? AND verified = 1').get(email) as
    { id: number } | undefined;
  return u ? issueToken(db, u.id, 'reset', RESET_TTL) : null;
}

/** Sets the new password and signs the user out everywhere. */
export function resetPassword(db: Db, token: string, password: string): boolean {
  const userId = consumeToken(db, token, 'reset');
  if (userId === null) return false;
  db.prepare('UPDATE user SET password_hash = ?, updated_at = ? WHERE id = ?').run(
    hashPassword(password),
    new Date().toISOString(),
    userId,
  );
  db.prepare('DELETE FROM session WHERE user_id = ?').run(userId);
  return true;
}
