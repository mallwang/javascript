import { ERRORS, NOTICES } from '@/lib/messages';

export type Notices = Promise<{ e?: string; ok?: string }>;

export async function Notice({ searchParams }: { searchParams: Notices }) {
  const { e, ok } = await searchParams;
  const error = e && Object.hasOwn(ERRORS, e) ? ERRORS[e] : undefined;
  const notice = ok && Object.hasOwn(NOTICES, ok) ? NOTICES[ok] : undefined;
  return (
    <>
      {error && <p className="error">{error}</p>}
      {notice && <p className="ok">{notice}</p>}
    </>
  );
}
