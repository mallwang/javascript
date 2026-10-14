import {Injectable} from '@angular/core';
import {HttpClient, HttpParams} from '@angular/common/http';
import {User} from '../../interfaces/user';
import {environment} from '../../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class UserService {
  public baseUrl = `${environment.backendBaseUrl}/users`;

  public constructor(private readonly httpClient: HttpClient) {}

  /**
   * Generic request using the http client.
   * @param {string} method: The http method.
   * @param {string} url: The URL to be called.
   * @param {any} data: The body data.
   * @param {HttpParams} params: The HttpParams.
   * @return {Promise<any>}: The promisified request.
   */
  private request(method: string, url: string, data?: any, params?: HttpParams): Promise<any> {
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
   * Update a user.
   * @param {string} id: The id of the user to be updated.
   * @param {Promise<User>} user: The user to be updated.
   * @return {Promise<User>} The user which has been updated.
   */
  public update(id: string, user: User): Promise<User> {
    return this.request('PUT', `${this.baseUrl}/${id}`, user);
  }

  /**
   * Find a user by id.
   * @param {string} id: The id of the user.
   * @return {Promise<User>} The found user.
   */
  public findOne(id: string): Promise<User> {
    return this.request('GET', `${this.baseUrl}/${id}`);
  }

  /**
   * Find all users.
   * @return {Promise<Array<User>>} A list of users.
   */
  public findAll(): Promise<Array<User>> {
    return this.request('GET', `${this.baseUrl}`);
  }

  /**
   * Delete a user based on its id.
   * @param {string} id: The user id.
   * @return {Promise<User>} The user which has been deleted.
   */
  public delete(id: string): Promise<User> {
    return this.request('DELETE', `${this.baseUrl}/${id}`);
  }
}
