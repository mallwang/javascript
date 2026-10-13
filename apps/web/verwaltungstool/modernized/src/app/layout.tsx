import type { ReactNode } from 'react';
import Link from 'next/link';
import { currentUser } from '../lib/auth';
import { logout } from './actions';
import './globals.css';

export const metadata = { title: 'Verwaltungstool' };

export default async function RootLayout({ children }: { children: ReactNode }) {
  const user = await currentUser();
  return (
    <html lang="en">
      <body>
        <nav>
          <img src="/img/logo.png" alt="Logo" height={32} />
          <Link href="/">Home</Link>
          {user ? (
            <>
              <Link href="/verwaltung">Management</Link>
              <Link href="/profile">Profile</Link>
              <form action={logout}>
                <button>Log out ({user.nickname})</button>
              </form>
            </>
          ) : (
            <>
              <Link href="/login">Log in</Link>
              <Link href="/register">Register</Link>
            </>
          )}
        </nav>
        {children}
      </body>
    </html>
  );
}
