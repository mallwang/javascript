import { expect, test } from 'vitest';
import { isEmail, message } from '../src/email';

test.each(['a@b.de', 'max.mustermann+tag@mail.example.com', 'x_y-z@sub-1.example.org'])(
  'accepts %s',
  (s) => expect(isEmail(s)).toBe(true),
);

// The legacy regex was unanchored and accepted the first three of these.
test.each(['foo a@b.de bar', 'x!!a@b.deZZ!', 'a@b...c', 'a@b', '@b.de', 'a@.de', '', 'a@b.d1'])(
  'rejects %j',
  (s) => expect(isEmail(s)).toBe(false),
);

test('messages', () => {
  expect(message('a@b.de')).toBe('Das ist eine E-Mail Adresse!');
  expect(message('nope')).toBe('Das ist leider keine E-Mail Adresse!');
});
