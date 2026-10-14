import {BrowserModule} from '@angular/platform-browser';
import {NgModule} from '@angular/core';

import {AppRoutingModule} from './app-routing.module';
import {AppComponent} from './app.component';
import {SharedModule} from './shared/shared.module';
import {CoreModule} from './core/core.module';
import {ResetPasswordComponent} from './pages/reset-password/reset-password.component';

@NgModule({
  declarations: [AppComponent, ResetPasswordComponent],
  imports: [
    BrowserModule,
    SharedModule,
    CoreModule,
    // NOTE: The AppRoutingModule must be imported AFTER any other Feature Module (containing its own routing module),
    // NOTE: to avoid the situation that any defined route within a Feature Module gets not found by the main
    // NOTE: App Routing Module. This would cause the effect that for all routes the catch-all route gets used.
    AppRoutingModule
  ],
  bootstrap: [AppComponent]
})
export class AppModule {}
