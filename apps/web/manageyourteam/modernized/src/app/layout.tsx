import type { ReactNode } from 'react';
import Link from 'next/link';
import { currentUser } from '../lib/auth';
import { logout } from './actions';
import './globals.css';

export const metadata = { title: 'ManageYourTeam' };
export const dynamic = 'force-dynamic';

export default async function RootLayout({ children }: { children: ReactNode }) {
  const user = await currentUser();
  return (
    <html lang="en">
      <body>
        <h1>ManageYourTeam</h1>
        {user && (
          <nav>
            <Link href="/club">{user.clubName}</Link>
            <Link href="/calendar">Calendar</Link>
            <Link href="/profile">Profile</Link>
            <form action={logout}>
              <button>Log out ({user.name})</button>
            </form>
          </nav>
        )}
        {children}
      </body>
    </html>
  );
}
