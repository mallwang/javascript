import type { ReactNode } from 'react';
import Link from 'next/link';
import { currentUser } from '../lib/auth';
import { logout } from './actions';
import './globals.css';

export const metadata = { title: 'Unser Blog' };
export const dynamic = 'force-dynamic';

export default async function RootLayout({ children }: { children: ReactNode }) {
  const user = await currentUser();
  return (
    <html lang="en">
      <body>
        <h1>
          <Link href="/">Unser Blog</Link>
        </h1>
        {user && (
          <nav>
            <Link href="/home">Home</Link>
            <Link href="/blog">My blog</Link>
            <Link href="/users">Users</Link>
            <Link href="/profile">Profile</Link>
            <form action={logout}>
              <button>Log out ({user.name})</button>
            </form>
          </nav>
        )}
        {children}
        <footer>
          <Link href="/impressum">Impressum</Link>
        </footer>
      </body>
    </html>
  );
}
