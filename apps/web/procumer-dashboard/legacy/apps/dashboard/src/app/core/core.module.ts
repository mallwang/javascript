// Angular
import {ErrorHandler, LOCALE_ID, NgModule} from '@angular/core';
import {HTTP_INTERCEPTORS, HttpClientModule} from '@angular/common/http';
import {registerLocaleData} from '@angular/common';
import localeDe from '@angular/common/locales/de';
// Third-party
import {LocalStorageModule} from 'angular-2-local-storage';
// Interceptors
import {GlobalErrorHandlerService} from './services/global-error-handler.service';
import {GlobalHttpInterceptorService} from './interceptors/global-http-interceptor.service';
// Services
import {ApiService} from './services/api.service';
import {UtilsService} from './services/utils.service';

// Register the language-specific locale
registerLocaleData(localeDe, 'de');

/**
 * The Core Module represents a Feature Module of type 'Service'.
 * See the Types of feature modules: https://angular.io/guide/module-types
 *
 * It makes ONLY providers available to external modules and should only be imported by the main App Module and NOT by any Feature Module.
 * It DOES NOT declare components, directives or pipes (this will be done via the Shared Module).
 */
@NgModule({
  imports: [
    HttpClientModule,
    LocalStorageModule.forRoot({
      prefix: 'procumer-dashboard',
      storageType: 'localStorage'
    })
  ],
  providers: [
    ApiService,
    GlobalErrorHandlerService,
    GlobalHttpInterceptorService,
    UtilsService,
    {
      provide: ErrorHandler,
      useClass: GlobalErrorHandlerService
    },
    {
      provide: HTTP_INTERCEPTORS,
      useClass: GlobalHttpInterceptorService,
      multi: true
    },
    {
      provide: LOCALE_ID,
      useValue: 'de'
    }
  ]
})
export class CoreModule {}
