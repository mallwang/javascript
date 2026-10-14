import {Body, Controller, Delete, Get, Param, Post, Put, UseGuards} from '@nestjs/common';
import {BookService} from './book.service';
import {BookDto, BookUpdateDto} from './dto/book.dto';
import {Book} from './schemas/book.schemas';
import {ApiBearerAuth, ApiOkResponse, ApiOperation, ApiTags} from '@nestjs/swagger';
import JwtAuthGuard from '../auth/guards/jwt-auth.guard';

@ApiBearerAuth()
@ApiTags('Books')
@UseGuards(JwtAuthGuard)
@Controller('books')
export class BookController {
  public constructor(private readonly bookService: BookService) {}

  @Post('')
  @ApiOperation({
    description: 'Create a new book.'
  })
  @ApiOkResponse({
    type: Book,
    description: 'The request was successful.'
  })
  public async create(@Body() bookDto: BookDto): Promise<Book> {
    return this.bookService.create(bookDto);
  }

  @Get('')
  @ApiOperation({
    description: 'Get all books.'
  })
  @ApiOkResponse({
    type: [Book],
    description: 'The request was successful.'
  })
  public async findAll(): Promise<Array<Book>> {
    return this.bookService.findAll();
  }

  @Get(':id')
  @ApiOperation({
    description: 'Get a single book.'
  })
  @ApiOkResponse({
    type: Book,
    description: 'The request was successful.'
  })
  public async findOne(@Param('id') id: string): Promise<Book> {
    return this.bookService.findOne(id);
  }

  @Put(':id')
  @ApiOperation({
    description: 'Update a book.'
  })
  @ApiOkResponse({
    type: BookUpdateDto,
    description: 'The request was successful.'
  })
  public async update(@Param('id') id: string, @Body() bookDto: BookUpdateDto): Promise<Book> {
    return this.bookService.update(id, bookDto);
  }

  @Delete(':id')
  @ApiOperation({
    description: 'Delete a book.'
  })
  @ApiOkResponse({
    type: Book,
    description: 'The request was successful.'
  })
  public async delete(@Param('id') id: string): Promise<Book> {
    return this.bookService.delete(id);
  }
}
