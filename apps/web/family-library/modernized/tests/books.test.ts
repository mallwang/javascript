import { describe, expect, it } from 'vitest';
import type { SessionUser } from '../src/lib/auth';
import { createBook, deleteBook, getBook, listBooks, updateBook } from '../src/lib/books';
import { openDb } from '../src/lib/db';
import { deleteUser, setRole } from '../src/lib/users';

const book = { title: 'T', author: 'A', isbn: '9783161484100', year: 2000, language: 'de' };

function setup() {
  const db = openDb(':memory:');
  const add = (n: number, role: 'admin' | 'user'): SessionUser => {
    db.prepare(
      "INSERT INTO user (id, first_name, last_name, email, password_hash, role, verified, updated_at) VALUES (?, 'F', 'L', ?, 'x:y', ?, 1, '')",
    ).run(n, `u${n}@example.com`, role);
    return { id: n, firstName: 'F', lastName: 'L', email: `u${n}@example.com`, role };
  };
  return { db, owner: add(1, 'user'), other: add(2, 'user'), admin: add(3, 'admin') };
}

describe('book ownership', () => {
  it('sets the owner server-side and rejects duplicate ISBNs', () => {
    const { db, owner } = setup();
    expect(createBook(db, owner, book)).toBe('ok');
    expect(createBook(db, owner, book)).toBe('duplicate_isbn');
    expect(listBooks(db)[0]).toMatchObject({ ownerId: 1, ownerName: 'F L' });
  });

  it('lets only the owner or an admin update and delete', () => {
    const { db, owner, other, admin } = setup();
    createBook(db, owner, book);
    const id = listBooks(db)[0].id;
    expect(updateBook(db, other, id, { ...book, title: 'X' })).toBe('forbidden');
    expect(deleteBook(db, other, id)).toBe('forbidden');
    expect(getBook(db, id)?.title).toBe('T');
    expect(updateBook(db, owner, id, { ...book, title: 'Y' })).toBe('ok');
    expect(updateBook(db, admin, id, { ...book, title: 'Z' })).toBe('ok');
    expect(updateBook(db, owner, 999, book)).toBe('notfound');
    expect(deleteBook(db, admin, id)).toBe('ok');
  });
});

describe('user administration', () => {
  it('is admin-only and an admin cannot change or delete themselves', () => {
    const { db, other, admin } = setup();
    expect(setRole(db, other, 1, 'admin')).toBe(false);
    expect(deleteUser(db, other, 1)).toBe(false);
    expect(setRole(db, admin, 3, 'user')).toBe(false);
    expect(deleteUser(db, admin, 3)).toBe(false);
    expect(setRole(db, admin, 1, 'admin')).toBe(true);
    expect(deleteUser(db, admin, 1)).toBe(true);
  });
});
