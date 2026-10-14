import {Injectable} from '@angular/core';
import {User} from '../../interfaces/user';
import {environment} from '../../../environments/environment';
import {HttpClient, HttpParams} from '@angular/common/http';
import {LocalStorageService} from 'angular-2-local-storage';
import decode from 'jwt-decode';
import {Router} from '@angular/router';
import {Token} from '../../shared/interfaces/Token';
import {TokenResponse} from '../../shared/interfaces/TokenResponse';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  public baseUrl = `${environment.backendBaseUrl}/auth`;
  private key = 'access_token';

  public constructor(
    private readonly httpClient: HttpClient,
    private readonly localStorageService: LocalStorageService,
    private readonly router: Router
  ) {}

  /**
   * Check if the access token is valid and not expired yet.
   * @param {string} token: The access token to be validated.
   * @return {boolean} Whether the given access token is valid.
   */
  private static isTokenValid(token: string): boolean {
    const decodedToken = decode(token);
    return decodedToken && decodedToken.exp * 1000 > +new Date();
  }

  /**
   * Generic request using the http client.
   * @param {string} method: The http method.
   * @param {string} url: The URL to be called.
   * @param {any} data: The body data.
   * @param {HttpParams} params: The HttpParams.
   * @return {Promise<any>}: The promisified request.
   */
  private request(method: string, url: string, data?: any, params?: HttpParams): Promise<any> {
    const result = this.httpClient.request(method, url, {
      body: data,
      responseType: 'json',
      observe: 'body',
      params: params
    });

    return new Promise<any>((resolve, reject) => {
      result.subscribe(resolve, reject);
    });
  }

  /**
   * Login a user by email and password.
   * @param {User} user: The user to be logged in.
   * @return {boolean} Whether the user could be logged in or not.
   */
  public async login(user: User): Promise<boolean> {
    try {
      const token: TokenResponse = await this.request('POST', `${this.baseUrl}/login`, user);
      // Store the token in the local storage
      this.storeToken(token.access_token);
      return true;
    } catch (error) {
      return false;
    }
  }

  /**
   * Register a new user.
   * @param {User} user: The user to be registered.
   * @return {Promise<User>} The user which has been registered.
   */
  public async register(user: User): Promise<boolean> {
    return this.request('POST', `${this.baseUrl}/register`, user);
  }

  /**
   * Send a reset password request.
   * @param {string} email: The user who resets the password.
   * @return {boolean} Whether the reset password request was successful.
   */
  public async forgotPassword(email: string): Promise<boolean> {
    return this.request('GET', `${this.baseUrl}/forgot-password?email=${email}`);
  }

  /**
   * Resend the verification email.
   * @param {string} email: The user the verification email gets resent for.
   * @return {boolean} Whether the verification email could be sent.
   */
  public async resendVerificationEmail(email: string): Promise<boolean> {
    return this.request('GET', `${this.baseUrl}/resend-verification?email=${email}`);
  }

  /**
   * Reset the user password.
   * @param {User} user: The user who resets the password.
   * @param {string} token: The verification token for resetting the password.
   * @return {boolean} Whether the new password could be set.
   */
  public async resetPassword(user: User, token: string): Promise<boolean> {
    return this.request('POST', `${this.baseUrl}/reset-password?token=${token}`, user);
  }

  /**
   * Logout the user by deleting the token from the local storage.
   */
  public logout(): void {
    this.localStorageService.remove(this.key);
  }

  /**
   * Returns the full name of the token user.
   * @return {string} The full name of the token user.
   */
  public get fullName(): string {
    const decodedToken: Token = this.decodeToken();
    if (decodedToken) {
      return `${decodedToken.firstName} ${decodedToken.lastName}`;
    } else {
      return '';
    }
  }

  /**
   * Returns the email address of the token user.
   * @return {string} The email address of the token user.
   */
  public get email(): string {
    const decodedToken: Token = this.decodeToken();
    return decodedToken.email;
  }

  /**
   * Returns the id of the token user.
   * @return {string} The id of the token user.
   */
  public get id(): string {
    const decodedToken: Token = this.decodeToken();
    return decodedToken.userId;
  }

  /**
   * Check if the current user is authenticated by verifying the access token and check if it is still valid.
   * @return {boolean} Whether the user is authenticated or not.
   */
  public isAuthenticated(): boolean {
    const token: string = this.getToken();

    const isAuthenticated = token !== null && AuthService.isTokenValid(token);

    if (!isAuthenticated) {
      this.localStorageService.remove(this.key);
    }

    return isAuthenticated;
  }

  /**
   * Decode the access token.
   * NOTE: if the token cannot be decoded it will be removed form the local storage and the user gets redirected to the login page.
   * @return {Token} The decoded access token.
   */
  private decodeToken(): Token {
    let decodedToken;
    try {
      decodedToken = decode(this.getToken());
    } catch (error) {
      this.localStorageService.remove(this.key);
      // this.router.navigate(['/login']);
    }
    return decodedToken;
  }

  /**
   * Store the access token in the local storage.
   * @param {string} token: The access token to be stored in the local storage.
   */
  private storeToken(token: string): void {
    this.localStorageService.set(this.key, token);
  }

  /**
   * Get the access token of the current logged in user.
   * @return {string} The access token.
   */
  public getToken(): string {
    return this.localStorageService.get(this.key);
  }

  /**
   * Get the current user data by accessing the stored access token.
   * @return {User} The user parsed from the access token.
   */
  public getUser(): User {
    const decodedToken: Token = this.decodeToken();

    if (decodedToken) {
      const user: User = {
        _id: decodedToken.userId,
        firstName: decodedToken.firstName,
        lastName: decodedToken.lastName,
        role: decodedToken.role,
        email: decodedToken.email.toLowerCase()
      };
      return user;
    }
  }
}
