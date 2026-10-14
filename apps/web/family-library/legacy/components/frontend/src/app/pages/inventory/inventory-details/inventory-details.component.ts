import {Component, OnDestroy, OnInit} from '@angular/core';
import {Title} from '@angular/platform-browser';
import {ActivatedRoute, Params} from '@angular/router';
import {BookService} from '../../../core/services/book.service';
import {Book} from '../../../interfaces/book';
import {UserService} from '../../../core/services/user.service';
import {User} from '../../../interfaces/user';
import {Subscription} from 'rxjs';

@Component({
  templateUrl: './inventory-details.component.html',
  styleUrls: ['./inventory-details.component.scss']
})
export class InventoryDetailsComponent implements OnInit, OnDestroy {
  // Other
  public bookId: string;
  public book: Book;
  public owner: User;
  public error: string;
  private routeSubscription: Subscription;

  public constructor(
    private readonly activatedRoute: ActivatedRoute,
    private readonly bookService: BookService,
    private readonly titleService: Title,
    private readonly userService: UserService
  ) {
    this.titleService.setTitle('Buch Details');
  }

  public ngOnInit(): void {
    this.routeSubscription = this.activatedRoute.params.subscribe(async (params: Params) => {
      this.bookId = params.id;

      // Load the current book
      try {
        this.book = await this.bookService.findOne(this.bookId);
        this.titleService.setTitle(this.book.title);
        this.owner = await this.userService.findOne(this.book.ownerId);
      } catch (error) {
        console.error(error);
        this.error = `Das Buch mit der ID "${this.bookId}" wurde nicht gefunden.`;
      }
    });
  }

  public ngOnDestroy(): void {
    this.routeSubscription.unsubscribe();
  }
}
