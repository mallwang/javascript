import {NgModule} from '@angular/core';
import {ConsumptionComponent} from './consumption.component';
import {SharedModule} from '../../shared/shared.module';
import {ConsumptionRoutingModule} from './consumption-routing.module';

@NgModule({
  imports: [ConsumptionRoutingModule, SharedModule],
  declarations: [ConsumptionComponent]
})
export class ConsumptionModule {}
