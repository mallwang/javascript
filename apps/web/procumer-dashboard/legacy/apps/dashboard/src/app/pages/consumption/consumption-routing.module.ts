import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {ConsumptionComponent} from './consumption.component';

const routes: Routes = [{path: '', component: ConsumptionComponent}];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ConsumptionRoutingModule {}
