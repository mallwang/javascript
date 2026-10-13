// Anchored, pragmatic check (not full RFC 5322): local part, dot-separated domain labels, alphabetic TLD.
const EMAIL = /^[A-Za-z0-9._%+-]+@(?:[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?\.)+[A-Za-z]{2,}$/;

export const isEmail = (s: string): boolean => s.length <= 254 && EMAIL.test(s);

export const message = (s: string): string =>
  isEmail(s) ? 'Das ist eine E-Mail Adresse!' : 'Das ist leider keine E-Mail Adresse!';
