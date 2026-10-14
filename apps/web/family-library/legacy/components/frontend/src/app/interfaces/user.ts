export interface User {
  _id?: string;
  firstName?: string;
  lastName?: string;
  role?: string;
  email?: string;
  password?: string;
  verificationToken?: string;
  resetPasswordVerificationToken?: string;
  verified?: boolean;
}
