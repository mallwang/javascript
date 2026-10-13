import Database from 'better-sqlite3';
import { beforeEach, expect, test } from 'vitest';
import { hashPassword, verifyPassword } from '../src/lib/password';
import {
  DELETION_GRACE,
  activate,
  authenticate,
  changeEmail,
  changePassword,
  createSession,
  createUser,
  getSession,
  migrate,
  purgeDeleted,
  requestDeletion,
} from '../src/lib/users';
import { validateEmail, validateNickname, validatePassword } from '../src/lib/validate';

let db: Database.Database;
const reg = { nickname: 'alice', email: 'Alice@Example.com', password: 'secret1' };

beforeEach(() => {
  db = new Database(':memory:');
  db.pragma('foreign_keys = ON');
  migrate(db);
});

test('password hashing is salted and verifiable', () => {
  const [a, b] = [hashPassword('x1y2z3'), hashPassword('x1y2z3')];
  expect(a).not.toBe(b);
  expect(verifyPassword('x1y2z3', a)).toBe(true);
  expect(verifyPassword('wrong', a)).toBe(false);
});

test('validation rules', () => {
  expect(validateNickname('abc')).not.toBeNull();
  expect(validateNickname('a@b.cd')).not.toBeNull();
  expect(validateNickname('alice')).toBeNull();
  expect(validateEmail('nope')).not.toBeNull();
  expect(validateEmail('a@b.de')).toBeNull();
  expect(validatePassword('12345')).not.toBeNull();
});

test('register, activate, login by nickname or e-mail', () => {
  const r = createUser(db, reg);
  if ('error' in r) throw new Error(r.error);
  expect(authenticate(db, 'alice', 'secret1')).toHaveProperty('error');
  expect(activate(db, r.key)).toBe(true);
  expect(activate(db, r.key)).toBe(false);
  expect(authenticate(db, 'ALICE', 'secret1')).toHaveProperty('user');
  expect(authenticate(db, 'alice@example.com', 'secret1')).toHaveProperty('user');
  expect(authenticate(db, 'alice', 'bad')).toHaveProperty('error');
});

test('duplicates are rejected, SQL injection is inert', () => {
  createUser(db, reg);
  expect(createUser(db, { ...reg, nickname: 'other' })).toHaveProperty('error');
  expect(authenticate(db, "' OR 1=1 --", 'x')).toHaveProperty('error');
});

test('profile changes require the current password', () => {
  const r = createUser(db, reg);
  if ('error' in r) throw new Error(r.error);
  activate(db, r.key);
  expect(changePassword(db, r.id, 'bad', 'newsecret')).not.toBeNull();
  expect(changePassword(db, r.id, 'secret1', 'newsecret')).toBeNull();
  expect(authenticate(db, 'alice', 'newsecret')).toHaveProperty('user');
  expect(changeEmail(db, r.id, 'bad', 'n@e.de')).not.toBeNull();
  expect(changeEmail(db, r.id, 'newsecret', 'n@e.de')).toBeNull();
  expect(authenticate(db, 'n@e.de', 'newsecret')).toHaveProperty('user');
});

test('deletion: purged after the grace period, cancelled by login, sessions expire', () => {
  const [a, b] = ['alice', 'bobby'].map((nickname) => {
    const r = createUser(db, { ...reg, nickname, email: `${nickname}@e.de` });
    if ('error' in r) throw new Error(r.error);
    activate(db, r.key);
    return r.id;
  });
  const t0 = 1_000_000;
  requestDeletion(db, a, t0);
  requestDeletion(db, b, t0);
  authenticate(db, 'bobby', 'secret1'); // cancels bob's deletion
  const token = createSession(db, a, t0);
  expect(getSession(db, token, t0)?.id).toBe(a);
  purgeDeleted(db, t0 + DELETION_GRACE + 1);
  expect(db.prepare('SELECT nickname FROM users').all()).toEqual([{ nickname: 'bobby' }]);
  expect(getSession(db, token, t0)).toBeUndefined();
});
