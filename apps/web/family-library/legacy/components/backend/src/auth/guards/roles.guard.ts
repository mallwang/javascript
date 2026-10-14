import {CanActivate, ExecutionContext, Injectable} from '@nestjs/common';
import {Reflector} from '@nestjs/core';
import {User} from '../../user/schemas/user.schemas';

/**
 * The RolesGuard secures the given endpoint from only being called by a user with certain roles.
 */
@Injectable()
export class RolesGuard implements CanActivate {
  public constructor(private readonly reflector: Reflector) {}

  public canActivate(context: ExecutionContext): boolean {
    // Check if role is set and need to be verified
    const requiredRoles = this.reflector.get<Array<string>>('roles', context.getHandler());
    if (!requiredRoles) {
      return true;
    }

    // Get the current user role (from the passed access token)
    const request = context.switchToHttp().getRequest();
    const user: User = request.user;
    const userRole = user.role;

    return this.matchRoles(requiredRoles, userRole);
  }

  /**
   * Check if the user has one of the required roles.
   * @param {Array<string>} requiredRoles: The list of roles the user required at least one.
   * @param {string} userRole: The role of the user.
   * @return {boolean} Whether the user role is in the array of required roles.
   */
  private matchRoles(requiredRoles: Array<string>, userRole: string): boolean {
    return requiredRoles.indexOf(userRole) > -1;
  }
}
