import {User} from '../../user/schemas/user.schemas';
import {Request} from '@nestjs/common';

interface RequestWithUser extends Request {
  user: User;
}

export default RequestWithUser;
