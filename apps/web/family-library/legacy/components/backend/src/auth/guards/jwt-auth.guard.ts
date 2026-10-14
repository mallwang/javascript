import {Injectable} from '@nestjs/common';
import {AuthGuard} from '@nestjs/passport';

/**
 * The JwtAuthGuard secures the given endpoint from only being called using a valid JWT authorization header.
 */
@Injectable()
export default class JwtAuthGuard extends AuthGuard('jwt') {}
