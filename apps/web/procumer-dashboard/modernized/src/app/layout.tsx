import type { ReactNode } from 'react';
import Link from 'next/link';
import './globals.css';

export const metadata = { title: 'Procumer Dashboard' };
export const dynamic = 'force-dynamic';

export default function RootLayout({ children }: { children: ReactNode }) {
  return (
    <html lang="en">
      <body>
        <h1>
          <Link href="/">Procumer Dashboard</Link>
        </h1>
        {children}
      </body>
    </html>
  );
}
