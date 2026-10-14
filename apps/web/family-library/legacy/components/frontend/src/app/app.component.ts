import {Component, OnInit} from '@angular/core';
import {Title} from '@angular/platform-browser';
import {AuthService} from './core/services/auth.service';
import {Router} from '@angular/router';
import {User} from './interfaces/user';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit {
  // Other
  public user: User;
  public isAuthenticated = false;

  public constructor(
    private readonly authService: AuthService,
    private readonly router: Router,
    private readonly titleService: Title
  ) {
    this.titleService.setTitle('Familienbiliothek');
  }

  public ngOnInit(): void {
    // Get the current user
    this.user = this.authService.getUser();
    this.isAuthenticated = this.authService.isAuthenticated();
  }

  /**
   * Logout the user and redirect to the login page.
   */
  public logout(): void {
    // Log the user out
    this.authService.logout();

    // Redirect the user to the login page
    window.location.href = 'login';
  }
}
