export interface TokenPayloadInterface {
  userId: string;
  firstName: string;
  lastName: string;
  email: string;
  role: string;
}

export class TokenPayload implements TokenPayloadInterface {
  public email: string;
  public firstName: string;
  public lastName: string;
  public role: string;
  public userId: string;
}
