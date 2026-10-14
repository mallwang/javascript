import {Injectable} from '@angular/core';
import {ActivatedRouteSnapshot, CanActivate, Router, RouterStateSnapshot} from '@angular/router';
import {AuthService} from '../services/auth.service';

@Injectable({
  providedIn: 'root'
})
export class AdminGuard implements CanActivate {
  public constructor(private readonly authService: AuthService, private readonly router: Router) {}

  /**
   * Check if the current user is authenticated and forward to the login page if not, preserving the previous visited url.
   * @param {ActivatedRouteSnapshot} next: The information about the current route.
   * @param {RouterStateSnapshot} state: The current state of the router.
   * @return {Promise<boolean>} Whether the user is allowed to access the given route.
   */
  public async canActivate(next: ActivatedRouteSnapshot, state: RouterStateSnapshot): Promise<boolean> {
    if (!this.authService.isAuthenticated()) {
      await this.router.navigate(['login'], {queryParams: {returnUrl: state.url}});
      return false;
    } else {
      const user = this.authService.getUser();
      return user.role === 'admin';
    }
  }
}
