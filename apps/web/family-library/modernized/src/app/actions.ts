'use server';

import { redirect } from 'next/navigation';
import * as auth from '@/lib/auth';
import { createBook, deleteBook, updateBook, type BookResult } from '@/lib/books';
import { getDb } from '@/lib/db';
import { baseUrl, sendMail } from '@/lib/mail';
import { allow } from '@/lib/security';
import {
  clearSessionCookie,
  getSessionToken,
  requireAdmin,
  requireUser,
  setSessionCookie,
} from '@/lib/session';
import * as users from '@/lib/users';
import {
  normalizeEmail,
  normalizeIsbn,
  validateBook,
  validatePassword,
  validateRegistration,
} from '@/lib/validate';

const field = (fd: FormData, key: string) => String(fd.get(key) ?? '');
const err = (path: string, code: string): never => redirect(`${path}?e=${code}`);
const ok = (path: string, code: string): never => redirect(`${path}?ok=${code}`);

const sendVerification = (email: string, token: string) =>
  sendMail(
    email,
    'Family Library: E-Mail-Adresse bestätigen',
    `Bitte bestätige deine E-Mail-Adresse (24 Stunden gültig):\n${baseUrl()}/verify?token=${token}\n`,
  );

export async function registerAction(fd: FormData) {
  const input = {
    firstName: field(fd, 'firstName').trim(),
    lastName: field(fd, 'lastName').trim(),
    email: normalizeEmail(field(fd, 'email')),
    password: field(fd, 'password'),
  };
  const invalid = validateRegistration(input);
  if (invalid) err('/register', invalid);
  if (!allow(`register:${input.email}`, 5)) err('/register', 'too_many');
  const token = auth.register(getDb(), input);
  if (token) await sendVerification(input.email, token);
  // Same answer whether or not the address was already registered.
  ok('/login', 'registered');
}

export async function verifyAction(fd: FormData) {
  const valid = auth.verifyEmail(getDb(), field(fd, 'token'), process.env.ADMIN_EMAIL);
  if (valid) ok('/login', 'verified');
  err('/login', 'invalid_token');
}

export async function resendAction(fd: FormData) {
  const email = normalizeEmail(field(fd, 'email'));
  if (!allow(`resend:${email}`, 5)) err('/login', 'too_many');
  const token = auth.resendVerification(getDb(), email);
  if (token) await sendVerification(email, token);
  ok('/login', 'resent');
}

export async function loginAction(fd: FormData) {
  const email = normalizeEmail(field(fd, 'email'));
  if (!allow(`login:${email}`)) err('/login', 'too_many');
  const token = auth.login(getDb(), email, field(fd, 'password'));
  if (!token) err('/login', 'credentials');
  await setSessionCookie(token as string);
  redirect('/books');
}

export async function logoutAction() {
  const token = await getSessionToken();
  if (token) auth.logout(getDb(), token);
  await clearSessionCookie();
  redirect('/login');
}

export async function forgotAction(fd: FormData) {
  const email = normalizeEmail(field(fd, 'email'));
  if (!allow(`forgot:${email}`, 5)) err('/forgot-password', 'too_many');
  const token = auth.forgotPassword(getDb(), email);
  if (token) {
    await sendMail(
      email,
      'Family Library: Passwort zurücksetzen',
      `Passwort zurücksetzen (1 Stunde gültig):\n${baseUrl()}/reset-password?token=${token}\n\nFalls du das nicht angefordert hast, ignoriere diese E-Mail.\n`,
    );
  }
  ok('/forgot-password', 'forgot');
}

export async function resetAction(fd: FormData) {
  const invalid = validatePassword(field(fd, 'password'));
  if (invalid) err('/login', invalid);
  if (!auth.resetPassword(getDb(), field(fd, 'token'), field(fd, 'password'))) {
    err('/login', 'invalid_token');
  }
  ok('/login', 'reset');
}

function bookInput(fd: FormData) {
  return {
    title: field(fd, 'title').trim(),
    author: field(fd, 'author').trim(),
    isbn: normalizeIsbn(field(fd, 'isbn')),
    year: Number(field(fd, 'year')),
    language: field(fd, 'language').trim(),
  };
}

const bookDone = (result: BookResult, path: string, success: string): never =>
  result === 'ok' ? ok(path, success) : err(path, result);

export async function createBookAction(fd: FormData) {
  const user = await requireUser();
  const input = bookInput(fd);
  const invalid = validateBook(input);
  if (invalid) err('/books', invalid);
  bookDone(createBook(getDb(), user, input), '/books', 'saved');
}

export async function updateBookAction(fd: FormData) {
  const user = await requireUser();
  const id = Number(field(fd, 'id'));
  const input = bookInput(fd);
  const invalid = validateBook(input);
  if (invalid) err(`/books/${id}`, invalid);
  bookDone(updateBook(getDb(), user, id, input), `/books/${id}`, 'saved');
}

export async function deleteBookAction(fd: FormData) {
  const user = await requireUser();
  const id = Number(field(fd, 'id'));
  bookDone(deleteBook(getDb(), user, id), '/books', 'deleted');
}

export async function setRoleAction(fd: FormData) {
  const admin = await requireAdmin();
  const role = field(fd, 'role') === 'admin' ? 'admin' : 'user';
  const done = users.setRole(getDb(), admin, Number(field(fd, 'id')), role);
  if (done) ok('/admin', 'saved');
  err('/admin', 'forbidden');
}

export async function deleteUserAction(fd: FormData) {
  const admin = await requireAdmin();
  if (users.deleteUser(getDb(), admin, Number(field(fd, 'id')))) ok('/admin', 'deleted');
  err('/admin', 'forbidden');
}
