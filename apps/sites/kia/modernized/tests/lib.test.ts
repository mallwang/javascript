import { expect, test } from 'vitest';
import { filterPhotos, neighbour, photos } from '../src/lib';

test('gallery has 27 photos, 12 outside and 15 inside', () => {
  expect(photos).toHaveLength(27);
  expect(filterPhotos(photos, 'aussen')).toHaveLength(12);
  expect(filterPhotos(photos, 'innen')).toHaveLength(15);
  expect(filterPhotos(photos, 'all')).toBe(photos);
});

test('photo ids are unique', () => {
  expect(new Set(photos.map((p) => p.id)).size).toBe(photos.length);
});

test('neighbour wraps around', () => {
  expect(neighbour(0, -1, 5)).toBe(4);
  expect(neighbour(4, 1, 5)).toBe(0);
  expect(neighbour(2, 1, 5)).toBe(3);
});
