import type { SessionUser } from './auth';
import type { Db } from './db';
import type { BookInput } from './validate';

export type Book = BookInput & { id: number; ownerId: number; ownerName: string };
export type BookResult = 'ok' | 'notfound' | 'forbidden' | 'duplicate_isbn';

const SELECT = `SELECT b.id, b.title, b.author, b.isbn, b.year, b.language, b.owner_id AS ownerId,
  u.first_name || ' ' || u.last_name AS ownerName FROM book b JOIN user u ON u.id = b.owner_id`;

export const listBooks = (db: Db) => db.prepare(`${SELECT} ORDER BY b.title`).all() as Book[];

export const getBook = (db: Db, id: number) =>
  db.prepare(`${SELECT} WHERE b.id = ?`).get(id) as Book | undefined;

const isDuplicate = (e: unknown) => (e as { code?: string }).code === 'SQLITE_CONSTRAINT_UNIQUE';

/** The owner is always the signed-in user, never taken from the request. */
export function createBook(db: Db, user: SessionUser, i: BookInput): BookResult {
  try {
    db.prepare(
      `INSERT INTO book (title, author, isbn, year, language, owner_id, updated_at)
       VALUES (?, ?, ?, ?, ?, ?, ?)`,
    ).run(i.title, i.author, i.isbn, i.year, i.language, user.id, new Date().toISOString());
    return 'ok';
  } catch (e) {
    if (isDuplicate(e)) return 'duplicate_isbn';
    throw e;
  }
}

function authorize(db: Db, user: SessionUser, id: number): BookResult {
  const book = getBook(db, id);
  if (!book) return 'notfound';
  return book.ownerId === user.id || user.role === 'admin' ? 'ok' : 'forbidden';
}

export function updateBook(db: Db, user: SessionUser, id: number, i: BookInput): BookResult {
  const auth = authorize(db, user, id);
  if (auth !== 'ok') return auth;
  try {
    db.prepare(
      `UPDATE book SET title = ?, author = ?, isbn = ?, year = ?, language = ?, updated_at = ?
       WHERE id = ?`,
    ).run(i.title, i.author, i.isbn, i.year, i.language, new Date().toISOString(), id);
    return 'ok';
  } catch (e) {
    if (isDuplicate(e)) return 'duplicate_isbn';
    throw e;
  }
}

export function deleteBook(db: Db, user: SessionUser, id: number): BookResult {
  const auth = authorize(db, user, id);
  if (auth === 'ok') db.prepare('DELETE FROM book WHERE id = ?').run(id);
  return auth;
}
