import {Prop, Schema, SchemaFactory} from '@nestjs/mongoose';
import * as mongoose from 'mongoose';
import {Document} from 'mongoose';
import {ApiProperty} from '@nestjs/swagger';

@Schema()
export class Book extends Document {
  @Prop({required: true})
  @ApiProperty({example: 'Mein Buch', required: false})
  public title: string;

  @Prop({required: true})
  @ApiProperty({example: 'Max Mustermann', required: false})
  public author: string;

  @Prop({required: false})
  @ApiProperty({example: 'Inhaltsbeschreibung', required: false})
  public content: string;

  @Prop({required: true, unique: true})
  @ApiProperty({example: 'ISBN 978-1-56619-909-4', required: false})
  public isbn: string;

  @Prop({required: true})
  @ApiProperty({example: 2020, required: false})
  public year: number;

  @Prop({required: true})
  @ApiProperty({example: 'Deutsch', required: false})
  public language: string;

  @Prop({required: false})
  @ApiProperty({example: '', required: false})
  public coverImage: string;

  @Prop({type: mongoose.Schema.Types.ObjectId, ref: 'User', required: true})
  @ApiProperty({example: '5f1947694469b610c0ecef86', required: false})
  public ownerId: string;

  @Prop({type: Date, default: new Date().getTime()})
  private updatedAt: Date;
}

export const BookSchema = SchemaFactory.createForClass(Book);
