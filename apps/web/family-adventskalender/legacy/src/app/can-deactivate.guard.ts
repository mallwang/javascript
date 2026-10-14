import {Injectable} from '@angular/core';
import {ActivatedRouteSnapshot, CanDeactivate, Router} from '@angular/router';
import {Observable} from 'rxjs';
import {Location} from '@angular/common';

export interface CanComponentDeactivate {
  canDeactivate: () => Observable<boolean> | Promise<boolean> | boolean;
}

@Injectable({
  providedIn: 'root'
})
export class CanDeactivateGuard implements CanDeactivate<CanComponentDeactivate> {
  public constructor(private readonly location: Location, private readonly router: Router) {
    console.log('[CanDeactivateGuard] in constructor');
  }

  /**
   * Check if the component has a canDeactivate method and execute it
   * @param {CanComponentDeactivate} component: The angular component
   * @param {ActivatedRouteSnapshot} currentRoute: The snapshot of the current route
   * @return {Promise<boolean>} whether it can be navigated away from the route or not
   */
  public async canDeactivate(
    component: CanComponentDeactivate,
    currentRoute: ActivatedRouteSnapshot
  ): Promise<boolean> {
    const canDeactivate = component && component.canDeactivate ? component.canDeactivate() : true;
    const value = await canDeactivate;
    if (!value) {
      const currentUrlTree = this.router.createUrlTree([], currentRoute);
      const currentUrl = currentUrlTree.toString();
      this.location.go(currentUrl);
      return false;
    } else {
      return true;
    }
  }
}
