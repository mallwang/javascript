import { cookies } from 'next/headers';
import { redirect } from 'next/navigation';
import { SESSION_TTL, sessionUser, type SessionUser } from './auth';
import { getDb } from './db';

const COOKIE = 'session';

export async function setSessionCookie(token: string) {
  (await cookies()).set(COOKIE, token, {
    httpOnly: true,
    sameSite: 'lax',
    secure: process.env.NODE_ENV === 'production',
    path: '/',
    maxAge: SESSION_TTL / 1000,
  });
}

export const getSessionToken = async () => (await cookies()).get(COOKIE)?.value;
export const clearSessionCookie = async () => (await cookies()).delete(COOKIE);

export async function currentUser(): Promise<SessionUser | null> {
  const token = await getSessionToken();
  return token ? sessionUser(getDb(), token) : null;
}

export async function requireUser(): Promise<SessionUser> {
  return (await currentUser()) ?? redirect('/login');
}

export async function requireAdmin(): Promise<SessionUser> {
  const user = await requireUser();
  return user.role === 'admin' ? user : redirect('/books?e=forbidden');
}
