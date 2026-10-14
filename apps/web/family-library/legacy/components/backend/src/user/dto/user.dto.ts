import {IsEmail, IsNotEmpty, IsOptional, MaxLength, MinLength} from 'class-validator';
import {ApiHideProperty, ApiProperty} from '@nestjs/swagger';

export class UserDto {
  @IsNotEmpty()
  @ApiProperty({example: 'Max', required: true})
  public readonly firstName?: string;

  @IsNotEmpty()
  @ApiProperty({example: 'Mustermann', required: true})
  public readonly lastName?: string;

  @IsEmail()
  @IsNotEmpty()
  @ApiProperty({example: 'max.mustermann@example.com', required: true})
  public readonly email: string;

  @IsNotEmpty()
  @MinLength(8)
  @MaxLength(36)
  @ApiProperty({example: 'secretPassword', required: true})
  public readonly password: string;

  @IsNotEmpty()
  @ApiProperty({example: 'user', required: true})
  public readonly role: string;

  @IsOptional()
  @ApiHideProperty()
  public readonly verificationToken?: string;

  @IsOptional()
  @ApiHideProperty()
  public readonly resetPasswordVerificationToken?: string;

  @IsOptional()
  @ApiHideProperty()
  public readonly verified?: boolean;
}

export class UserUpdateDto {
  @IsOptional()
  @ApiProperty({example: 'Max', required: false})
  public readonly firstName?: string;

  @IsOptional()
  @ApiProperty({example: 'Mustermann', required: false})
  public readonly lastName?: string;

  @IsEmail()
  @IsOptional()
  @ApiProperty({example: 'max.mustermann@example.com', required: false})
  public readonly email?: string;

  @IsOptional()
  @MinLength(8)
  @MaxLength(36)
  @ApiProperty({example: 'secretPassword', required: false})
  public readonly password?: string;

  @IsOptional()
  @ApiProperty({example: 'user', required: false})
  public readonly role?: string;

  @IsOptional()
  @ApiHideProperty()
  public readonly verificationToken?: string;

  @IsOptional()
  @ApiHideProperty()
  public readonly resetPasswordVerificationToken?: string;

  @IsOptional()
  @ApiHideProperty()
  public readonly verified?: boolean;
}

export class UserLoginDto {
  @IsEmail()
  @IsNotEmpty()
  @ApiProperty({example: 'max.mustermann@example.com', required: true})
  public readonly email: string;

  @IsNotEmpty()
  @MinLength(8)
  @MaxLength(36)
  @ApiHideProperty()
  @ApiProperty({example: 'secretPassword', required: true})
  public readonly password: string;
}
