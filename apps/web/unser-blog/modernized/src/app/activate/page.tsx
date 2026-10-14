import Link from 'next/link';
import { activate } from '../../lib/account';
import { getDb } from '../../lib/db';
import { AppError } from '../../lib/errors';

export default async function Activate({
  searchParams,
}: {
  searchParams: Promise<{ key?: string }>;
}) {
  const { key = '' } = await searchParams;
  let error: string | undefined;
  try {
    activate(getDb(), key);
  } catch (e) {
    if (!(e instanceof AppError)) throw e;
    error = e.message;
  }
  return error ? (
    <p className="error">{error}</p>
  ) : (
    <p className="ok">
      Account activated. <Link href="/">Log in</Link>
    </p>
  );
}
