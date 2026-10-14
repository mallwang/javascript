import {Component, OnDestroy, OnInit} from '@angular/core';
import {AuthService} from '../../core/services/auth.service';
import {Title} from '@angular/platform-browser';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {User} from '../../interfaces/user';
import {ActivatedRoute, Params, Router} from '@angular/router';
import {Subscription} from 'rxjs';

@Component({
  templateUrl: './reset-password.component.html',
  styleUrls: ['./reset-password.component.scss']
})
export class ResetPasswordComponent implements OnInit, OnDestroy {
  // Password form
  public passwordForm = new FormGroup({});
  public passwordError = null;
  public passwordSuccess = false;

  // Other
  public email: string;
  private token: string;
  private routeSubscription: Subscription;

  public constructor(
    private readonly activatedRoute: ActivatedRoute,
    private readonly authService: AuthService,
    private readonly router: Router,
    private readonly titleService: Title
  ) {
    this.titleService.setTitle('Passwort zurücksetzen');
  }

  public ngOnInit(): void {
    const isAuthenticated = this.authService.isAuthenticated();

    // Redirect the user to the start page if authenticated
    if (isAuthenticated) {
      this.router.navigate(['login']);
    }

    this.routeSubscription = this.activatedRoute.queryParams.subscribe((params: Params) => {
      if (params.email && params.token) {
        this.email = params.email;
        this.token = params.token;
        this.passwordForm = new FormGroup({
          email: new FormControl(this.email, [Validators.email, Validators.required]),
          password: new FormControl('', [Validators.minLength(8), Validators.maxLength(36), Validators.required]),
          password2: new FormControl('', [Validators.minLength(8), Validators.maxLength(36), Validators.required])
        });
      } else {
        this.passwordError = 'E-Mail Adresse oder Token ungültig.';
      }
    });
  }

  public ngOnDestroy(): void {
    this.routeSubscription.unsubscribe();
  }

  /**
   * Handler for submitting the form.
   */
  public async onSubmit(): Promise<any> {
    // Check if the fields are set
    if (this.passwordForm.valid) {
      try {
        const user: User = this.passwordForm.value;

        // Check whether the password fiels matches
        if (this.passwordForm.controls['password'].value !== this.passwordForm.controls['password2'].value) {
          this.passwordError = 'Beide Passwörter stimmen nicht überein.';
          return;
        }

        // Set the new password
        const result = await this.authService.resetPassword(user, this.token);

        if (result) {
          this.passwordSuccess = true;
          this.passwordError = null;

          // Reset form
          this.passwordForm.reset();
        } else {
          this.passwordError = 'Das Passwort konnte nicht aktualisiert werden.';
        }
      } catch (error) {
        console.error(error.message);
        this.passwordError = error.error.message;
      }
    }
  }
}
