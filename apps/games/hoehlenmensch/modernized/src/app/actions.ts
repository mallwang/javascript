'use server';

import { redirect } from 'next/navigation';
import { endSession, requireUser, startSession } from '../lib/auth';
import { getDb } from '../lib/db';
import { authenticate, register as createUser, start } from '../lib/player';

const str = (f: FormData, k: string) => String(f.get(k) ?? '');
function go(path: string, error: string): never {
  redirect(`${path}?error=${encodeURIComponent(error)}`);
}

export async function register(f: FormData) {
  const password = str(f, 'password');
  if (password !== str(f, 'password2')) go('/', 'Passwords do not match');
  const r = createUser(getDb(), str(f, 'nickname'), password);
  if ('error' in r) go('/', r.error);
  await startSession(r.id);
  redirect('/scene/home');
}

export async function login(f: FormData) {
  const r = authenticate(getDb(), str(f, 'nickname'), str(f, 'password'));
  if ('error' in r) go('/', r.error);
  await startSession(r.id);
  redirect('/scene/home');
}

export async function logout() {
  await endSession();
  redirect('/');
}

export async function work(f: FormData) {
  const user = await requireUser();
  const error = start(getDb(), user.id, Number(str(f, 'id')), Number(str(f, 'duration')));
  if (error) go(`/work/${str(f, 'id')}`, error);
  redirect('/status');
}
