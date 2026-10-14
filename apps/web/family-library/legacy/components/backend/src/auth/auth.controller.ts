import {
  Body,
  Controller,
  Get,
  HttpCode,
  HttpException,
  HttpStatus,
  Post,
  Query,
  Req,
  Res,
  UseGuards
} from '@nestjs/common';
import {AuthService} from './auth.service';
import {LocalAuthGuard} from './guards/local-auth-guard.service';
import {UserDto, UserLoginDto} from '../user/dto/user.dto';
import {ApiBearerAuth, ApiBody, ApiOkResponse, ApiOperation, ApiTags} from '@nestjs/swagger';
import {User} from '../user/schemas/user.schemas';
import RequestWithUser from './interfaces/RequestWithUser.interface';
import JwtAuthGuard from './guards/jwt-auth.guard';
import {TokenResponse} from './interfaces/TokenResponse.interface';
import {ConfigService} from '../config/config.service';
import {URL} from 'url';

@ApiTags('Auth')
@Controller('auth')
export class AuthController {
  public constructor(private readonly authService: AuthService, private readonly configService: ConfigService) {}

  @Post('register')
  @ApiOperation({
    description: 'Register a new user.'
  })
  @ApiOkResponse({
    type: User,
    description: 'The request was successful.'
  })
  public async register(@Body() userDto: UserDto): Promise<User> {
    return this.authService.register(userDto);
  }

  @Post('login')
  @UseGuards(LocalAuthGuard)
  @HttpCode(200)
  @ApiBody({type: UserLoginDto})
  @ApiOperation({
    description: 'Login a user.'
  })
  @ApiOkResponse({
    type: TokenResponse,
    description: 'The request was successful.'
  })
  public async login(@Req() request: RequestWithUser): Promise<TokenResponse> {
    // Read the user from the payload and create a access token for it
    const user: User = request.user;
    return this.authService.createToken(user);
  }

  @Get('verify')
  @ApiOperation({
    description: 'Verify the user.'
  })
  @ApiOkResponse({
    type: Boolean,
    description: 'The request was successful.'
  })
  public async verifyEmail(
    @Query('email') email: string,
    @Query('token') token: string,
    @Res() response
  ): Promise<boolean> {
    const emailVerified = await this.authService.verifyEmail(email, token);
    if (emailVerified) {
      const loginUrl = new URL(
        `${this.configService.get('BASE_DOMAIN')}:${this.configService.get('FRONTEND_PORT')}/login`
      );
      return response
        .type('text/html')
        .send(
          `Deine E-Mail Adresse wurde erfolgreich verifiziert, du kannst dich nun einloggen: <a href="${loginUrl.toString()}">Family Library Login</a></a>`
        );
    } else {
      throw new HttpException(
        {
          status: HttpStatus.BAD_REQUEST,
          error: 'Email could not be verified'
        },
        HttpStatus.BAD_REQUEST
      );
    }
  }

  @Get('')
  @UseGuards(JwtAuthGuard)
  @ApiBearerAuth()
  @ApiOperation({
    description: 'Verify that the token is valid.'
  })
  @ApiOkResponse({
    type: User,
    description: 'The request was successful.'
  })
  public authenticate(@Req() request: RequestWithUser): User {
    // Read the user from the payload and return it
    const user: User = request.user;
    user.password = undefined;
    return user;
  }

  @Get('forgot-password')
  @ApiOperation({
    description: 'Send an email to request a new password.'
  })
  @ApiOkResponse({
    type: Boolean,
    description: 'The request was successful.'
  })
  public async sendEmailForgotPassword(@Query('email') email: string): Promise<boolean> {
    return this.authService.sendEmailForgotPassword(email);
  }

  @Post('reset-password')
  @ApiOperation({
    description: 'Set a new user password.'
  })
  @ApiOkResponse({
    type: Boolean,
    description: 'The request was successful.'
  })
  public async resetPassword(@Body() userDto: UserLoginDto, @Query('token') token: string): Promise<boolean> {
    return this.authService.resetPassword(userDto, token);
  }

  @Get('resend-verification')
  @ApiOperation({
    description: 'Resend the verification email.'
  })
  @ApiOkResponse({
    type: Boolean,
    description: 'The request was successful.'
  })
  public async resendVerificationEmail(@Query('email') email: string): Promise<boolean> {
    return this.authService.resendVerificationEmail(email);
  }
}
