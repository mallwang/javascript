import { expect, test } from 'vitest';
import { DAYS, isLocked, lockedMessage, shuffledDays } from '../src/calendar';

const dec = (d: number) => new Date(2026, 11, d);

test('doors open only in the required month from their day on', () => {
  expect(isLocked(5, dec(5), 11)).toBe(false);
  expect(isLocked(6, dec(5), 11)).toBe(true);
  expect(isLocked(1, new Date(2026, 10, 30), 11)).toBe(true);
});

test('locked message depends on the month', () => {
  expect(lockedMessage(new Date(2026, 9, 1), 11)).toContain('Dezember');
  expect(lockedMessage(dec(1), 11)).toBe('Nur noch ein paar Tage schlafen...');
});

test('shuffledDays is a permutation of 1..24', () => {
  const days = shuffledDays();
  expect(days).toHaveLength(DAYS);
  expect([...days].sort((a, b) => a - b)).toEqual(Array.from({ length: DAYS }, (_, i) => i + 1));
});
