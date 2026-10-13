import type { ReactNode } from 'react';
import Link from 'next/link';
import { currentUser } from '../lib/auth';
import { getDb } from '../lib/db';
import { getPlayer } from '../lib/player';
import { ACTIVITIES } from '../lib/game';
import { logout } from './actions';
import './globals.css';

export const metadata = { title: 'Stone Age Game' };
export const dynamic = 'force-dynamic';

export default async function RootLayout({ children }: { children: ReactNode }) {
  const user = await currentUser();
  const p = user && getPlayer(getDb(), user.id);
  return (
    <html lang="en">
      <body>
        <h1>Stone Age Game - Reloaded</h1>
        {user && p ? (
          <>
            <nav>
              <Link href="/scene/home">Cave</Link>
              <Link href="/status">Status</Link>
              <Link href="/crafting">Crafting</Link>
              <Link href="/inventory">Inventory</Link>
              <form action={logout}>
                <button>Log out ({user.nickname})</button>
              </form>
            </nav>
            <div className="layout">
              <aside>
                <h3>Activities</h3>
                <ul>
                  {ACTIVITIES.filter((a) => a.resource).map((a) => (
                    <li key={a.id}>
                      <Link href={`/work/${a.id}`}>{a.name}</Link>
                    </li>
                  ))}
                </ul>
                <h3>Resources</h3>
                <ul>
                  {Object.entries(p.resources).map(([k, v]) => (
                    <li key={k}>
                      {k.replace('_', ' ')}: {v}
                    </li>
                  ))}
                </ul>
                <p>Experience: {p.xp}</p>
              </aside>
              <main>{children}</main>
            </div>
          </>
        ) : (
          children
        )}
      </body>
    </html>
  );
}
