import { mkdirSync, readFileSync, writeFileSync } from 'node:fs';
import { join } from 'node:path';
import { assert } from './errors';

export const MAX_BYTES = 100 * 1024;
export const MAX_SIDE = 100;

const dir = () => process.env.PHOTO_DIR ?? 'data/photos';

/** Reads width and height from the first SOF segment; undefined if not a JPEG. */
export function jpegSize(b: Buffer): { width: number; height: number } | undefined {
  if (b[0] !== 0xff || b[1] !== 0xd8) return undefined;
  try {
    for (let i = 2; b[i] === 0xff;) {
      const m = b[i + 1];
      if (m >= 0xc0 && m <= 0xcf && m !== 0xc4 && m !== 0xc8 && m !== 0xcc)
        return { height: b.readUInt16BE(i + 5), width: b.readUInt16BE(i + 7) };
      i += 2 + b.readUInt16BE(i + 2);
    }
  } catch {
    // truncated file
  }
  return undefined;
}

export function savePhoto(userId: number, data: Buffer) {
  assert(data.length > 0 && data.length <= MAX_BYTES, 'The photo must be at most 100 KB.');
  const s = jpegSize(data);
  assert(s, 'The photo must be a JPEG file.');
  assert(
    s.width <= MAX_SIDE && s.height <= MAX_SIDE,
    `The photo must be at most ${MAX_SIDE}x${MAX_SIDE} pixels.`,
  );
  mkdirSync(dir(), { recursive: true });
  writeFileSync(join(dir(), `${userId}.jpg`), data);
}

export function readPhoto(userId: number): Buffer | undefined {
  try {
    return readFileSync(join(dir(), `${userId}.jpg`));
  } catch {
    return undefined;
  }
}
