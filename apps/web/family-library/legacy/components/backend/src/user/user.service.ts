import {HttpException, HttpStatus, Injectable} from '@nestjs/common';
import {InjectModel} from '@nestjs/mongoose';
import {User} from './schemas/user.schemas';
import {Model} from 'mongoose';
import {UserDto, UserUpdateDto} from './dto/user.dto';

@Injectable()
export class UserService {
  public constructor(@InjectModel(User.name) private readonly userModel: Model<User>) {}

  /**
   * Create a new user.
   * @param {UserDto} userDto: The user to be created.
   * @return {Promise<User>}: The created user object.
   */
  public async create(userDto: UserDto): Promise<User> {
    const createdUser = new this.userModel(userDto);

    // Check if the user exists
    const userExists = await this.userModel.exists({email: userDto.email});
    if (userExists) {
      throw new HttpException(
        {
          status: HttpStatus.CONFLICT,
          error: 'The email address already exists'
        },
        HttpStatus.CONFLICT
      );
    } else {
      const savedUser = await createdUser.save();
      if (savedUser) {
        // Hide the password property
        savedUser.password = null;
      }
      return savedUser;
    }
  }

  /**
   * Find all users.
   * @return {Promise<Array<User>>}: A list of user objects.
   */
  public async findAll(): Promise<Array<User>> {
    return await this.userModel.find({}, {firstName: true, lastName: true, email: true, role: true}).exec();
  }

  /**
   * Find a single user.
   * @param {string} id: The id of the user.
   * @return {User}: The user object.
   */
  public async findOne(id: string): Promise<User> {
    return await this.userModel.findById(id, {firstName: true, lastName: true, email: true, role: true}).exec();
  }

  /**
   * Find a single user by its email address.
   * @param {string} email: The email address of the user.
   * @return {User}: The user object.
   */
  public async findByEmail(email: string): Promise<User> {
    return await this.userModel.findOne({email: email}).exec();
  }

  /**
   * Update a user.
   * @param {string} id: The id of the user to be updated.
   * @param {UserUpdateDto} userDto: The user to be updated.
   * @return {User}: The updated user object.
   */
  public async update(id: string, userDto: UserUpdateDto): Promise<User> {
    const updatedUser = await this.userModel.findByIdAndUpdate(id, userDto, {new: true});
    if (updatedUser) {
      // Hide the password property
      updatedUser.password = null;
    }
    return updatedUser;
  }

  /**
   * Delete a user.
   * @param {string} id: The id of the user to be deleted.
   * @return {User}: The deleted user object.
   */
  public async delete(id: string): Promise<User> {
    const deletedUser = await this.userModel.findByIdAndRemove(id);
    if (deletedUser) {
      // Hide the password property
      deletedUser.password = null;
    }
    return deletedUser;
  }
}
