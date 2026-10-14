import type { ReactNode } from 'react';
import Link from 'next/link';
import { currentUser } from '../lib/auth';
import { logout } from './actions';
import './globals.css';

export const metadata = { title: 'Schankimperium' };
export const dynamic = 'force-dynamic';

export default async function RootLayout({ children }: { children: ReactNode }) {
  const user = await currentUser();
  return (
    <html lang="en">
      <body>
        <h1>
          <Link href="/">Schankimperium</Link>
        </h1>
        <nav>
          {user && (
            <>
              <Link href="/company">Company</Link>
              <Link href="/buildings">Buildings</Link>
              <Link href="/finances">Finances</Link>
              <Link href="/market">Market</Link>
              <Link href="/labour">Labour market</Link>
              <Link href="/employees">Employees</Link>
              <Link href="/messages">Messages</Link>
              <Link href="/profile">Profile</Link>
            </>
          )}
          <Link href="/players">Players</Link>
          {user && (
            <form action={logout}>
              <button>Log out ({user.nickname})</button>
            </form>
          )}
        </nav>
        {children}
      </body>
    </html>
  );
}
