'use server';

import { redirect } from 'next/navigation';
import { endSession, requireUser, startSession } from '../lib/auth';
import { getDb } from '../lib/db';
import { sendMail } from '../lib/mail';
import {
  authenticate,
  changeEmail,
  changePassword,
  createUser,
  requestDeletion,
} from '../lib/users';

const str = (f: FormData, k: string) => String(f.get(k) ?? '');
function go(path: string, kind: 'error' | 'ok', msg: string): never {
  redirect(`${path}?${kind}=${encodeURIComponent(msg)}`);
}
const baseUrl = () => process.env.APP_URL ?? 'http://localhost:3000';

export async function register(f: FormData) {
  const password = str(f, 'password');
  if (password !== str(f, 'password2')) go('/register', 'error', 'Passwords do not match');
  const email = str(f, 'email');
  const r = createUser(getDb(), { nickname: str(f, 'nickname'), email, password });
  if ('error' in r) go('/register', 'error', r.error);
  await sendMail(
    email,
    'Activate your account',
    `Welcome! Activate your account: ${baseUrl()}/activate?key=${r.key}`,
  );
  go('/login', 'ok', 'Registered. Check your e-mail for the activation link.');
}

export async function login(f: FormData) {
  const r = authenticate(getDb(), str(f, 'login'), str(f, 'password'));
  if ('error' in r) go('/login', 'error', r.error);
  await startSession(r.user.id);
  redirect('/verwaltung');
}

export async function logout() {
  await endSession();
  redirect('/');
}

export async function updatePassword(f: FormData) {
  const user = await requireUser();
  const next = str(f, 'new');
  if (next !== str(f, 'new2')) go('/profile', 'error', 'Passwords do not match');
  const error = changePassword(getDb(), user.id, str(f, 'current'), next);
  if (error) go('/profile', 'error', error);
  await startSession(user.id); // all sessions were invalidated
  await sendMail(user.email, 'Password changed', 'Your password has been changed.');
  go('/profile', 'ok', 'Password changed');
}

export async function updateEmail(f: FormData) {
  const user = await requireUser();
  const email = str(f, 'email');
  const error = changeEmail(getDb(), user.id, str(f, 'password'), email);
  if (error) go('/profile', 'error', error);
  await sendMail(user.email, 'E-mail changed', `Your e-mail address is now ${email}.`);
  await sendMail(email, 'E-mail changed', 'This address is now linked to your account.');
  go('/profile', 'ok', 'E-mail address changed');
}

export async function deleteAccount() {
  const user = await requireUser();
  requestDeletion(getDb(), user.id);
  await sendMail(
    user.email,
    'Account deletion requested',
    'Your account will be deleted in 3 days. Log in again to cancel.',
  );
  await endSession();
  go('/login', 'ok', 'Deletion requested. Log in within 3 days to cancel.');
}
