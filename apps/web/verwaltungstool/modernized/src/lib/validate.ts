export const validateNickname = (n: string) =>
  n.length < 4 || n.length > 30
    ? 'Nickname must have 4 to 30 characters'
    : !/^[\p{L}\p{N}_.-]+$/u.test(n)
      ? 'Nickname may only contain letters, digits, _ . -'
      : null;

export const validateEmail = (e: string) =>
  e.length <= 254 && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(e) ? null : 'Invalid e-mail address';

export const validatePassword = (p: string) =>
  p.length < 6 || p.length > 200 ? 'Password must have at least 6 characters' : null;
