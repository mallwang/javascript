import {NgModule} from '@angular/core';
import {MeterComponent} from './meter.component';
import {MeterRoutingModule} from './meter-routing.module';
import {SharedModule} from '../../shared/shared.module';
import {StromComponent} from './strom/strom.component';
import {WasserComponent} from './wasser/wasser.component';
import {PelletComponent} from './pellet/pellet.component';

@NgModule({
  imports: [MeterRoutingModule, SharedModule],
  declarations: [MeterComponent, PelletComponent, StromComponent, WasserComponent]
})
export class MeterModule {}
