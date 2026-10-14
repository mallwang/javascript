import {IsNotEmpty, IsOptional} from 'class-validator';
import {ApiProperty} from '@nestjs/swagger';

export class BookDto {
  @IsNotEmpty()
  @ApiProperty({example: 'Mein Buch', required: true})
  public readonly title: string;

  @IsNotEmpty()
  @ApiProperty({example: 'Max Mustermann', required: true})
  public readonly author: string;

  @IsOptional()
  @ApiProperty({example: 'Inhaltsbeschreibung', required: false})
  public readonly content: string;

  @IsNotEmpty()
  @ApiProperty({example: 'ISBN 978-1-56619-909-4', required: true})
  public readonly isbn: string;

  @IsNotEmpty()
  @ApiProperty({example: 2020, required: true})
  public readonly year: number;

  @IsNotEmpty()
  @ApiProperty({example: 'Deutsch', required: true})
  public readonly language: string;

  @IsOptional()
  @ApiProperty({example: '', required: false})
  public readonly coverImage: string;

  @IsNotEmpty()
  @ApiProperty({example: '5f1947694469b610c0ecef86', required: true})
  public readonly ownerId: string;
}

export class BookUpdateDto {
  @IsOptional()
  @ApiProperty({example: 'Mein Buch', required: false})
  public readonly title: string;

  @IsOptional()
  @ApiProperty({example: 'Max Mustermann', required: false})
  public readonly author: string;

  @IsOptional()
  @ApiProperty({example: 'Inhaltsbeschreibung', required: false})
  public readonly content: string;

  @IsOptional()
  @ApiProperty({example: 'ISBN 978-1-56619-909-4', required: false})
  public readonly isbn: string;

  @IsOptional()
  @ApiProperty({example: 2020, required: false})
  public readonly year: number;

  @IsOptional()
  @ApiProperty({example: 'Deutsch', required: false})
  public readonly language: string;

  @IsOptional()
  @ApiProperty({example: '', required: false})
  public readonly coverImage: string;

  @IsOptional()
  @ApiProperty({example: '5f1947694469b610c0ecef86', required: false})
  public readonly ownerId: string;
}
