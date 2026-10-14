import type { Db } from './db';
import { assert } from './errors';

const LIMIT = 20;
export type Message = {
  id: number;
  subject: string;
  text: string;
  sent_at: number;
  from_name: string;
  to_name: string;
};

const SELECT = `SELECT m.id, m.subject, m.text, m.sent_at, f.nickname from_name, t.nickname to_name
  FROM messages m JOIN users f ON f.id = m.from_id JOIN users t ON t.id = m.to_id`;

export const inbox = (db: Db, userId: number) =>
  db
    .prepare(`${SELECT} WHERE m.to_id = ? AND m.recipient_deleted = 0 ORDER BY m.id DESC`)
    .all(userId) as Message[];
export const outbox = (db: Db, userId: number) =>
  db
    .prepare(`${SELECT} WHERE m.from_id = ? AND m.sender_deleted = 0 ORDER BY m.id DESC`)
    .all(userId) as Message[];

export function send(
  db: Db,
  fromId: number,
  toNickname: string,
  rawSubject: string,
  rawText: string,
  now = Date.now(),
) {
  const subject = rawSubject.trim();
  const text = rawText.trim();
  assert(subject.length >= 1 && subject.length <= 100, 'Subject must have 1-100 characters.');
  assert(text.length >= 1 && text.length <= 2000, 'Message must have 1-2000 characters.');
  db.transaction(() => {
    const to = db.prepare('SELECT id FROM users WHERE nickname = ?').get(toNickname.trim()) as
      { id: number } | undefined;
    assert(to, 'Recipient not found.');
    const count = (sql: string, id: number) => (db.prepare(sql).get(id) as { n: number }).n;
    assert(
      count('SELECT COUNT(*) n FROM messages WHERE to_id = ? AND recipient_deleted = 0', to.id) <
        LIMIT,
      'The recipient inbox is full.',
    );
    assert(
      count('SELECT COUNT(*) n FROM messages WHERE from_id = ? AND sender_deleted = 0', fromId) <
        LIMIT,
      'Your outbox is full. Delete some messages.',
    );
    db.prepare(
      'INSERT INTO messages (subject, text, sent_at, from_id, to_id) VALUES (?, ?, ?, ?, ?)',
    ).run(subject, text, now, fromId, to.id);
  })();
}

/** Marks the message deleted for the caller's side; the row goes away once both sides deleted it. */
export function remove(db: Db, userId: number, id: number) {
  db.transaction(() => {
    const m = db.prepare('SELECT from_id, to_id FROM messages WHERE id = ?').get(id) as
      { from_id: number; to_id: number } | undefined;
    assert(m && (m.from_id === userId || m.to_id === userId), 'Message not found.');
    if (m.from_id === userId)
      db.prepare('UPDATE messages SET sender_deleted = 1 WHERE id = ?').run(id);
    if (m.to_id === userId)
      db.prepare('UPDATE messages SET recipient_deleted = 1 WHERE id = ?').run(id);
    db.prepare(
      'DELETE FROM messages WHERE id = ? AND sender_deleted = 1 AND recipient_deleted = 1',
    ).run(id);
  })();
}
