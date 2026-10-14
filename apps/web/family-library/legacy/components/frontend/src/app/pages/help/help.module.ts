import {NgModule} from '@angular/core';
import {HelpRoutingModule} from './help-routing.module';
import {SharedModule} from '../../shared/shared.module';
import {HelpComponent} from './help.component';

@NgModule({
  imports: [HelpRoutingModule, SharedModule],
  declarations: [HelpComponent],
  exports: [HelpComponent]
})
export class HelpModule {}
