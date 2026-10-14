import {Injectable} from '@angular/core';
import {HttpErrorResponse, HttpEvent, HttpHandler, HttpInterceptor, HttpRequest} from '@angular/common/http';
import {Observable, throwError} from 'rxjs';
import {catchError, retry, takeUntil} from 'rxjs/operators';
import {HttpCancelService} from '../services/http-cancel.service';

@Injectable({
  providedIn: 'root'
})
export class GlobalHttpInterceptorService implements HttpInterceptor {
  public constructor(private httpCancelService: HttpCancelService) {}

  /**
   * Intercept the request and retry it two more times to eliminate temporary errors (like a slow connection) and catch known errors like 401 or 403.
   * @param {HttpRequest<any>} httpRequest: The http request.
   * @param {HttpHandler} next: The next interceptor in the http event chain.
   * @return {Observable<HttpEvent<any>>} The intercepted http event.
   */
  public intercept(httpRequest: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
    // Check the 'application/json' content type and add if not present to PUT, POST and PATCH
    if (['PATCH', 'POST', 'PUT'].includes(httpRequest.method) && !httpRequest.headers.has('Content-Type')) {
      httpRequest = httpRequest.clone({headers: httpRequest.headers.set('Content-Type', 'application/json')});
    }

    // Requests to the oauth endpoint will only be sent once to prevent from multiple exchanging authorization code
    if (httpRequest.url.includes('/oauth')) {
      return next.handle(httpRequest);
    } else {
      return next.handle(httpRequest).pipe(
        // Resolve the http request once the http request has cancelled.
        // Cancellation will be done once the user navigates to another page during an active http-request.
        takeUntil(this.httpCancelService.onCancelPendingRequests()),
        // Retry exactly 2 times (in total 3 http requests) if the first attempt fails
        retry(2),
        // Catch all errors using the 'handleError' function
        catchError(this.handleError)
      );
    }
  }

  /**
   * The global http interceptor for catching http-related errors.
   * @param {HttpErrorResponse} error: The original http error response from Angular.
   * @return {Observable<HttpEvent<any>>} The intercepted http event.
   */
  private handleError(error: HttpErrorResponse): Observable<HttpEvent<any>> {
    // All http errors from outside the Angular applications are from type HttpErrorResponse
    if (error instanceof HttpErrorResponse) {
      if (error.error instanceof ErrorEvent) {
        console.log('Error Event');
      } else {
        console.log(`Error: ${error.status} ${error.statusText}`);
        switch (error.status) {
          // 401 Unauthorized
          case 401:
            // TODO: clarify what to do if user is unauthorized (show message, redirect to login page, etc.)
            console.log('Redirect the user to the login page.');
            // this.router.navigateByUrl('/login');
            break;
          // 403 Forbidden
          case 403:
            // TODO: clarify what to do if user is forbidden to access resource (show message etc.)
            console.log('User is forbidden to access.');
            break;
          default:
            // TODO: clarify what to do with other status codes, especially 5xx
            console.log('Other statuscode received.');
        }
      }
    } else {
      console.log('Something else happened.');
    }

    // Rethrow the error to the service
    return throwError(error);
  }
}
