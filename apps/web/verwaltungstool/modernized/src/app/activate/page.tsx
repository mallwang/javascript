import Link from 'next/link';
import { getDb } from '../../lib/db';
import { activate } from '../../lib/users';

export default async function Activate({
  searchParams,
}: {
  searchParams: Promise<{ key?: string }>;
}) {
  const { key = '' } = await searchParams;
  return activate(getDb(), key) ? (
    <p className="ok">
      Account activated. <Link href="/login">Log in</Link>
    </p>
  ) : (
    <p className="error">Invalid or already used activation link.</p>
  );
}
