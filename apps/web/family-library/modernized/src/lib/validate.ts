// Validators return an error code (see messages.ts) or null when the input is valid.

export const normalizeEmail = (email: string) => email.trim().toLowerCase();
export const normalizeIsbn = (isbn: string) => isbn.replace(/[\s-]/g, '').toUpperCase();

const EMAIL = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const ISBN = /^(\d{9}[\dX]|\d{13})$/;

export function validatePassword(password: string): string | null {
  return password.length >= 8 && password.length <= 128 ? null : 'invalid_password';
}

export type RegistrationInput = {
  firstName: string;
  lastName: string;
  email: string;
  password: string;
};

export function validateRegistration(i: RegistrationInput): string | null {
  if (!i.firstName || i.firstName.length > 100 || !i.lastName || i.lastName.length > 100) {
    return 'invalid_name';
  }
  if (!EMAIL.test(i.email) || i.email.length > 254) return 'invalid_email';
  return validatePassword(i.password);
}

export type BookInput = {
  title: string;
  author: string;
  isbn: string;
  year: number;
  language: string;
};

export function validateBook(i: BookInput): string | null {
  if (!i.title || i.title.length > 200) return 'invalid_title';
  if (!i.author || i.author.length > 200) return 'invalid_author';
  if (!ISBN.test(i.isbn)) return 'invalid_isbn';
  if (!Number.isInteger(i.year) || i.year < 1900 || i.year > new Date().getFullYear()) {
    return 'invalid_year';
  }
  if (!i.language || i.language.length > 50) return 'invalid_language';
  return null;
}
