import {Strategy} from 'passport-local';
import {PassportStrategy} from '@nestjs/passport';
import {Injectable} from '@nestjs/common';
import {AuthService} from '../auth.service';
import {User} from '../../user/schemas/user.schemas';

@Injectable()
export class LocalStrategy extends PassportStrategy(Strategy) {
  public constructor(private authenticationService: AuthService) {
    super({
      usernameField: 'email'
    });
  }
  public async validate(email: string, password: string): Promise<User> {
    return this.authenticationService.getAuthenticatedUser(email, password);
  }
}
