import type { Db } from './db';
import { assert } from './errors';

export const PAGE_SIZE = 5;

export type Comment = {
  id: number;
  userId: number;
  author: string;
  subject: string;
  content: string;
  createdAt: number;
};
export type Post = {
  id: number;
  userId: number;
  author: string;
  subject: string;
  content: string;
  createdAt: number;
  comments: Comment[];
};

function checkText(subject: string, content: string) {
  assert(subject.length >= 1 && subject.length <= 50, 'Subject must have 1 to 50 characters.');
  assert(
    content.trim().length >= 1 && content.length <= 5000,
    'Text must have 1 to 5000 characters.',
  );
}

export function addPost(
  db: Db,
  userId: number,
  subject: string,
  content: string,
  now = Date.now(),
) {
  subject = subject.trim();
  checkText(subject, content);
  db.prepare('INSERT INTO blogs (user_id, subject, content, created_at) VALUES (?, ?, ?, ?)').run(
    userId,
    subject,
    content,
    now,
  );
}

export function deletePost(db: Db, userId: number, postId: number) {
  const r = db.prepare('DELETE FROM blogs WHERE id = ? AND user_id = ?').run(postId, userId);
  assert(r.changes === 1, 'Post not found.');
}

export function addComment(
  db: Db,
  userId: number,
  postId: number,
  subject: string,
  content: string,
  now = Date.now(),
) {
  subject = subject.trim();
  checkText(subject, content);
  assert(db.prepare('SELECT 1 FROM blogs WHERE id = ?').get(postId), 'Post not found.');
  db.prepare(
    'INSERT INTO comments (blog_id, user_id, subject, content, created_at) VALUES (?, ?, ?, ?, ?)',
  ).run(postId, userId, subject, content, now);
}

/** The comment author or the owner of the post may delete a comment. */
export function deleteComment(db: Db, userId: number, commentId: number) {
  const r = db
    .prepare(
      `DELETE FROM comments WHERE id = ? AND (user_id = ?
         OR blog_id IN (SELECT id FROM blogs WHERE user_id = ?))`,
    )
    .run(commentId, userId, userId);
  assert(r.changes === 1, 'Comment not found.');
}

function withComments(db: Db, where: string, params: number[], page: number) {
  const total = (
    db.prepare(`SELECT COUNT(*) n FROM blogs b WHERE ${where}`).get(...params) as { n: number }
  ).n;
  const posts = db
    .prepare(
      `SELECT b.id, b.user_id userId, u.name author, b.subject, b.content, b.created_at createdAt
       FROM blogs b JOIN users u ON u.id = b.user_id WHERE ${where}
       ORDER BY b.created_at DESC, b.id DESC LIMIT ? OFFSET ?`,
    )
    .all(...params, PAGE_SIZE, (page - 1) * PAGE_SIZE) as Omit<Post, 'comments'>[];
  const comments = db.prepare(
    `SELECT c.id, c.user_id userId, u.name author, c.subject, c.content, c.created_at createdAt
     FROM comments c JOIN users u ON u.id = c.user_id WHERE c.blog_id = ?
     ORDER BY c.created_at, c.id`,
  );
  return {
    total,
    pages: Math.max(1, Math.ceil(total / PAGE_SIZE)),
    posts: posts.map((p) => ({ ...p, comments: comments.all(p.id) as Comment[] })),
  };
}

/** Posts of the authors the user subscribed to. */
export const feed = (db: Db, userId: number, page: number) =>
  withComments(
    db,
    'b.user_id IN (SELECT author_id FROM subscriptions WHERE user_id = ?)',
    [userId],
    page,
  );

export const ownPosts = (db: Db, userId: number, page: number) =>
  withComments(db, 'b.user_id = ?', [userId], page);

export function subscribe(db: Db, userId: number, authorId: number) {
  assert(userId !== authorId, 'You cannot subscribe to yourself.');
  assert(db.prepare('SELECT 1 FROM users WHERE id = ?').get(authorId), 'User not found.');
  db.prepare('INSERT OR IGNORE INTO subscriptions VALUES (?, ?)').run(userId, authorId);
}

export const unsubscribe = (db: Db, userId: number, authorId: number) =>
  void db
    .prepare('DELETE FROM subscriptions WHERE user_id = ? AND author_id = ?')
    .run(userId, authorId);

export const subscriptions = (db: Db, userId: number) =>
  db
    .prepare(
      `SELECT u.id, u.name FROM subscriptions s JOIN users u ON u.id = s.author_id
       WHERE s.user_id = ? ORDER BY u.name`,
    )
    .all(userId) as { id: number; name: string }[];

/** Nickname substring search (activated users); empty or `*` lists everyone. */
export function searchUsers(db: Db, userId: number, query: string) {
  const q = query.trim() === '*' ? '' : query.trim();
  return db
    .prepare(
      `SELECT u.id, u.name,
        (SELECT COUNT(*) FROM blogs WHERE user_id = u.id) posts,
        (SELECT COUNT(*) FROM comments WHERE user_id = u.id) comments,
        EXISTS (SELECT 1 FROM subscriptions WHERE user_id = ? AND author_id = u.id) subscribed
       FROM users u WHERE u.activation_key IS NULL AND instr(lower(u.name), lower(?)) > 0
       ORDER BY u.name`,
    )
    .all(userId, q) as {
    id: number;
    name: string;
    posts: number;
    comments: number;
    subscribed: number;
  }[];
}

export const isSubscribed = (db: Db, userId: number, authorId: number) =>
  !!db
    .prepare('SELECT 1 FROM subscriptions WHERE user_id = ? AND author_id = ?')
    .get(userId, authorId);
