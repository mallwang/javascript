import { timingSafeEqual } from 'node:crypto';
import { getDb } from '../../../lib/db';
import { tick } from '../../../lib/settle';

export const dynamic = 'force-dynamic';

/** POST /api/tick with header `Authorization: Bearer <TICK_SECRET>` (call it from cron). */
export async function POST(req: Request) {
  const secret = process.env.TICK_SECRET;
  const given = (req.headers.get('authorization') ?? '').replace(/^Bearer /, '');
  const ok =
    !!secret &&
    secret !== 'change-me' &&
    given.length === secret.length &&
    timingSafeEqual(Buffer.from(given), Buffer.from(secret));
  if (!ok) return new Response('Forbidden', { status: 403 });
  tick(getDb());
  return new Response('ok');
}
