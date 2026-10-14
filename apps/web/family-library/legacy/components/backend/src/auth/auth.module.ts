import {Module} from '@nestjs/common';
import {AuthService} from './auth.service';
import {PassportModule} from '@nestjs/passport';
import {LocalStrategy} from './strategies/local.strategy';
import {UserModule} from '../user/user.module';
import {AuthController} from './auth.controller';
import {ConfigModule} from '../config/config.module';
import {JwtModule} from '@nestjs/jwt';
import {ConfigService} from '../config/config.service';
import {JwtStrategy} from './strategies/jwt.strategy';

@Module({
  imports: [
    UserModule,
    PassportModule,
    ConfigModule,
    JwtModule.registerAsync({
      imports: [ConfigModule],
      inject: [ConfigService],
      useFactory: async (configService: ConfigService) => ({
        secret: configService.get('OAUTH_TOKEN_SECRET_KEY'),
        signOptions: {expiresIn: `${Number(configService.get('OAUTH_TOKEN_EXPIRATION_MINUTES'))}m`}
      })
    })
  ],
  providers: [AuthService, LocalStrategy, JwtStrategy],
  controllers: [AuthController]
})
export class AuthModule {}
