import { mkdtempSync } from 'node:fs';
import { tmpdir } from 'node:os';
import { join } from 'node:path';
import { beforeEach, describe, expect, it } from 'vitest';
import * as account from '../src/lib/account';
import * as blog from '../src/lib/blog';
import { openDb, type Db } from '../src/lib/db';
import { jpegSize, readPhoto, savePhoto } from '../src/lib/photo';

let db: Db;
beforeEach(() => {
  db = openDb(':memory:');
});

const user = (name: string) => {
  const { id, key } = account.register(db, {
    name,
    email: `${name}@example.com`,
    password: 'password1',
    password2: 'password1',
  });
  account.activate(db, key);
  return id;
};

describe('accounts', () => {
  it('requires activation before login and accepts nickname or e-mail', () => {
    const { id, key } = account.register(db, {
      name: 'anna',
      email: 'Anna@Example.com',
      password: 'password1',
      password2: 'password1',
    });
    expect(() => account.authenticate(db, 'anna', 'password1')).toThrow();
    account.activate(db, key);
    expect(account.authenticate(db, 'anna', 'password1')).toBe(id);
    expect(account.authenticate(db, 'anna@example.com', 'password1')).toBe(id);
    expect(() => account.authenticate(db, 'anna', 'wrong-pass')).toThrow();
    expect(() => account.activate(db, key)).toThrow();
  });

  it('rejects duplicates, weak and mismatching passwords', () => {
    user('anna');
    const base = {
      name: 'bob',
      email: 'bob@example.com',
      password: 'password1',
      password2: 'password1',
    };
    expect(() => account.register(db, { ...base, name: 'ANNA' })).toThrow();
    expect(() => account.register(db, { ...base, email: 'anna@example.com' })).toThrow();
    expect(() =>
      account.register(db, { ...base, password: 'short', password2: 'short' }),
    ).toThrow();
    expect(() => account.register(db, { ...base, password2: 'other-pass' })).toThrow();
  });

  it('sessions work and end on password change', () => {
    const id = user('anna');
    const t = account.createSession(db, id);
    expect(account.getSession(db, t)).toEqual({ id, name: 'anna' });
    account.changePassword(db, id, 'password1', 'password2');
    expect(account.getSession(db, t)).toBeUndefined();
    expect(() => account.changePassword(db, id, 'wrong-pass', 'password3')).toThrow();
    expect(account.authenticate(db, 'anna', 'password2')).toBe(id);
  });

  it('resets the password with a one-time token that expires', () => {
    const id = user('anna');
    expect(account.requestReset(db, 'nobody@example.com')).toBeUndefined();
    const t = account.requestReset(db, 'anna@example.com', 0)!;
    expect(() => account.resetPassword(db, t, 'password9', 2 * 3600_000)).toThrow();
    const t2 = account.requestReset(db, 'anna@example.com')!;
    account.resetPassword(db, t2, 'password9');
    expect(account.authenticate(db, 'anna', 'password9')).toBe(id);
    expect(() => account.resetPassword(db, t2, 'password8')).toThrow();
  });

  it('validates the profile', () => {
    const id = user('anna');
    const p = {
      firstName: 'Anna',
      lastName: 'A',
      birthDate: '2000-01-31',
      place: '',
      hobbies: '',
      description: '',
    };
    account.updateProfile(db, id, p);
    expect(account.getProfile(db, id)).toMatchObject(p);
    expect(() => account.updateProfile(db, id, { ...p, birthDate: '31.01.2000' })).toThrow();
  });
});

describe('blog', () => {
  it('stores text verbatim and paginates the feed of subscriptions', () => {
    const a = user('anna');
    const b = user('bob');
    for (let i = 1; i <= 7; i++) blog.addPost(db, a, `post ${i}`, '<b>x</b>\nline', i);
    blog.addPost(db, b, 'other', 'not subscribed', 100);
    expect(blog.feed(db, b, 1).total).toBe(0);
    blog.subscribe(db, b, a);
    const p1 = blog.feed(db, b, 1);
    expect(p1).toMatchObject({ total: 7, pages: 2 });
    expect(p1.posts.map((p) => p.subject)).toEqual([
      'post 7',
      'post 6',
      'post 5',
      'post 4',
      'post 3',
    ]);
    expect(p1.posts[0].content).toBe('<b>x</b>\nline');
    expect(blog.feed(db, b, 2).posts).toHaveLength(2);
    expect(blog.ownPosts(db, b, 1).posts.map((p) => p.subject)).toEqual(['other']);
  });

  it('lets only the owner delete posts and cascades to comments', () => {
    const a = user('anna');
    const b = user('bob');
    blog.addPost(db, a, 's', 'c');
    const post = blog.ownPosts(db, a, 1).posts[0];
    expect(() => blog.deletePost(db, b, post.id)).toThrow();
    blog.addComment(db, b, post.id, 'Re: s', 'hi');
    blog.deletePost(db, a, post.id);
    expect(db.prepare('SELECT COUNT(*) n FROM comments').get()).toEqual({ n: 0 });
  });

  it('lets comment author and post owner delete comments, nobody else', () => {
    const [a, b, c] = ['anna', 'bob', 'cleo'].map(user);
    blog.addPost(db, a, 's', 'c');
    const post = blog.ownPosts(db, a, 1).posts[0];
    expect(() => blog.addComment(db, b, 999, 're', 'x')).toThrow();
    blog.addComment(db, b, post.id, 're', 'one');
    blog.addComment(db, b, post.id, 're', 'two');
    const [c1, c2] = blog.ownPosts(db, a, 1).posts[0].comments;
    expect(() => blog.deleteComment(db, c, c1.id)).toThrow();
    blog.deleteComment(db, b, c1.id);
    blog.deleteComment(db, a, c2.id);
    expect(blog.ownPosts(db, a, 1).posts[0].comments).toHaveLength(0);
  });

  it('unsubscribe only affects the own subscription', () => {
    const [a, b, c] = ['anna', 'bob', 'cleo'].map(user);
    blog.subscribe(db, b, a);
    blog.subscribe(db, c, a);
    expect(() => blog.subscribe(db, a, a)).toThrow();
    blog.unsubscribe(db, b, a);
    expect(blog.isSubscribed(db, b, a)).toBe(false);
    expect(blog.isSubscribed(db, c, a)).toBe(true);
    expect(blog.subscriptions(db, c)).toEqual([{ id: a, name: 'anna' }]);
  });

  it('searches by nickname substring with counts; * lists everyone', () => {
    const [a, b] = ['anna', 'hannah'].map(user);
    blog.addPost(db, a, 's', 'c');
    blog.subscribe(db, b, a);
    expect(blog.searchUsers(db, b, 'ann').map((u) => u.name)).toEqual(['anna', 'hannah']);
    expect(blog.searchUsers(db, b, 'HAN').map((u) => u.name)).toEqual(['hannah']);
    expect(blog.searchUsers(db, b, '*')).toHaveLength(2);
    expect(blog.searchUsers(db, b, "'; DROP TABLE users;--")).toEqual([]);
    expect(blog.searchUsers(db, b, 'anna')[0]).toMatchObject({ posts: 1, subscribed: 1 });
  });
});

describe('photo', () => {
  const jpeg = (w: number, h: number, size = 20) => {
    const b = Buffer.alloc(size);
    Buffer.from([0xff, 0xd8, 0xff, 0xc0, 0, 11, 8, h >> 8, h & 255, w >> 8, w & 255]).copy(b);
    return b;
  };

  it('reads the JPEG size and rejects invalid uploads', () => {
    process.env.PHOTO_DIR = mkdtempSync(join(tmpdir(), 'photo-'));
    expect(jpegSize(jpeg(80, 60))).toEqual({ width: 80, height: 60 });
    expect(jpegSize(Buffer.from('GIF89a'))).toBeUndefined();
    expect(() => savePhoto(1, jpeg(101, 50))).toThrow();
    expect(() => savePhoto(1, jpeg(50, 50, 100 * 1024 + 1))).toThrow();
    expect(() => savePhoto(1, Buffer.from('not a jpeg'))).toThrow();
    expect(readPhoto(1)).toBeUndefined();
    savePhoto(1, jpeg(100, 100));
    expect(readPhoto(1)).toHaveLength(20);
  });
});
