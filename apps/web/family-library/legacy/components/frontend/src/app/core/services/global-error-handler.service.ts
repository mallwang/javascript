import {ErrorHandler, Injectable} from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class GlobalErrorHandlerService implements ErrorHandler {
  /**
   * Some common hints about error handling:
   * - try to handle all known errors in the specific component/service at first
   * - if a error cannot be handled, then re-throw it to this global error handler
   *
   * NOTE: This global error handler will only handle client-side errors and NO http-related errors.
   * NOTE: For handling http-related errors, a custom http interceptor is used (GlobalHttpInterceptorService).
   */

  /**
   * Custom error handler for all errors they could not be handled from the origin component or service.
   * @param {any} error: The error to be thrown by JavaScript or anywhere from the source code.
   */
  public handleError(error: any): void {
    console.error('[GlobalErrorHandlerService] An error occurred:', error.message);
    console.error(error);

    // TODO: Display a information to the user (alert, notification, toast, etc.)
    console.log('[GlobalErrorHandlerService] TODO: display a information to the user');

    // TODO: Pass the client-side error to the backend to be able to know about and resolve it.
    console.log('[GlobalErrorHandlerService] TODO: pass the client-side error to the backend');
  }
}
