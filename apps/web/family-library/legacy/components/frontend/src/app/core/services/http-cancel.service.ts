import {Injectable} from '@angular/core';
import {Observable, Subject} from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class HttpCancelService {
  // The Subject is used by the global http interceptor to cancel all pending http requests once a new value emits.
  private pendingHttpRequests$ = new Subject<void>();

  /**
   * Emits a event to all subscribers of the pendingHttpRequests$ Observable.
   */
  public cancelPendingRequests(): void {
    this.pendingHttpRequests$.next();
  }

  /**
   * Returns the Subject packaged as an Observable to be able to subscribe to.
   * @return {Observable<void>} The Observable used for indicating that pending requests should be cancelled.
   */
  public onCancelPendingRequests(): Observable<void> {
    return this.pendingHttpRequests$.asObservable();
  }
}
