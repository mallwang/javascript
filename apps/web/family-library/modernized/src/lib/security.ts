import { createHash, randomBytes, scryptSync, timingSafeEqual } from 'node:crypto';

export const sha256 = (s: string) => createHash('sha256').update(s).digest('hex');

/** 256-bit random URL-safe token; store only `sha256(token)`. */
export const newToken = () => randomBytes(32).toString('base64url');

export function hashPassword(password: string): string {
  const salt = randomBytes(16);
  return `${salt.toString('hex')}:${scryptSync(password, salt, 64).toString('hex')}`;
}

export function verifyPassword(password: string, stored: string): boolean {
  const [salt, hash] = stored.split(':');
  const actual = scryptSync(password, Buffer.from(salt, 'hex'), 64);
  return timingSafeEqual(actual, Buffer.from(hash, 'hex'));
}

// ponytail: in-memory, per process, keys never pruned; use a shared store if run on several instances.
const hits = new Map<string, { count: number; reset: number }>();

/** Returns false once `key` exceeded `max` calls within `windowMs`. */
export function allow(key: string, max = 10, windowMs = 15 * 60_000, now = Date.now()): boolean {
  const h = hits.get(key);
  if (!h || h.reset <= now) {
    hits.set(key, { count: 1, reset: now + windowMs });
    return true;
  }
  return ++h.count <= max;
}
