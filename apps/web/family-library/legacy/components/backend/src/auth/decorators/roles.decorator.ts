import {SetMetadata} from '@nestjs/common';

export const Roles = (...roles: Array<string>): any => SetMetadata('roles', roles);
