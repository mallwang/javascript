export interface Token {
  iss?: string;
  iat?: number;
  exp?: number;
  userId: string;
  email: string;
  firstName: string;
  lastName: string;
  role: string;
}
