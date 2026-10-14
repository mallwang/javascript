import {Injectable} from '@nestjs/common';
import {InjectModel} from '@nestjs/mongoose';
import {Book} from './schemas/book.schemas';
import {Model} from 'mongoose';
import {BookDto, BookUpdateDto} from './dto/book.dto';

@Injectable()
export class BookService {
  public constructor(@InjectModel(Book.name) private readonly bookModel: Model<Book>) {}

  /**
   * Create a new book.
   * @param {BookDto} bookDto: The book to be created.
   * @return {Promise<Book>}: The created book object.
   */
  public async create(bookDto: BookDto): Promise<Book> {
    const createdBook = new this.bookModel(bookDto);
    return createdBook.save();
  }

  /**
   * Find all books.
   * @return {Promise<Array<Book>>}: A list of book objects.
   */
  public async findAll(): Promise<Array<Book>> {
    return await this.bookModel.find().exec();
  }

  /**
   * Find a single book.
   * @param {string} id: The id of the book.
   * @return {Book}: The book object.
   */
  public async findOne(id: string): Promise<Book> {
    return await this.bookModel.findById(id).exec();
  }

  /**
   * Update a book.
   * @param {string} id: The id of the book to be updated.
   * @param {BookUpdateDto} bookDto: The book to be updated.
   * @return {Book}: The updated book object.
   */
  public async update(id: string, bookDto: BookUpdateDto): Promise<Book> {
    return await this.bookModel.findByIdAndUpdate(id, bookDto, {new: true});
  }

  /**
   * Delete a book.
   * @param {string} id: The id of the book to be deleted.
   * @return {Book}: The deleted book object.
   */
  public async delete(id: string): Promise<Book> {
    return await this.bookModel.findByIdAndRemove(id);
  }
}
