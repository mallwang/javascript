import {Component, OnInit} from '@angular/core';
import {Book} from '../../interfaces/book';
import {ClrDatagridSortOrder} from '@clr/angular';
import {AuthService} from '../../core/services/auth.service';
import {Title} from '@angular/platform-browser';
import {BookService} from '../../core/services/book.service';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {Router} from '@angular/router';

type MODE = 'CREATE' | 'EDIT';

@Component({
  templateUrl: './inventory.component.html',
  styleUrls: ['./inventory.component.scss']
})
export class InventoryComponent implements OnInit {
  // Datagrid parameters
  public books: Array<Book> = [];
  public descSort = ClrDatagridSortOrder.ASC;

  // Deletion confirmation modal
  public openDeletionConfirmation = false;
  public bookToBeDeleted: Book = null;
  public deleteBookError = null;

  // Create book modal and form
  public openBook = false;
  public newBookForm = new FormGroup({});
  public bookError = null;
  public mode: 'CREATE' | 'EDIT' = 'CREATE';

  // Other
  public currentId;
  public loading = true;

  public constructor(
    private readonly authService: AuthService,
    private readonly bookService: BookService,
    private readonly titleService: Title,
    private readonly router: Router
  ) {
    this.titleService.setTitle('Bücherregal');
  }

  public async ngOnInit(): Promise<any> {
    this.books = await this.bookService.findAll();
    this.currentId = this.authService.id;
    this.loading = false;
  }

  /**
   * Create a new book form.
   */
  private createNewBookForm(): void {
    this.newBookForm = new FormGroup({
      title: new FormControl('', Validators.required),
      author: new FormControl('', Validators.required),
      isbn: new FormControl('', [
        Validators.pattern(
          '^(?:ISBN(?:-1[03])?:? )?(?=[0-9X]{10}$|(?=(?:[0-9]+[- ]){3})[- 0-9X]{13}$|97[89][0-9]{10}$|(?=(?:[0-9]+[- ]){4})[- 0-9]{17}$)(?:97[89][- ]?)?[0-9]{1,5}[- ]?[0-9]+[- ]?[0-9]+[- ]?[0-9X]$'
        ),
        Validators.required
      ]),
      year: new FormControl('', [Validators.required, Validators.min(1900), Validators.max(new Date().getFullYear())]),
      language: new FormControl('', Validators.required),
      content: new FormControl({value: null, disabled: true}),
      coverImage: new FormControl({value: null, disabled: true}),
      ownerId: new FormControl({value: this.currentId, disabled: true}, Validators.required)
    });
  }

  /**
   * Create a edit book form.
   * @param {Book} book: The book to be edited.
   */
  private createEditBookForm(book: Book): void {
    this.newBookForm = new FormGroup({
      _id: new FormControl(book._id),
      title: new FormControl(book.title, Validators.required),
      author: new FormControl(book.author, Validators.required),
      isbn: new FormControl(book.isbn, [
        Validators.pattern(
          '^(?:ISBN(?:-1[03])?:? )?(?=[0-9X]{10}$|(?=(?:[0-9]+[- ]){3})[- 0-9X]{13}$|97[89][0-9]{10}$|(?=(?:[0-9]+[- ]){4})[- 0-9]{17}$)(?:97[89][- ]?)?[0-9]{1,5}[- ]?[0-9]+[- ]?[0-9]+[- ]?[0-9X]$'
        ),
        Validators.required
      ]),
      year: new FormControl(book.year, [
        Validators.required,
        Validators.min(1900),
        Validators.max(new Date().getFullYear())
      ]),
      language: new FormControl(book.language, Validators.required),
      content: new FormControl({value: book.content, disabled: true}),
      coverImage: new FormControl({value: book.coverImage, disabled: true}),
      ownerId: new FormControl({value: this.currentId, disabled: true}, Validators.required)
    });
  }

  /**
   * Open the book deletion confirmation modal.
   * @param {Book} book: The book to be deleted.
   */
  public openDeletionConfirmationModal(book: Book): void {
    this.bookToBeDeleted = book;
    this.openDeletionConfirmation = true;
  }

  /**
   * Open the book creation modal.
   * @param {MODE} mode: The modal mode.
   * @param {Book} book: The book to be edited.
   */
  public openBookModal(mode: MODE, book?: Book): void {
    this.mode = mode;

    if (mode === 'CREATE') {
      this.createNewBookForm();
    } else if (mode === 'EDIT') {
      this.createEditBookForm(book);
    }

    this.openBook = true;
  }

  /**
   * Open the book details page.
   * @param {string} bookId: The id of the book.
   */
  public openBookDetails(bookId: string): void {
    this.router.navigate(['buecherregal', bookId]);
  }

  /**
   * Save the book form.
   */
  public async saveBook(): Promise<any> {
    // Reset the alerts
    this.bookError = null;

    try {
      // Save book form
      const newBook: Book = this.newBookForm.getRawValue();

      let savedBook;
      if (this.mode === 'CREATE') {
        savedBook = await this.bookService.create(newBook);
      } else if (this.mode === 'EDIT') {
        await this.bookService.update(newBook._id, newBook);
      }

      if (this.mode === 'CREATE') {
        // Add book to the list of books
        this.books.push(savedBook);
      } else if (this.mode === 'EDIT') {
        // Replace the existing book in the list of books
        const bookIndex = this.books.findIndex((book: Book) => {
          return book._id === newBook._id;
        });
        this.books[bookIndex] = newBook;
      }

      this.openBook = false;

      this.newBookForm.reset();
    } catch (error) {
      console.error(error.error.error);
      this.bookError = `Es ist ein Fehler aufgetreten: ${error.error.error}`;
    }
  }

  /**
   * Delete a book.
   */
  public async deleteBook(): Promise<any> {
    try {
      // Delete a book
      const deletedBook = await this.bookService.delete(this.bookToBeDeleted._id);

      // Remove the deleted book from the books list
      const bookIndex = this.books.findIndex((book: Book) => {
        return book._id === deletedBook._id;
      });
      this.books.splice(bookIndex, 1);

      // Close the modal
      this.openDeletionConfirmation = false;
    } catch (error) {
      console.error(error.error.error);
      this.deleteBookError = `Es ist ein Fehler aufgetreten: ${error.error.error}`;
    }
  }
}
