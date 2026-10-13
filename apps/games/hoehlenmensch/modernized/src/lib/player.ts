import { createHash, randomBytes } from 'node:crypto';
import type { Database } from 'better-sqlite3';
import { ACTIVITIES, activity, cost, CRAFT_DURATION, isDuration, reward } from './game';
import { hashPassword, verifyPassword } from './password';

const SESSION_MS = 7 * 24 * 60 * 60 * 1000;
const sha256 = (s: string) => createHash('sha256').update(s).digest('hex');
const NICKNAME = /^[A-Za-z0-9_-]{3,20}$/;

export function register(
  db: Database,
  nickname: string,
  password: string,
): { error: string } | { id: number } {
  if (!NICKNAME.test(nickname)) return { error: 'Nickname: 3-20 letters, digits, _ or -' };
  if (password.length < 8) return { error: 'Password must be at least 8 characters' };
  if (db.prepare('SELECT 1 FROM users WHERE nickname = ?').get(nickname))
    return { error: 'Nickname already taken' };
  return db.transaction(() => {
    const id = Number(
      db
        .prepare('INSERT INTO users (nickname, password) VALUES (?, ?)')
        .run(nickname, hashPassword(password)).lastInsertRowid,
    );
    db.prepare('INSERT INTO player (user_id) VALUES (?)').run(id);
    const ins = db.prepare("INSERT INTO stock VALUES (?, 'resource', ?, ?)");
    for (const [n, q] of [
      ['plant_food', 10],
      ['animal_food', 10],
      ['water', 10],
      ['wood', 0],
      ['stone', 0],
    ] as const)
      ins.run(id, n, q);
    return { id };
  })();
}

export function authenticate(
  db: Database,
  nickname: string,
  password: string,
): { error: string } | { id: number } {
  const u = db
    .prepare('SELECT id, nickname, password FROM users WHERE nickname = ?')
    .get(nickname) as { id: number; nickname: string; password: string } | undefined;
  // same message for unknown user and wrong password
  return u && verifyPassword(password, u.password)
    ? { id: u.id }
    : { error: 'Invalid nickname or password' };
}

export function createSession(db: Database, userId: number, now = Date.now()) {
  const token = randomBytes(32).toString('hex');
  db.prepare('DELETE FROM sessions WHERE expires_at < ?').run(now);
  db.prepare('INSERT INTO sessions VALUES (?, ?, ?)').run(sha256(token), userId, now + SESSION_MS);
  return token;
}

export function getSession(db: Database, token: string, now = Date.now()) {
  return db
    .prepare(
      'SELECT u.id, u.nickname FROM sessions s JOIN users u ON u.id = s.user_id WHERE s.token_hash = ? AND s.expires_at > ?',
    )
    .get(sha256(token), now) as { id: number; nickname: string } | undefined;
}

export const deleteSession = (db: Database, token: string) =>
  db.prepare('DELETE FROM sessions WHERE token_hash = ?').run(sha256(token));

const add = (db: Database, uid: number, kind: string, name: string, qty: number) =>
  db
    .prepare(
      `INSERT INTO stock VALUES (?, ?, ?, ?)
       ON CONFLICT (user_id, kind, name) DO UPDATE SET qty = ROUND(qty + excluded.qty, 2)`,
    )
    .run(uid, kind, name, qty);

/** Credit a finished job. Clearing `activity` in the same transaction makes it credit exactly once. */
export function settle(db: Database, uid: number, now = Date.now(), rand = Math.random) {
  db.transaction(() => {
    const p = db
      .prepare('SELECT activity, duration, ends_at FROM player WHERE user_id = ?')
      .get(uid) as { activity: number; duration: number; ends_at: number } | undefined;
    const a = p && activity(p.activity);
    if (!p || !a || p.ends_at > now || !isDuration(p.duration)) return;
    const row = a.foodKind
      ? (db
          .prepare('SELECT points FROM food WHERE kind = ? ORDER BY RANDOM() LIMIT 1')
          .get(a.foodKind) as { points: number })
      : undefined;
    const r = reward(a, p.duration, rand, row?.points);
    add(db, uid, a.item ? 'item' : 'resource', (a.item ?? a.resource)!, r.amount);
    add(db, uid, 'skill', a.skill, r.skill);
    db.prepare(
      'UPDATE player SET xp = xp + 1, activity = 0, duration = 0, ends_at = 0 WHERE user_id = ?',
    ).run(uid);
  })();
}

export function start(db: Database, uid: number, id: number, minutes: number, now = Date.now()) {
  const a = activity(id);
  const d = a?.item ? CRAFT_DURATION : minutes;
  if (!a || !isDuration(d)) return 'Unknown activity or duration';
  return db.transaction(() => {
    settle(db, uid, now);
    const p = db.prepare('SELECT activity FROM player WHERE user_id = ?').get(uid) as {
      activity: number;
    };
    if (p.activity) return 'You are already busy';
    const c = cost(d);
    const have = (n: string) =>
      (
        db
          .prepare("SELECT qty FROM stock WHERE user_id = ? AND kind = 'resource' AND name = ?")
          .get(uid, n) as { qty: number }
      ).qty;
    // either food type can feed the caveman, animal food first
    const animal = Math.min(have('animal_food'), c.food);
    const plant = c.food - animal;
    if (plant > have('plant_food') || c.water > have('water')) return 'Not enough food or water';
    add(db, uid, 'resource', 'animal_food', -animal);
    add(db, uid, 'resource', 'plant_food', -plant);
    add(db, uid, 'resource', 'water', -c.water);
    db.prepare('UPDATE player SET activity = ?, duration = ?, ends_at = ? WHERE user_id = ?').run(
      id,
      d,
      now + d * 60_000,
      uid,
    );
  })();
}

export function getPlayer(db: Database, uid: number, now = Date.now()) {
  settle(db, uid, now);
  const p = db
    .prepare('SELECT xp, activity, duration, ends_at FROM player WHERE user_id = ?')
    .get(uid) as {
    xp: number;
    activity: number;
    duration: number;
    ends_at: number;
  };
  const stock = (kind: string) =>
    Object.fromEntries(
      (
        db
          .prepare('SELECT name, qty FROM stock WHERE user_id = ? AND kind = ? ORDER BY name')
          .all(uid, kind) as { name: string; qty: number }[]
      ).map((r) => [r.name, r.qty]),
    );
  return {
    ...p,
    current: activity(p.activity),
    resources: stock('resource'),
    items: stock('item'),
    skills: stock('skill'),
  };
}

export const craftable = ACTIVITIES.filter((a) => a.item);
