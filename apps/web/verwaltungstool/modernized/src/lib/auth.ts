import { cookies } from 'next/headers';
import { redirect } from 'next/navigation';
import { getDb } from './db';
import { createSession, deleteSession, getSession } from './users';

const COOKIE = 'session';

export async function currentUser() {
  const token = (await cookies()).get(COOKIE)?.value;
  return token ? getSession(getDb(), token) : undefined;
}

export async function requireUser() {
  return (await currentUser()) ?? redirect('/login');
}

export async function startSession(userId: number) {
  (await cookies()).set(COOKIE, createSession(getDb(), userId), {
    httpOnly: true,
    sameSite: 'lax',
    secure: process.env.NODE_ENV === 'production',
    maxAge: 7 * 24 * 60 * 60,
    path: '/',
  });
}

export async function endSession() {
  const jar = await cookies();
  const token = jar.get(COOKIE)?.value;
  if (token) deleteSession(getDb(), token);
  jar.delete(COOKIE);
}
