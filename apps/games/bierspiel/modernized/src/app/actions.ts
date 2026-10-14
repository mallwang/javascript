'use server';

import { redirect } from 'next/navigation';
import * as account from '../lib/account';
import { endSession, requireUser, startSession } from '../lib/auth';
import * as company from '../lib/company';
import { getDb } from '../lib/db';
import { GameError } from '../lib/errors';
import { GOODS } from '../lib/goods';
import * as labour from '../lib/labour';
import * as market from '../lib/market';
import * as messages from '../lib/messages';

const str = (f: FormData, k: string) => String(f.get(k) ?? '');
const num = (f: FormData, k: string) => Number(f.get(k));
function go(path: string, msg?: string, key = 'error'): never {
  redirect(msg ? `${path}?${key}=${encodeURIComponent(msg)}` : path);
}

/** Runs a game action for the logged-in user; game errors become a flash message on `path`. */
async function act(
  path: string,
  fn: (companyId: number, userId: number) => void,
  ok?: string,
): Promise<never> {
  const u = await requireUser();
  try {
    fn(u.companyId, u.id);
  } catch (e) {
    if (e instanceof GameError) go(path, e.message);
    throw e;
  }
  return go(path, ok, 'ok');
}

async function enter(path: string, fn: () => number): Promise<never> {
  let id: number;
  try {
    id = fn();
  } catch (e) {
    if (e instanceof GameError) go(path, e.message);
    throw e;
  }
  await startSession(id);
  return redirect('/company');
}

export const register = async (f: FormData) =>
  enter('/', () =>
    account.register(getDb(), {
      nickname: str(f, 'nickname'),
      email: str(f, 'email'),
      password: str(f, 'password'),
      password2: str(f, 'password2'),
      company: str(f, 'company'),
      hq: num(f, 'hq'),
    }),
  );
export const login = async (f: FormData) =>
  enter('/', () => account.authenticate(getDb(), str(f, 'login'), str(f, 'password')));
export async function logout() {
  await endSession();
  redirect('/');
}

export const build = async (f: FormData) =>
  act(
    '/buildings',
    (c) =>
      void company.build(getDb(), c, {
        kind: str(f, 'kind'),
        provider: num(f, 'provider'),
        location: num(f, 'location'),
        district: num(f, 'district'),
        name: str(f, 'name'),
      }),
    'Construction started.',
  );
export const sell = async (f: FormData) =>
  act('/buildings', (c) => company.sell(getDb(), c, num(f, 'id')), 'Building sold.');
export const demolish = async (f: FormData) =>
  act('/buildings', (c) => company.demolish(getDb(), c, num(f, 'id')), 'Building demolished.');

export const rebuild = async (f: FormData) =>
  act(
    `/buildings/${num(f, 'id')}`,
    (c) =>
      company.rebuild(getDb(), c, num(f, 'id'), {
        style: str(f, 'style'),
        seatIndex: num(f, 'seatIndex'),
        wcM: num(f, 'wcM'),
        wcW: num(f, 'wcW'),
        pzg: str(f, 'pzg'),
        szg: str(f, 'szg'),
      }),
    'Rebuild done.',
  );

export const saveMenu = async (f: FormData) =>
  act(
    `/buildings/${num(f, 'id')}`,
    (c) => {
      const items = Object.fromEntries(
        GOODS.map((g) => [
          g.key,
          { offered: f.get(`o_${g.key}`) === 'on', price: num(f, `p_${g.key}`) },
        ]),
      );
      company.setMenu(getDb(), c, num(f, 'id'), items);
    },
    'Menu saved.',
  );

export const buyGoods = async (f: FormData) =>
  act(
    `/buildings/${num(f, 'id')}`,
    (c) => market.buyGoods(getDb(), c, num(f, 'id'), str(f, 'good'), num(f, 'liters')),
    'Purchase done.',
  );
export const buyOffer = async (f: FormData) =>
  act(
    '/market',
    (c) => market.buyOffer(getDb(), c, num(f, 'id'), num(f, 'amount')),
    'Purchase done.',
  );

export const borrow = async (f: FormData) =>
  act('/finances', (c) => company.borrow(getDb(), c, f.get('amount')), 'Loan granted.');
export const repay = async (f: FormData) =>
  act('/finances', (c) => company.repay(getDb(), c, f.get('amount')), 'Loan repaid.');

export const bid = async (f: FormData) =>
  act('/labour', (c) => labour.placeBid(getDb(), c, num(f, 'id'), f.get('amount')), 'Bid placed.');
export const hire = async (f: FormData) =>
  act('/labour', (c) => labour.hire(getDb(), c, num(f, 'id')), 'Hired.');
export const fire = async (f: FormData) =>
  act('/employees', (c) => labour.fire(getDb(), c, num(f, 'id')), 'Employee fired.');
export const setSalary = async (f: FormData) =>
  act(
    '/employees',
    (c) => labour.setSalary(getDb(), c, num(f, 'id'), f.get('salary')),
    'Salary changed.',
  );

export const sendMessage = async (f: FormData) =>
  act(
    '/messages',
    (_c, u) => messages.send(getDb(), u, str(f, 'to'), str(f, 'subject'), str(f, 'text')),
    'Message sent.',
  );
export const deleteMessage = async (f: FormData) =>
  act('/messages', (_c, u) => messages.remove(getDb(), u, num(f, 'id')));

export const changePassword = async (f: FormData) =>
  act(
    '/profile',
    (_c, u) => account.changePassword(getDb(), u, str(f, 'current'), str(f, 'password')),
    'Password changed.',
  );
export const changeEmail = async (f: FormData) =>
  act(
    '/profile',
    (_c, u) => account.changeEmail(getDb(), u, str(f, 'password'), str(f, 'email')),
    'E-mail changed.',
  );
export const renameCompany = async (f: FormData) =>
  act('/profile', (_c, u) => account.renameCompany(getDb(), u, str(f, 'name')), 'Company renamed.');
export const requestDeletion = async (f: FormData) =>
  act(
    '/profile',
    (_c, u) => account.requestDeletion(getDb(), u, str(f, 'password')),
    'Deletion requested. Log in again within 7 days to cancel.',
  );
