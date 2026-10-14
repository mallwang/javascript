import {ExtractJwt, Strategy} from 'passport-jwt';
import {PassportStrategy} from '@nestjs/passport';
import {Injectable} from '@nestjs/common';
import {UserService} from '../../user/user.service';
import {ConfigService} from '../../config/config.service';
import {User} from '../../user/schemas/user.schemas';

@Injectable()
export class JwtStrategy extends PassportStrategy(Strategy) {
  public constructor(private readonly configService: ConfigService, private readonly userService: UserService) {
    super({
      jwtFromRequest: ExtractJwt.fromAuthHeaderAsBearerToken(),
      ignoreExpiration: false,
      secretOrKey: configService.get('OAUTH_TOKEN_SECRET_KEY')
    });
  }

  public async validate(payload: any): Promise<User> {
    return this.userService.findOne(payload.userId);
  }
}
