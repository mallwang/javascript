import { createHash, randomBytes } from 'node:crypto';
import type Database from 'better-sqlite3';
import { hashPassword, verifyPassword } from './password';

type Db = Database.Database;

export const CATEGORIES = { sight: 'Sights', park: 'Parks', museum: 'Museums' } as const;
export type Category = keyof typeof CATEGORIES;

export const STATES = [
  'Baden-Württemberg',
  'Bayern',
  'Berlin',
  'Brandenburg',
  'Bremen',
  'Hamburg',
  'Hessen',
  'Mecklenburg-Vorpommern',
  'Niedersachsen',
  'Nordrhein-Westfalen',
  'Rheinland-Pfalz',
  'Saarland',
  'Sachsen',
  'Sachsen-Anhalt',
  'Schleswig-Holstein',
  'Thüringen',
];

const SESSION_MS = 7 * 24 * 60 * 60 * 1000;
const sha256 = (s: string) => createHash('sha256').update(s).digest('hex');
const isCategory = (c: string): c is Category => c in CATEGORIES;

// ---- accounts ----

export type User = { id: number; nickname: string; isAdmin: boolean };

export function register(
  db: Db,
  i: { nickname: string; email: string; password: string },
): { id: number } | { error: string } {
  const nickname = i.nickname.trim();
  const email = i.email.trim();
  if (!/^\w[\w .-]{1,29}$/.test(nickname)) return { error: 'Invalid nickname' };
  if (!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email)) return { error: 'Invalid e-mail address' };
  if (i.password.length < 8) return { error: 'Password must have at least 8 characters' };
  try {
    const r = db
      .prepare('INSERT INTO users (nickname, email, password) VALUES (?, ?, ?)')
      .run(nickname, email, hashPassword(i.password));
    return { id: Number(r.lastInsertRowid) };
  } catch {
    return { error: 'Nickname or e-mail already in use' };
  }
}

/** Login by nickname or e-mail, like the legacy form. */
export function authenticate(
  db: Db,
  login: string,
  password: string,
): { id: number } | { error: string } {
  const u = db
    .prepare('SELECT id, password FROM users WHERE nickname = ? OR email = ?')
    .get(login.trim(), login.trim()) as { id: number; password: string } | undefined;
  return u && verifyPassword(password, u.password)
    ? { id: u.id }
    : { error: 'Wrong login or password' };
}

export function createSession(db: Db, userId: number, now = Date.now()) {
  const token = randomBytes(32).toString('hex');
  db.prepare('DELETE FROM sessions WHERE expires_at < ?').run(now);
  db.prepare('INSERT INTO sessions VALUES (?, ?, ?)').run(sha256(token), userId, now + SESSION_MS);
  return token;
}

export function deleteSession(db: Db, token: string) {
  db.prepare('DELETE FROM sessions WHERE token_hash = ?').run(sha256(token));
}

export function getSession(db: Db, token: string, now = Date.now()): User | undefined {
  const u = db
    .prepare(
      `SELECT u.id, u.nickname, u.is_admin FROM sessions s JOIN users u ON u.id = s.user_id
       WHERE s.token_hash = ? AND s.expires_at > ?`,
    )
    .get(sha256(token), now) as { id: number; nickname: string; is_admin: number } | undefined;
  return u && { id: u.id, nickname: u.nickname, isAdmin: u.is_admin === 1 };
}

// ---- places ----

export type PlaceInput = {
  category: string;
  state: string;
  city: string;
  name: string;
  description: string;
  lat: number;
  lng: number;
};
export type Place = PlaceInput & { id: number; category: Category };

export function validatePlace(i: PlaceInput): string | undefined {
  if (!isCategory(i.category)) return 'Unknown category';
  if (!STATES.includes(i.state)) return 'Unknown state';
  if (!i.city.trim() || !i.name.trim()) return 'City and name are required';
  if (!(Math.abs(i.lat) <= 90) || !(Math.abs(i.lng) <= 180)) return 'Invalid coordinates';
}

/** Anyone may submit; places stay `pending` until an admin approves them. */
export function submitPlace(db: Db, i: PlaceInput, userId?: number): string | undefined {
  const error = validatePlace(i);
  if (error) return error;
  db.prepare(
    `INSERT INTO places (category, state, city, name, description, lat, lng, submitted_by)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?)`,
  ).run(
    i.category,
    i.state,
    i.city.trim(),
    i.name.trim(),
    i.description.trim(),
    i.lat,
    i.lng,
    userId ?? null,
  );
}

const placesBy = (db: Db, status: string, city = '', category = '') =>
  db
    .prepare(
      `SELECT id, category, state, city, name, description, lat, lng FROM places
       WHERE status = ? AND (? = '' OR city = ? COLLATE NOCASE) AND (? = '' OR category = ?)
       ORDER BY city, name`,
    )
    .all(status, city, city, category, category) as Place[];

export const listPlaces = (db: Db, city?: string, category?: string) =>
  placesBy(db, 'approved', city?.trim(), category);
export const listPending = (db: Db) => placesBy(db, 'pending');

export function cities(db: Db) {
  return (
    db
      .prepare(`SELECT DISTINCT city FROM places WHERE status = 'approved' ORDER BY city`)
      .all() as { city: string }[]
  ).map((r) => r.city);
}

export function moderate(db: Db, actor: User | undefined, id: number, approve: boolean) {
  if (!actor?.isAdmin) return 'Not allowed';
  db.prepare(
    approve
      ? `UPDATE places SET status = 'approved' WHERE id = ?`
      : `DELETE FROM places WHERE id = ? AND status = 'pending'`,
  ).run(id);
}

const esc = (s: string | number) =>
  String(s).replace(
    /[<>&"']/g,
    (c) => ({ '<': '&lt;', '>': '&gt;', '&': '&amp;', '"': '&quot;', "'": '&apos;' })[c]!,
  );

/** Same document shape as the legacy `getPlaces.php`, but escaped. */
export function placesXml(places: Place[]) {
  const items = places
    .map(
      (p) =>
        `<objekt><name>${esc(p.name)}</name><beschreibung>${esc(p.description)}</beschreibung>` +
        `<lat>${p.lat}</lat><long>${p.lng}</long></objekt>`,
    )
    .join('');
  return `<?xml version="1.0" encoding="UTF-8"?><datensaetze>${items}</datensaetze>`;
}

// ---- library ----

export type Book = { id: number; title: string; author: string; year: number | null };

export function searchBooks(db: Db, category = '', format = '', q = '') {
  return db
    .prepare(
      `SELECT id, title, author, year FROM books
       WHERE (? = '' OR category = ?) AND (? = '' OR format = ?)
         AND (? = '' OR title LIKE '%' || ? || '%' OR author LIKE '%' || ? || '%')
       ORDER BY title`,
    )
    .all(category, category, format, format, q, q, q) as Book[];
}

export function distinct(db: Db, column: 'category' | 'format') {
  return (
    db
      .prepare(`SELECT DISTINCT ${column} AS v FROM books WHERE ${column} <> '' ORDER BY v`)
      .all() as { v: string }[]
  ).map((r) => r.v);
}

// ---- support ----

export function addSupport(
  db: Db,
  i: { subject: string; email: string; text: string },
  now = Date.now(),
): string | undefined {
  if (!i.subject.trim() || !i.text.trim()) return 'Subject and message are required';
  if (!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(i.email.trim())) return 'Invalid e-mail address';
  db.prepare('INSERT INTO support (subject, email, text, created_at) VALUES (?, ?, ?, ?)').run(
    i.subject.trim(),
    i.email.trim(),
    i.text.trim(),
    now,
  );
}

// ---- memory game ----

/** Shuffled deck of `pairs` pairs (Fisher-Yates); each card value 1..pairs appears twice. */
export function makeDeck(pairs: number, rnd: () => number = Math.random): number[] {
  const deck = Array.from({ length: pairs * 2 }, (_, i) => (i >> 1) + 1);
  for (let i = deck.length - 1; i > 0; i--) {
    const j = Math.floor(rnd() * (i + 1));
    [deck[i], deck[j]] = [deck[j]!, deck[i]!];
  }
  return deck;
}
