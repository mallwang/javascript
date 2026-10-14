import { cookies } from 'next/headers';
import { redirect } from 'next/navigation';
import { createSession, deleteSession, getSession, type User } from './account';
import { getDb } from './db';

const COOKIE = 'session';

export async function currentUser(): Promise<User | undefined> {
  const token = (await cookies()).get(COOKIE)?.value;
  return token ? getSession(getDb(), token) : undefined;
}

export async function requireUser(): Promise<User> {
  const user = await currentUser();
  if (!user) redirect('/?error=Please+log+in.');
  return user;
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
