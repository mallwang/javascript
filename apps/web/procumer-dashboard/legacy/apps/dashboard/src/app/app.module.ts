import {BrowserModule} from '@angular/platform-browser';
import {LOCALE_ID, NgModule} from '@angular/core';

import {AppComponent} from './app.component';
import {BrowserAnimationsModule} from '@angular/platform-browser/animations';
import {ApiService} from './core/services/api.service';
import {SharedModule} from './shared/shared.module';

import {registerLocaleData} from '@angular/common';
import localeDe from '@angular/common/locales/de';
import {CoreModule} from './core/core.module';
import {AppRoutingModule} from './app-routing.module';

registerLocaleData(localeDe, 'de');

@NgModule({
  declarations: [AppComponent],
  imports: [
    BrowserModule,
    BrowserAnimationsModule,
    SharedModule,
    CoreModule,
    // NOTE: The AppRoutingModule must be imported AFTER any other Feature Module (containing its own routing module),
    // NOTE: to avoid the situation that any defined route within a Feature Module gets not found by the main
    // NOTE: App Routing Module. This would cause the effect that for all routes the catch-all route gets used.
    AppRoutingModule
  ],
  providers: [{provide: LOCALE_ID, useValue: 'de'}, ApiService],
  bootstrap: [AppComponent]
})
export class AppModule {}
