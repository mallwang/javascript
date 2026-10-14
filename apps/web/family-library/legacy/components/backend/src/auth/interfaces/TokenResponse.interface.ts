import {ApiProperty} from '@nestjs/swagger';

export interface TokenResponseInterface {
  access_token: string;
  token_type: string;
  expires_in: number;
}

export class TokenResponse implements TokenResponseInterface {
  @ApiProperty({
    example:
      'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJmYW1pbHkgbGlicmFyeSIsImlhdCI6MTU5NTU3ODE5NywiZXhwIjoxNjI3MTE0MTk3LCJhdWQiOiIiLCJzdWIiOiJtYXgubXVzdGVybWFubkBleGFtcGxlLmNvbSIsInVzZXJJZCI6IjVkNDI5YjlhNDY4NTdna3UxNTk3NThmMWQiLCJmaXJzdE5hbWUiOiJNYXgiLCJsYXN0TmFtZSI6Ik11c3Rlcm1hbm4iLCJlbWFpbCI6Im1heC5tdXN0ZXJtYW5uQGV4YW1wbGUuY29tIiwicm9sZSI6InVzZXIifQ.mdwbxsfPstHaMVzDOkuku0zlCstbqo0iqvb8kEN6Yfo'
  })
  public access_token: string;

  @ApiProperty({example: 3600})
  public expires_in: number;

  @ApiProperty({example: 'Bearer'})
  public token_type: string;
}
