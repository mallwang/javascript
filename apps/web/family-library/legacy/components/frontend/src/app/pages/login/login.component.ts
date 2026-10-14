import {Component, OnInit} from '@angular/core';
import {Title} from '@angular/platform-browser';
import {AuthService} from '../../core/services/auth.service';
import {User} from '../../interfaces/user';
import {ActivatedRoute, Params, Router} from '@angular/router';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {Subscription} from 'rxjs';

@Component({
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {
  // Login form
  public loginForm;
  public loginError = null;

  // User account modal and form
  public openUserAccount = false;
  public newUserAccountForm;
  public newUserAccountError = null;
  public newUserAccountSuccess = false;
  public newUserAccountEmail;

  // Password reset modal and form
  public openPasswordReset = false;
  public passwordResetForm;
  public passwordResetError = null;
  public passwordResetSuccess = false;
  public passwordResetEmail;

  // Password reset modal and form
  public openVerificationEmail = false;
  public verificationEmailForm;
  public verificationEmailError = null;
  public verificationEmailSuccess = false;
  public verificationEmail;

  // Other
  private routeSubscription: Subscription;

  public constructor(
    private readonly activatedRoute: ActivatedRoute,
    private readonly authService: AuthService,
    private readonly router: Router,
    private readonly titleService: Title
  ) {
    this.titleService.setTitle('Login');
  }

  public ngOnInit(): void {
    const isAuthenticated = this.authService.isAuthenticated();

    // Redirect the user to the start page if authenticated
    if (isAuthenticated) {
      this.router.navigate(['buecherregal']);
    }

    this.routeSubscription = this.activatedRoute.queryParams.subscribe((params: Params) => {
      this.loginForm = new FormGroup({
        email: new FormControl(params.email, [Validators.email, Validators.required]),
        password: new FormControl('', [Validators.minLength(8), Validators.maxLength(36), Validators.required])
      });
      this.createNewUserAccountForm();
      this.createPasswordResetForm(params.email);
      this.createVerificationEmailForm(params.email);
    });
  }

  /**
   * Create a new user account form.
   */
  private createNewUserAccountForm(): void {
    this.newUserAccountForm = new FormGroup({
      firstName: new FormControl('', Validators.required),
      lastName: new FormControl('', Validators.required),
      email: new FormControl('', [Validators.email, Validators.required]),
      password: new FormControl('', [Validators.minLength(8), Validators.maxLength(36), Validators.required]),
      password2: new FormControl('', [Validators.minLength(8), Validators.maxLength(36), Validators.required])
    });
  }

  /**
   * Create a password reset form.
   * @param {string} email: The email address the password gets reset for.
   */
  private createPasswordResetForm(email?: string): void {
    this.passwordResetForm = new FormGroup({
      email: new FormControl(email, [Validators.email, Validators.required])
    });
  }

  /**
   * Create a verification email form.
   * @param {string} email: The email address the verification email gets resent for.
   */
  private createVerificationEmailForm(email?: string): void {
    this.verificationEmailForm = new FormGroup({
      email: new FormControl(email, [Validators.email, Validators.required])
    });
  }

  /**
   * Handler for submitting the form.
   */
  public async login(): Promise<any> {
    // Reset messages
    this.loginError = null;

    // Check if the fields are set
    if (this.loginForm.valid) {
      try {
        // Login user
        const user: User = this.loginForm.value;
        user.email = user.email.toLowerCase();
        const result = await this.authService.login(user);

        if (result) {
          // Redirect to the admin page
          window.location.href = 'buecherregal';
        } else {
          this.loginError = 'E-Mail Adresse und Passwort stimmen nicht überein.';
        }
      } catch (error) {
        console.error(error.message);
        this.loginError = 'E-Mail Adresse und Passwort stimmen nicht überein.';
      }
    }
  }

  /**
   * Handler for submitting the form.
   */
  public async requestNewPassword(): Promise<any> {
    // Reset messages
    this.passwordResetError = null;
    this.passwordResetSuccess = false;

    // Check if the fields are set
    if (this.passwordResetForm.valid) {
      try {
        // Login user
        const email: string = this.passwordResetForm.controls.email.value.toLowerCase();
        const result = await this.authService.forgotPassword(email);

        if (result) {
          this.passwordResetSuccess = true;
          this.passwordResetEmail = email;

          this.passwordResetForm.reset();
        } else {
          this.passwordResetError = 'Fehler beim Zurücksetzen des Passworts.';
        }
      } catch (error) {
        console.error(error.message);
        this.passwordResetError = error.error.message;
      }
    }
  }

  /**
   * Handler for submitting the form.
   */
  public async resendVerificationEmail(): Promise<any> {
    // Reset messages
    this.verificationEmailError = null;
    this.verificationEmailSuccess = false;

    // Check if the fields are set
    if (this.verificationEmailForm.valid) {
      try {
        // Login user
        const email: string = this.verificationEmailForm.controls.email.value.toLowerCase();
        const result = await this.authService.resendVerificationEmail(email);

        if (result) {
          this.verificationEmailSuccess = true;
          this.verificationEmail = email;

          this.verificationEmailForm.reset();
        } else {
          this.verificationEmailError = 'Fehler beim Zurücksetzen des Passworts.';
        }
      } catch (error) {
        console.error(error.message);
        this.verificationEmailError = error.error.message;
      }
    }
  }

  /**
   * Open the user account modal.
   */
  public openUserAccountModal(): void {
    // Reset form and state
    this.createNewUserAccountForm();
    this.newUserAccountError = null;
    this.newUserAccountSuccess = false;

    this.openUserAccount = true;
  }

  /**
   * Open the password reset modal.
   */
  public openResetPasswordModal(): void {
    // Reset form and state
    this.createPasswordResetForm();
    this.passwordResetError = null;
    this.passwordResetSuccess = false;

    this.openPasswordReset = true;
  }

  /**
   * Open the verification email modal.
   */
  public openVerificationEmailModal(): void {
    // Reset form and state
    this.createVerificationEmailForm();
    this.verificationEmailError = null;
    this.verificationEmailSuccess = false;

    this.openVerificationEmail = true;
  }

  /**
   * Create a new user account.
   */
  public async createNewAccount(): Promise<any> {
    // Reset the alerts
    this.newUserAccountError = null;
    this.newUserAccountSuccess = false;

    // Check whether the password fiels matches
    if (this.newUserAccountForm.controls['password'].value !== this.newUserAccountForm.controls['password2'].value) {
      this.newUserAccountError = 'Beide Passwörter stimmen nicht überein.';
      return;
    }

    try {
      // Create new user
      const user: User = this.newUserAccountForm.value;
      user.email = user.email.toLowerCase();
      user.role = 'user';
      await this.authService.register(user);
      this.newUserAccountSuccess = true;
      this.newUserAccountEmail = user.email;

      this.newUserAccountForm.reset();
    } catch (error) {
      if (error.error.statusCode === 409) {
        this.newUserAccountError = `Die E-Mail Adresse '${this.newUserAccountForm.value.email.toLowerCase()}' existiert bereits.`;
      } else {
        this.newUserAccountError = 'Es ist ein Fehler aufgetreten';
      }
    }
  }
}
