'use server';

import { redirect } from 'next/navigation';
import { endSession, requireUser, startSession } from '../lib/auth';
import * as club from '../lib/club';
import { getDb } from '../lib/db';

const str = (f: FormData, k: string) => String(f.get(k) ?? '');
const num = (f: FormData, k: string) => Number(str(f, k));
function go(path: string, error?: string): never {
  redirect(error ? `${path}?error=${encodeURIComponent(error)}` : path);
}
/** Runs a club operation as the signed-in user and returns to `path` (with the error, if any). */
async function run(path: string, op: (a: club.User) => string | undefined): Promise<never> {
  go(path, op(await requireUser()));
}

export async function register(f: FormData) {
  const password = str(f, 'password');
  if (password !== str(f, 'password2')) go('/', 'Passwords do not match');
  const r = club.registerClub(getDb(), {
    club: str(f, 'club'),
    first: str(f, 'first'),
    last: str(f, 'last'),
    email: str(f, 'email'),
    password,
  });
  if ('error' in r) go('/', r.error);
  await startSession(r.id);
  redirect('/club');
}

export async function login(f: FormData) {
  const r = club.authenticate(getDb(), str(f, 'email'), str(f, 'password'));
  if ('error' in r) go('/', r.error);
  await startSession(r.id);
  redirect('/club');
}

export async function logout() {
  await endSession();
  redirect('/');
}

export async function saveClub(f: FormData) {
  await run('/club', (a) =>
    club.updateClub(getDb(), a, {
      name: str(f, 'name'),
      short: str(f, 'short'),
      long: str(f, 'long'),
      history: str(f, 'history'),
    }),
  );
}

export async function addTeam(f: FormData) {
  await run('/club', (a) => club.createTeam(getDb(), a, num(f, 'type'), str(f, 'name')));
}

export async function removeTeam(f: FormData) {
  await run('/club', (a) => club.deleteTeam(getDb(), a, num(f, 'id')));
}

export async function addPerson(f: FormData) {
  await run('/club', (a) =>
    club.createPerson(getDb(), a, {
      role: str(f, 'role'),
      email: str(f, 'email'),
      password: str(f, 'password'),
      first: str(f, 'first'),
      last: str(f, 'last'),
      teamId: num(f, 'team'),
      birthdate: str(f, 'birthdate'),
    }),
  );
}

export async function removePerson(f: FormData) {
  await run('/club', (a) => club.deletePerson(getDb(), a, num(f, 'id')));
}

export async function coachTeam(f: FormData) {
  const team = num(f, 'team');
  await run(`/teams/${team}`, (a) =>
    club.assignCoach(getDb(), a, num(f, 'id'), team, str(f, 'on') === '1'),
  );
}

export async function playerTeam(f: FormData) {
  await run('/club', (a) => club.movePlayer(getDb(), a, num(f, 'id'), num(f, 'team')));
}

export async function lineup(f: FormData) {
  const team = num(f, 'team');
  const picks = [...f.keys()]
    .filter((k) => k.startsWith('slot-') && str(f, k) !== '')
    .map((k) => ({ playerId: Number(k.slice(5)), slot: num(f, k) }));
  await run(`/teams/${team}`, (a) => club.saveLineup(getDb(), a, team, str(f, 'formation'), picks));
}

export async function addEvent(f: FormData) {
  await run('/calendar', (a) =>
    club.createEvent(getDb(), a, {
      teamId: num(f, 'team'),
      type: str(f, 'type'),
      title: str(f, 'title'),
      location: str(f, 'location'),
      opponent: str(f, 'opponent'),
      start: str(f, 'start'),
      end: str(f, 'end'),
    }),
  );
}

export async function removeEvent(f: FormData) {
  await run('/calendar', (a) => club.deleteEvent(getDb(), a, num(f, 'id')));
}

export async function result(f: FormData) {
  const id = num(f, 'id');
  await run(`/events/${id}`, (a) =>
    club.setResult(getDb(), a, id, str(f, 'goals'), str(f, 'opponent')),
  );
}

export async function addTournamentMatch(f: FormData) {
  const id = num(f, 'id');
  await run(`/events/${id}`, (a) =>
    club.addTournamentMatch(getDb(), a, id, str(f, 'opponent'), str(f, 'start')),
  );
}

export async function tournamentResult(f: FormData) {
  const id = num(f, 'event');
  await run(`/events/${id}`, (a) =>
    club.setTournamentResult(getDb(), a, num(f, 'id'), str(f, 'goals'), str(f, 'opponent')),
  );
}

export async function removeTournamentMatch(f: FormData) {
  const id = num(f, 'event');
  await run(`/events/${id}`, (a) => club.deleteTournamentMatch(getDb(), a, num(f, 'id')));
}

export async function attendance(f: FormData) {
  const id = num(f, 'id');
  const values: Record<number, string> = {};
  for (const k of f.keys()) if (k.startsWith('att-')) values[Number(k.slice(4))] = str(f, k);
  await run(`/events/${id}`, (a) => club.saveAttendance(getDb(), a, id, values));
}

export async function password(f: FormData) {
  await run('/profile', (a) => club.changePassword(getDb(), a.id, str(f, 'old'), str(f, 'new')));
}

export async function email(f: FormData) {
  await run('/profile', (a) => club.changeEmail(getDb(), a.id, str(f, 'email')));
}

export async function deleteAccount(f: FormData) {
  const user = await requireUser();
  const e = club.deleteAccount(getDb(), user, str(f, 'password'));
  if (e) go('/profile', e);
  await endSession();
  redirect('/');
}
