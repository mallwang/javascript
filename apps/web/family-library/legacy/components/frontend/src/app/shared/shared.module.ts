import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {RouterModule} from '@angular/router';
import {ClarityModule} from '@clr/angular';

/**
 * The Shared Module represents a Feature Module of type 'Widget'.
 * See the Types of feature modules: https://angular.io/guide/module-types
 *
 * It makes components, directives and pipes available to external modules.
 * It DOES NOT declare or export any providers (this will be done via the Core Module).
 */
@NgModule({
  imports: [CommonModule, FormsModule, ReactiveFormsModule, RouterModule],
  declarations: [],
  exports: [ClarityModule, CommonModule, FormsModule, ReactiveFormsModule]
})
export class SharedModule {}
