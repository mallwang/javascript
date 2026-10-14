import {Injectable} from '@nestjs/common';
import {AuthGuard} from '@nestjs/passport';

/**
 * The LocalAuthGuard secures the given endpoint from only being called using a valid username and password.
 */
@Injectable()
export class LocalAuthGuard extends AuthGuard('local') {}
