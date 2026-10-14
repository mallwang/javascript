import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {PelletComponent} from './pellet/pellet.component';
import {StromComponent} from './strom/strom.component';
import {WasserComponent} from './wasser/wasser.component';

const routes: Routes = [
  {path: 'pellet', component: PelletComponent},
  {path: 'strom', component: StromComponent},
  {path: 'wasser', component: WasserComponent},
  {
    path: '**',
    redirectTo: 'strom'
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class MeterRoutingModule {}
