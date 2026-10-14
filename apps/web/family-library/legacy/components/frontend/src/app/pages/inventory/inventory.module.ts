import {NgModule} from '@angular/core';
import {InventoryRoutingModule} from './inventory-routing.module';
import {SharedModule} from '../../shared/shared.module';
import {InventoryComponent} from './inventory.component';
import {InventoryDetailsComponent} from './inventory-details/inventory-details.component';

@NgModule({
  imports: [InventoryRoutingModule, SharedModule],
  declarations: [InventoryComponent, InventoryDetailsComponent],
  exports: [InventoryComponent]
})
export class InventoryModule {}
