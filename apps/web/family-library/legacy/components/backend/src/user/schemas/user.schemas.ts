import {Prop, Schema, SchemaFactory} from '@nestjs/mongoose';
import {Document} from 'mongoose';
import {ApiHideProperty, ApiProperty} from '@nestjs/swagger';

@Schema()
export class User extends Document {
  @Prop({required: true})
  @ApiProperty({example: 'Max', required: false})
  public firstName: string;

  @Prop({required: true})
  @ApiProperty({example: 'Mustermann', required: false})
  public lastName: string;

  @Prop({required: true, unique: true})
  @ApiProperty({example: 'max.mustermann@example.com', required: false})
  public email: string;

  @Prop({required: true, expose: false, minlength: 8, maxLength: 36})
  @ApiHideProperty()
  public password: string;

  @Prop({required: true})
  @ApiProperty({example: 'user', required: false})
  public role: string;

  @Prop({required: true})
  @ApiHideProperty()
  public verificationToken: string;

  @Prop({required: false})
  @ApiHideProperty()
  public readonly resetPasswordVerificationToken: string;

  @Prop({required: true})
  @ApiHideProperty()
  public verified: boolean;

  @Prop({type: Date, default: new Date().getTime()})
  private updatedAt: Date;
}

export const UserSchema = SchemaFactory.createForClass(User);
