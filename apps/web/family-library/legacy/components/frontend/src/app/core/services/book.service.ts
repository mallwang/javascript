import {Injectable} from '@angular/core';
import {HttpClient, HttpParams} from '@angular/common/http';
import {environment} from '../../../environments/environment';
import {Book} from '../../interfaces/book';

@Injectable({
  providedIn: 'root'
})
export class BookService {
  public baseUrl = `${environment.backendBaseUrl}/books`;

  public constructor(private readonly httpClient: HttpClient) {}

  /**
   * Generic request using the http client.
   * @param {string} method: The http method.
   * @param {string} url: The URL.
   * @param {any} data: The body data.
   * @param {HttpParams} params: The HttpParams.
   * @return {Promise<any>}: The promisified request.
   */
  private request(method: string, url: string, data?: any, params?: HttpParams): Promise<any> {
    // TODO: get token
    // const token = await this.oktaAuth.getAccessToken();
    // const result = this.http.request(method, url, {
    //   body: data,
    //   responseType: 'json',
    //   observe: 'body',
    //   headers: {
    //     Authorization: `Bearer ${token}`
    //   }
    // });

    const result = this.httpClient.request(method, url, {
      body: data,
      responseType: 'json',
      observe: 'body',
      params: params
    });

    return new Promise<any>((resolve, reject) => {
      result.subscribe(resolve, reject);
    });
  }

  /**
   * Create a new book.
   * @param {Promise<Book>} book: The book to be created.
   * @return {Promise<Book>} The book which has been created.
   */
  public create(book: Book): Promise<Book> {
    return this.request('POST', `${this.baseUrl}`, book);
  }

  /**
   * Update a book.
   * @param {string} id: The id of the book to be updated.
   * @param {Promise<Book>} book: The book to be updated.
   * @return {Promise<Book>} The book which has been updated.
   */
  public update(id: string, book: Book): Promise<Book> {
    return this.request('PUT', `${this.baseUrl}/${id}`, book);
  }

  /**
   * Find a book by id.
   * @param {string} id: The id of the book.
   * @return {Promise<Book>} The found book.
   */
  public findOne(id: string): Promise<Book> {
    return this.request('GET', `${this.baseUrl}/${id}`);
  }

  /**
   * Find all books.
   * @return {Promise<Array<Book>>} A list of books.
   */
  public findAll(): Promise<Array<Book>> {
    return this.request('GET', `${this.baseUrl}`);
  }

  /**
   * Delete a book based on its id.
   * @param {string} id: The book id.
   * @return {Promise<Book>} The book which has been deleted.
   */
  public delete(id: string): Promise<Book> {
    return this.request('DELETE', `${this.baseUrl}/${id}`);
  }
}
