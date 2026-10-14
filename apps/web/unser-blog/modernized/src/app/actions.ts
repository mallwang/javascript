'use server';

import { redirect } from 'next/navigation';
import * as account from '../lib/account';
import { endSession, requireUser, startSession } from '../lib/auth';
import * as blog from '../lib/blog';
import { getDb } from '../lib/db';
import { AppError } from '../lib/errors';
import { savePhoto } from '../lib/photo';

const str = (f: FormData, k: string) => String(f.get(k) ?? '');
const num = (f: FormData, k: string) => Number(f.get(k));

function go(path: string, msg?: string, key = 'error'): never {
  const sep = path.includes('?') ? '&' : '?';
  redirect(msg ? `${path}${sep}${key}=${encodeURIComponent(msg)}` : path);
}

/** Only local app paths are accepted as return target (no open redirect). */
const back = (f: FormData) => {
  const b = str(f, 'back');
  return /^\/(home|blog|profile|users)([/?][\w=&%.*+-]*)?$/.test(b) ? b : '/home';
};

/** Runs `fn`; an AppError becomes a flash message on `path`. */
function attempt<T>(path: string, fn: () => T): T {
  try {
    return fn();
  } catch (e) {
    if (e instanceof AppError) go(path, e.message);
    throw e;
  }
}

/** There is no mail delivery: activation and reset links are written to the server log. */
const link = (what: string, path: string) =>
  console.log(`[${what}] ${process.env.APP_URL ?? 'http://localhost:3000'}${path}`);

export async function register(f: FormData) {
  const { key } = attempt('/', () =>
    account.register(getDb(), {
      name: str(f, 'name'),
      email: str(f, 'email'),
      password: str(f, 'password'),
      password2: str(f, 'password2'),
    }),
  );
  link('activation', `/activate?key=${key}`);
  go('/', 'Registered. The activation link was written to the server log.', 'ok');
}

export async function login(f: FormData) {
  const id = attempt('/', () => account.authenticate(getDb(), str(f, 'login'), str(f, 'password')));
  await startSession(id);
  redirect('/home');
}

export async function logout() {
  await endSession();
  redirect('/');
}

export async function forgotPassword(f: FormData) {
  const t = attempt('/', () => account.requestReset(getDb(), str(f, 'email')));
  if (t) link('password reset', `/reset?token=${t}`);
  go('/', 'If the address is registered, a reset link was written to the server log.', 'ok');
}

export async function resetPassword(f: FormData) {
  const token = str(f, 'token');
  attempt(`/reset?token=${encodeURIComponent(token)}`, () => {
    assertSame(f);
    account.resetPassword(getDb(), token, str(f, 'password'));
  });
  go('/', 'Password changed. You can log in now.', 'ok');
}

function assertSame(f: FormData) {
  if (str(f, 'password') !== str(f, 'password2')) throw new AppError('Passwords do not match.');
}

async function act(path: string, fn: (userId: number) => void, ok: string): Promise<never> {
  const u = await requireUser();
  attempt(path, () => fn(u.id));
  return go(path, ok, 'ok');
}

export const newPost = async (f: FormData) =>
  act(
    '/blog',
    (u) => blog.addPost(getDb(), u, str(f, 'subject'), str(f, 'content')),
    'Post saved.',
  );

export const deletePost = async (f: FormData) =>
  act(back(f), (u) => blog.deletePost(getDb(), u, num(f, 'id')), 'Post deleted.');

export const newComment = async (f: FormData) =>
  act(
    back(f),
    (u) => blog.addComment(getDb(), u, num(f, 'blog'), str(f, 'subject'), str(f, 'content')),
    'Comment saved.',
  );

export const deleteComment = async (f: FormData) =>
  act(back(f), (u) => blog.deleteComment(getDb(), u, num(f, 'id')), 'Comment deleted.');

export const subscribe = async (f: FormData) =>
  act(back(f), (u) => blog.subscribe(getDb(), u, num(f, 'author')), 'Subscribed.');

export const unsubscribe = async (f: FormData) =>
  act(back(f), (u) => blog.unsubscribe(getDb(), u, num(f, 'author')), 'Subscription stopped.');

export const saveProfile = async (f: FormData) =>
  act(
    '/profile',
    (u) =>
      account.updateProfile(getDb(), u, {
        firstName: str(f, 'firstName').trim(),
        lastName: str(f, 'lastName').trim(),
        birthDate: str(f, 'birthDate'),
        place: str(f, 'place').trim(),
        hobbies: str(f, 'hobbies').trim(),
        description: str(f, 'description').trim(),
      }),
    'Profile saved.',
  );

export async function uploadPhoto(f: FormData) {
  const file = f.get('photo');
  const u = await requireUser();
  if (!(file instanceof File) || file.size === 0) go('/profile', 'Choose a photo.');
  const data = Buffer.from(await file.arrayBuffer());
  attempt('/profile', () => savePhoto(u.id, data));
  go('/profile', 'Photo saved.', 'ok');
}

export async function changePassword(f: FormData) {
  const u = await requireUser();
  attempt('/profile', () => {
    assertSame(f);
    account.changePassword(getDb(), u.id, str(f, 'current'), str(f, 'password'));
  });
  await startSession(u.id); // changing the password ended all sessions
  go('/profile', 'Password changed.', 'ok');
}

export const changeEmail = async (f: FormData) =>
  act(
    '/profile',
    (u) => account.changeEmail(getDb(), u, str(f, 'password'), str(f, 'email')),
    'E-mail changed.',
  );
