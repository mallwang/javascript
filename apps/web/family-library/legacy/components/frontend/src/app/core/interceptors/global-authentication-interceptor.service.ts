import {Injectable} from '@angular/core';
import {HttpEvent, HttpHandler, HttpInterceptor, HttpRequest} from '@angular/common/http';
import {Observable} from 'rxjs';
import {AuthService} from '../services/auth.service';

@Injectable({
  providedIn: 'root'
})
export class GlobalAuthenticationInterceptorService implements HttpInterceptor {
  public constructor(private readonly authService: AuthService) {}

  /**
   * Intercept the request and add the JWT to the header.
   * @param {HttpRequest<any>} request: The http request.
   * @param {HttpHandler} next: The next interceptor in the http event chain.
   * @return {Observable<HttpEvent<any>>} The intercepted http event.
   *
   * TODO: The JWT has only be added so some requests (e.g. not for the request to issue the token itself)
   */
  public intercept(request: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
    // Check the 'application/json' content type and add if not present
    if (!request.headers.has('Content-Type')) {
      request = request.clone({headers: request.headers.set('Content-Type', 'application/json')});
    }

    // Check if the user is authenticated and add the JWT to the header
    if (this.authService.isAuthenticated()) {
      request = request.clone({
        headers: request.headers.set('Authorization', `Bearer ${this.authService.getToken()}`)
      });
    }

    // Pass the request to the next interceptor in the chain.
    return next.handle(request);
  }
}
