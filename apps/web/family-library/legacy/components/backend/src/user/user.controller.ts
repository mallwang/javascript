import {Body, Controller, Delete, Get, Param, Post, Put, Req, UseGuards} from '@nestjs/common';
import {UserService} from './user.service';
import {UserDto, UserUpdateDto} from './dto/user.dto';
import {User} from './schemas/user.schemas';
import {ApiBearerAuth, ApiOkResponse, ApiOperation, ApiTags} from '@nestjs/swagger';
import JwtAuthGuard from '../auth/guards/jwt-auth.guard';
import {Roles} from '../auth/decorators/roles.decorator';
import {RolesGuard} from '../auth/guards/roles.guard';
import RequestWithUser from '../auth/interfaces/RequestWithUser.interface';

@ApiBearerAuth()
@ApiTags('Users')
@UseGuards(JwtAuthGuard, RolesGuard)
@Controller('users')
export class UserController {
  public constructor(private readonly userService: UserService) {}

  @Post('')
  @Roles('admin')
  @ApiOperation({
    description: 'Create a new user.'
  })
  @ApiOkResponse({
    type: User,
    description: 'The request was successful.'
  })
  public async create(@Body() userDto: UserDto): Promise<User> {
    // TODO: do not return the password
    return this.userService.create(userDto);
  }

  @Get('')
  @Roles('admin')
  @ApiOperation({
    description: 'Get all users.'
  })
  @ApiOkResponse({
    type: [User],
    description: 'The request was successful.'
  })
  public async findAll(): Promise<Array<User>> {
    return this.userService.findAll();
  }

  @Get('current')
  @ApiBearerAuth()
  @ApiOperation({
    description: 'Get the current user.'
  })
  @ApiOkResponse({
    type: User,
    description: 'The request was successful.'
  })
  public current(@Req() request: RequestWithUser): User {
    // Read the user from the payload and return it
    const user: User = request.user;
    user.password = undefined;
    return user;
  }

  @Get(':id')
  @Roles('admin')
  @ApiOperation({
    description: 'Get a single user.'
  })
  @ApiOkResponse({
    type: User,
    description: 'The request was successful.'
  })
  public async findOne(@Param('id') id: string): Promise<User> {
    return this.userService.findOne(id);
  }

  @Put(':id')
  @Roles('admin')
  @ApiOperation({
    description: 'Update a user.'
  })
  @ApiOkResponse({
    type: User,
    description: 'The request was successful.'
  })
  public async update(@Param('id') id: string, @Body() userDto: UserUpdateDto): Promise<User> {
    return this.userService.update(id, userDto);
  }

  @Delete(':id')
  @Roles('admin')
  @ApiOperation({
    description: 'Delete a user.'
  })
  @ApiOkResponse({
    type: User,
    description: 'The request was successful.'
  })
  public async delete(@Param('id') id: string): Promise<User> {
    return this.userService.delete(id);
  }
}
