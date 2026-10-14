'use server';

import { redirect } from 'next/navigation';
import { currentUser, endSession, startSession } from '../lib/auth';
import { getDb } from '../lib/db';
import * as site from '../lib/site';

const str = (f: FormData, k: string) => String(f.get(k) ?? '');
function go(path: string, msg?: string, key = 'error'): never {
  redirect(msg ? `${path}?${key}=${encodeURIComponent(msg)}` : path);
}

export async function register(f: FormData) {
  const r = site.register(getDb(), {
    nickname: str(f, 'nickname'),
    email: str(f, 'email'),
    password: str(f, 'password'),
  });
  if ('error' in r) go('/login', r.error);
  await startSession(r.id);
  redirect('/');
}

export async function login(f: FormData) {
  const r = site.authenticate(getDb(), str(f, 'login'), str(f, 'password'));
  if ('error' in r) go('/login', r.error);
  await startSession(r.id);
  redirect('/');
}

export async function logout() {
  await endSession();
  redirect('/');
}

export async function submitPlace(f: FormData) {
  const error = site.submitPlace(
    getDb(),
    {
      category: str(f, 'category'),
      state: str(f, 'state'),
      city: str(f, 'city'),
      name: str(f, 'name'),
      description: str(f, 'description'),
      lat: Number(str(f, 'lat')),
      lng: Number(str(f, 'lng')),
    },
    (await currentUser())?.id,
  );
  go(
    '/places/new',
    error ?? 'Thanks! The place will appear once it has been approved.',
    error ? 'error' : 'ok',
  );
}

export async function moderate(f: FormData) {
  go(
    '/admin',
    site.moderate(getDb(), await currentUser(), Number(str(f, 'id')), str(f, 'approve') === '1'),
  );
}

export async function sendSupport(f: FormData) {
  const error = site.addSupport(getDb(), {
    subject: str(f, 'subject'),
    email: str(f, 'email'),
    text: str(f, 'text'),
  });
  go('/support', error ?? 'Thanks, your message was saved.', error ? 'error' : 'ok');
}
