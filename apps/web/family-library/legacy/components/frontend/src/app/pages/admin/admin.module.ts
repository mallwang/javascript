import {NgModule} from '@angular/core';
import {AdminRoutingModule} from './admin-routing.module';
import {SharedModule} from '../../shared/shared.module';
import {AdminComponent} from './admin.component';

@NgModule({
  imports: [AdminRoutingModule, SharedModule],
  declarations: [AdminComponent],
  exports: [AdminComponent]
})
export class AdminModule {}
