import {UserService} from '../user/user.service';
import {UserDto, UserLoginDto} from '../user/dto/user.dto';
import * as bcrypt from 'bcrypt';
import * as nodemailer from 'nodemailer';
import {HttpException, HttpStatus, Injectable} from '@nestjs/common';
import {User} from '../user/schemas/user.schemas';
import {JwtService} from '@nestjs/jwt';
import {ConfigService} from '../config/config.service';
import {TokenResponse} from './interfaces/TokenResponse.interface';
import {TokenPayload} from './interfaces/TokenPayload.interface';

@Injectable()
export class AuthService {
  private transporter;

  public constructor(
    private readonly configService: ConfigService,
    private readonly jwtService: JwtService,
    private readonly userService: UserService
  ) {
    this.transporter = nodemailer.createTransport({
      host: this.configService.get('EMAIL_HOST'),
      port: this.configService.get('EMAIL_PORT'),
      secure: this.configService.get('EMAIL_SECURE'),
      auth: {
        user: this.configService.get('EMAIL_USER'),
        pass: this.configService.get('EMAIL_PASS')
      }
    });
  }

  /**
   * Verifies that the passed plain password matches the stored hashed one.
   * @param {string} plainTextPassword: The plain password.
   * @param {string} hashedPassword: The hashed password.
   */
  private static async verifyPassword(plainTextPassword: string, hashedPassword: string): Promise<void> {
    const isPasswordMatching = await bcrypt.compare(plainTextPassword, hashedPassword);
    if (!isPasswordMatching) {
      throw new HttpException('Wrong credentials provided', HttpStatus.BAD_REQUEST);
    }
  }

  /**
   * Creates a random string used for the email verification.
   * @param {number} length: The length of the random string.
   * @return {string} The random string.
   */
  private static createRandomString(length: number): string {
    let result = '';
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    const charactersLength = characters.length;
    for (let i = 0; i < length; i++) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
  }

  /**
   * Register a new user.
   * @param {UserDto} userDto: The user object.
   * @return {Promise<User>} The registered user.
   */
  public async register(userDto: UserDto): Promise<User> {
    // Hash the password
    const hashedPassword = await bcrypt.hash(userDto.password, 10);
    const verificationToken = AuthService.createRandomString(20);
    try {
      // Create a user using the hashed password
      const createdUser = await this.userService.create({
        ...userDto,
        password: hashedPassword,
        verificationToken: verificationToken,
        verified: false
      });

      // Send verification email
      await this.sendEmailVerification(createdUser.email);

      // Remove the password from the created user object
      createdUser.password = undefined;
      createdUser.verificationToken = undefined;
      return createdUser;
    } catch (error) {
      // The email address already exists
      if (error.status === 409) {
        throw new HttpException('A user with the same email address already exists', HttpStatus.CONFLICT);
      } else {
        throw new HttpException('Something went wrong', HttpStatus.INTERNAL_SERVER_ERROR);
      }
    }
  }

  /**
   * Verify a given email address using the verification token.
   * @param {string} email: The email address of the user to be verified.
   * @param {string} token: The verification token of the user to be verified.
   * @return {boolean} Whether the verification was successful.
   */
  public async verifyEmail(email: string, token: string): Promise<boolean> {
    // Get the user to be verified
    const user: User = await this.userService.findByEmail(email);
    if (user) {
      // Check if the verificationToken matches
      if (user.verificationToken === token) {
        user.verified = true;
        user.verificationToken = null;
        await this.userService.update(user['_id'], user);
        return true;
      } else {
        throw new HttpException(
          {
            status: HttpStatus.BAD_REQUEST,
            error: 'Wrong credentials provided'
          },
          HttpStatus.BAD_REQUEST
        );
      }
    } else {
      throw new HttpException(
        {
          status: HttpStatus.BAD_REQUEST,
          error: 'Wrong credentials provided'
        },
        HttpStatus.BAD_REQUEST
      );
    }
  }

  /**
   * Gets the user from the database using the passed email address and password.
   * @param {string} email: The email address of the user to be logged in.
   * @param {string} password: The password of the user to be logged in.
   * @return {User} The user object.
   */
  public async getAuthenticatedUser(email: string, password: string): Promise<User> {
    try {
      // Get the user by his email address
      const user = await this.userService.findByEmail(email);
      // Verify that the passed plain password matches the stored hashed one.
      await AuthService.verifyPassword(password, user.password);
      // Remove the password from the user object
      user.password = undefined;
      return user;
    } catch (error) {
      throw new HttpException('Wrong credentials provided', HttpStatus.BAD_REQUEST);
    }
  }

  /**
   * Creates a valid and signed JWT token.
   * @param {User} user: The user the token gets created for.
   * @return {TokenResponse}: The token response.
   */
  public createToken(user: User): TokenResponse {
    // Check if the user is verified
    if (!user.verified) {
      throw new HttpException(
        {
          status: HttpStatus.BAD_REQUEST,
          error: 'The email address is not verified'
        },
        HttpStatus.BAD_REQUEST
      );
    }

    // Create the token payload
    const payload: TokenPayload = {
      userId: user['_id'],
      firstName: user.firstName,
      lastName: user.lastName,
      email: user.email,
      role: user.role
    };

    // Sign the token
    const expirationMinutes = Number(this.configService.get('OAUTH_TOKEN_EXPIRATION_MINUTES'));
    const signedToken = this.jwtService.sign(payload, {
      issuer: 'family library',
      subject: payload.email,
      audience: '',
      expiresIn: `${expirationMinutes}m`
    });

    // Create the token response
    const tokenResponse = new TokenResponse();
    // eslint-disable-next-line @typescript-eslint/camelcase
    tokenResponse.access_token = signedToken;
    // eslint-disable-next-line @typescript-eslint/camelcase
    tokenResponse.token_type = 'Bearer';
    // eslint-disable-next-line @typescript-eslint/camelcase
    tokenResponse.expires_in = expirationMinutes * 60;

    return tokenResponse;
  }

  /**
   * Send a email to the user for verifying the email address.
   * @param {string} email: The email address of the user to be verified.
   * @return {boolean} Whether the email could be sent.
   */
  private async sendEmailVerification(email: string): Promise<boolean> {
    // Get the user to be verified
    const user: User = await this.userService.findByEmail(email);

    // Check if the user exists and is verified
    if (user && !user.verified) {
      // Create the email options
      const emailOptions = {
        from: `Family Library Benutzerverwaltung <${this.configService.get('EMAIL_USER')}>`,
        to: email,
        subject: `Herzlich Willkommen ${user.firstName}, bitte bestätige deine E-Mail Adresse`,
        text: `Herzlich Willkommen ${user.firstName}, bitte bestätige deine E-Mail Adresse`,
        html: `Hallo ${user.firstName},<br>
        <br>
        vielen Dank für deine Registrierung.<br>
        <br>
        Bitte bestätige deine E-Mail Adresse um dich einloggen zu können: <a href="${this.configService.get(
          'BASE_DOMAIN'
        )}:${this.configService.get('PORT')}/${this.configService.get(
          'API_VERSION'
        )}/auth/verify?email=${email}&token=${user.verificationToken}">E-Mail Adresse bestätigen</a><br>
        <br><br>
        Vielen Dank und schöne Grüße,<br>
        <br>
        Family Library Benutzerverwaltung`
      };

      // Send the email
      const sent = await this.transporter.sendMail(emailOptions);
      return sent;
    } else {
      throw new HttpException('User does not exist or is already verified', HttpStatus.BAD_REQUEST);
    }
  }

  /**
   * Send a email to the user when the user forgot his password.
   * @param {string} email: The email address of the user.
   * @return {boolean}: Whether the email has been sent to the user.
   */
  public async sendEmailForgotPassword(email: string): Promise<boolean> {
    // Get the user object
    const user: User = await this.userService.findByEmail(email);

    // Check if the user is verified
    if (user && user.verified) {
      const resetPasswordVerificationToken = AuthService.createRandomString(20);

      await this.userService.update(user['_id'], {
        resetPasswordVerificationToken: resetPasswordVerificationToken
      });

      // Create the email options
      const emailOptions = {
        from: `Family Library Benutzerverwaltung <${this.configService.get('EMAIL_USER')}>`,
        to: email,
        subject: 'Ein neues Passwort wurde angefordert',
        text: 'Ein neues Passwort wurde angefordert',
        html: `Hallo ${user.firstName},<br>
        <br>
        für deinen Account wurde ein neues Passwort angefordert.<br>
        <br>
        Du kannst dein Passwort unter folgendem Link zurücksetzen: <a href="${this.configService.get(
          'BASE_DOMAIN'
        )}:${this.configService.get(
          'FRONTEND_PORT'
        )}/reset-password?email=${email}&token=${resetPasswordVerificationToken}">Passwort zurücksetzen</a><br>
        <br>
        Falls diese Anforderung nicht von dir durchgeführt wurde, kannst du diese E-Mail ignorieren und löschen.<br><br>
        Vielen Dank und schöne Grüße,<br>
        <br>
        Family Library Benutzerverwaltung`
      };

      // Send the email
      const sent = await this.transporter.sendMail(emailOptions);
      return sent;
    } else {
      throw new HttpException('User does not exist or is not verified yet.', HttpStatus.BAD_REQUEST);
    }
  }

  /**
   * Set a new password for the given user email address.
   * @param {UserLoginDto} userDto: The user object.
   * @param {string} token: The verification token for resetting the password.
   * @return {boolean}: Whether a new password could be set for the given user.
   */
  public async resetPassword(userDto: UserLoginDto, token: string): Promise<boolean> {
    // Get the user email address
    const user: User = await this.userService.findByEmail(userDto.email);

    // Check if the user exists and is verified
    if (user && user.verified) {
      // Check if the token matches
      if (user.resetPasswordVerificationToken === token) {
        // Hash the password
        const hashedPassword = await bcrypt.hash(userDto.password, 10);

        // Create a user using the hashed password
        const updatedUser = await this.userService.update(user['_id'], {
          password: hashedPassword,
          resetPasswordVerificationToken: null
        });

        if (updatedUser) {
          return true;
        } else {
          throw new HttpException('Something went wrong', HttpStatus.BAD_REQUEST);
        }
      } else {
        throw new HttpException('Wrong credentials provided', HttpStatus.BAD_REQUEST);
      }
    } else {
      throw new HttpException('User does not exist or is not verified yet', HttpStatus.BAD_REQUEST);
    }
  }

  /**
   * Resend the verification email.
   * @param {string} email: The email address of the user.
   * @return {boolean}: Whether the email has been sent to the user.
   */
  public async resendVerificationEmail(email: string): Promise<boolean> {
    return this.sendEmailVerification(email);
  }
}
