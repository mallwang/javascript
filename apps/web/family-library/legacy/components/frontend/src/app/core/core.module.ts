import {ErrorHandler, NgModule} from '@angular/core';
import {HTTP_INTERCEPTORS, HttpClientModule} from '@angular/common/http';
import {GlobalErrorHandlerService} from './services/global-error-handler.service';
import {GlobalHttpInterceptorService} from './interceptors/global-http-interceptor.service';
import {HttpCancelService} from './services/http-cancel.service';
import {BrowserAnimationsModule} from '@angular/platform-browser/animations';
import {BrowserModule} from '@angular/platform-browser';
import {LocalStorageModule} from 'angular-2-local-storage';
import {GlobalAuthenticationInterceptorService} from './interceptors/global-authentication-interceptor.service';

/**
 * The Core Module represents a Feature Module of type 'Service'.
 * See the Types of feature modules: https://angular.io/guide/module-types
 *
 * It makes ONLY providers available to external modules and should only be imported by the main App Module and NOT by any Feature Module.
 * It DOES NOT declare components, directives or pipes (this will be done via the Shared Module).
 */
@NgModule({
  imports: [
    BrowserAnimationsModule,
    BrowserModule,
    HttpClientModule,
    LocalStorageModule.forRoot({
      prefix: 'family-library',
      storageType: 'localStorage'
    })
  ],
  providers: [
    GlobalErrorHandlerService,
    GlobalHttpInterceptorService,
    HttpCancelService,
    {
      provide: ErrorHandler,
      useClass: GlobalErrorHandlerService
    },
    {
      provide: HTTP_INTERCEPTORS,
      useClass: GlobalAuthenticationInterceptorService,
      multi: true
    },
    {
      provide: HTTP_INTERCEPTORS,
      useClass: GlobalHttpInterceptorService,
      multi: true
    }
  ]
})
export class CoreModule {}
