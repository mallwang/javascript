// Angular
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {NgModule} from '@angular/core';
import {RouterModule} from '@angular/router';
import {MaterialModule} from './material.module';
import {MDBBootstrapModule} from 'angular-bootstrap-md';

/**
 * The Shared Module represents a Feature Module of type 'Widget'.
 * See the Types of feature modules: https://angular.io/guide/module-types
 *
 * It makes components, directives and pipes available to external modules.
 * It DOES NOT declare or export any providers (this will be done via the Core Module).
 */

@NgModule({
  imports: [CommonModule, FormsModule, MaterialModule, MDBBootstrapModule.forRoot(), ReactiveFormsModule, RouterModule],
  declarations: [],
  exports: [CommonModule, FormsModule, MaterialModule, MDBBootstrapModule, ReactiveFormsModule, RouterModule]
})
export class SharedModule {}
