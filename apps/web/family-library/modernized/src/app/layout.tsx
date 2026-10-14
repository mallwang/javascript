import type { ReactNode } from 'react';
import Link from 'next/link';
import { currentUser } from '@/lib/session';
import { logoutAction } from './actions';
import './globals.css';

export const metadata = { title: 'Family Library' };
export const dynamic = 'force-dynamic';

export default async function RootLayout({ children }: { children: ReactNode }) {
  const user = await currentUser();
  return (
    <html lang="de">
      <body>
        <nav>
          <strong>Family Library</strong>
          {user ? (
            <>
              <Link href="/books">Bücherregal</Link>
              {user.role === 'admin' && <Link href="/admin">Administration</Link>}
              <Link href="/help">Hilfe</Link>
              <form action={logoutAction}>
                <button>
                  Abmelden ({user.firstName} {user.lastName})
                </button>
              </form>
            </>
          ) : (
            <>
              <Link href="/login">Anmelden</Link>
              <Link href="/register">Registrieren</Link>
              <Link href="/help">Hilfe</Link>
            </>
          )}
        </nav>
        {children}
      </body>
    </html>
  );
}
