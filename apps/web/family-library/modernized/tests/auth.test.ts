import { describe, expect, it } from 'vitest';
import * as auth from '../src/lib/auth';
import { openDb } from '../src/lib/db';
import { allow } from '../src/lib/security';
import { validateBook, validateRegistration } from '../src/lib/validate';

const input = { firstName: 'Ada', lastName: 'L', email: 'ada@example.com', password: 'secret123' };

function setup() {
  const db = openDb(':memory:');
  const token = auth.register(db, input) as string;
  return { db, token };
}

describe('registration and verification', () => {
  it('creates an unverified user and stores only token hashes and password hashes', () => {
    const { db, token } = setup();
    expect(db.prepare('SELECT verified, role, password_hash FROM user').get()).toMatchObject({
      verified: 0,
      role: 'user',
    });
    const stored = JSON.stringify(db.prepare('SELECT * FROM user').all());
    expect(stored).not.toContain('secret123');
    expect(JSON.stringify(db.prepare('SELECT * FROM token').all())).not.toContain(token);
  });

  it('does not reveal an existing email', () => {
    const { db } = setup();
    expect(auth.register(db, input)).toBeNull();
  });

  it('blocks login until verified; tokens are single use', () => {
    const { db, token } = setup();
    expect(auth.login(db, input.email, input.password)).toBeNull();
    expect(auth.verifyEmail(db, token)).toBe(true);
    expect(auth.verifyEmail(db, token)).toBe(false);
    expect(auth.login(db, input.email, input.password)).toBeTruthy();
    expect(auth.login(db, input.email, 'wrong-password')).toBeNull();
  });

  it('rejects expired tokens', () => {
    const { db, token } = setup();
    db.prepare('UPDATE token SET expires_at = 1').run();
    expect(auth.verifyEmail(db, token)).toBe(false);
  });

  it('only ADMIN_EMAIL becomes admin', () => {
    const { db, token } = setup();
    auth.verifyEmail(db, token, 'ADA@example.com');
    expect(db.prepare('SELECT role FROM user').get()).toEqual({ role: 'admin' });
    const other = auth.register(db, { ...input, email: 'b@example.com' }) as string;
    auth.verifyEmail(db, other, 'ada@example.com');
    expect(db.prepare("SELECT role FROM user WHERE email = 'b@example.com'").get()).toEqual({
      role: 'user',
    });
  });
});

describe('sessions and password reset', () => {
  it('resolves, expires and ends sessions', () => {
    const { db, token } = setup();
    auth.verifyEmail(db, token);
    const session = auth.login(db, input.email, input.password) as string;
    expect(auth.sessionUser(db, session)).toMatchObject({ email: input.email, role: 'user' });
    db.prepare('UPDATE session SET expires_at = 1').run();
    expect(auth.sessionUser(db, session)).toBeNull();
    const again = auth.login(db, input.email, input.password) as string;
    auth.logout(db, again);
    expect(auth.sessionUser(db, again)).toBeNull();
  });

  it('reset is single use, only for verified accounts, and ends all sessions', () => {
    const { db, token } = setup();
    expect(auth.forgotPassword(db, input.email)).toBeNull();
    auth.verifyEmail(db, token);
    const session = auth.login(db, input.email, input.password) as string;
    const reset = auth.forgotPassword(db, input.email) as string;
    expect(auth.resetPassword(db, reset, 'new-secret-1')).toBe(true);
    expect(auth.resetPassword(db, reset, 'other-secret-1')).toBe(false);
    expect(auth.sessionUser(db, session)).toBeNull();
    expect(auth.login(db, input.email, input.password)).toBeNull();
    expect(auth.login(db, input.email, 'new-secret-1')).toBeTruthy();
  });
});

describe('validation and rate limit', () => {
  it('validates registration and books', () => {
    expect(validateRegistration(input)).toBeNull();
    expect(validateRegistration({ ...input, password: 'short' })).toBe('invalid_password');
    expect(validateRegistration({ ...input, email: 'nope' })).toBe('invalid_email');
    const book = { title: 'T', author: 'A', isbn: '9783161484100', year: 2000, language: 'de' };
    expect(validateBook(book)).toBeNull();
    expect(validateBook({ ...book, isbn: '123' })).toBe('invalid_isbn');
    expect(validateBook({ ...book, year: 1800 })).toBe('invalid_year');
  });

  it('limits attempts per key', () => {
    const results = Array.from({ length: 4 }, () => allow('k', 3, 1000, 0));
    expect(results).toEqual([true, true, true, false]);
    expect(allow('k', 3, 1000, 2000)).toBe(true);
  });
});
