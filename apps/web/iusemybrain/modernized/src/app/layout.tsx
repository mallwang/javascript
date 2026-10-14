import type { ReactNode } from 'react';
import Link from 'next/link';
import { currentUser } from '../lib/auth';
import { logout } from './actions';
import './globals.css';

export const metadata = { title: 'I use my brain' };
export const dynamic = 'force-dynamic';

export default async function RootLayout({ children }: { children: ReactNode }) {
  const user = await currentUser();
  return (
    <html lang="en">
      <body>
        <h1>
          <Link href="/">I use my brain</Link>
        </h1>
        <nav>
          <Link href="/places">Places</Link>
          <Link href="/library">Library</Link>
          <Link href="/memory">Memory</Link>
          <Link href="/download">Download</Link>
          <Link href="/support">Support</Link>
          <Link href="/impressum">Imprint</Link>
          {user?.isAdmin && <Link href="/admin">Admin</Link>}
          {user ? (
            <form action={logout}>
              <button>Log out ({user.nickname})</button>
            </form>
          ) : (
            <Link href="/login" className="right">
              Log in
            </Link>
          )}
        </nav>
        {children}
      </body>
    </html>
  );
}
