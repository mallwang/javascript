import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';

const routes: Routes = [
  {
    path: 'consumption',
    loadChildren: (): any => import('./pages/consumption/consumption.module').then((m) => m.ConsumptionModule)
  },
  {
    path: 'dashboard',
    loadChildren: (): any => import('./pages/dashboard/dashboard.module').then((m) => m.DashboardModule)
  },
  {
    path: 'meter',
    loadChildren: (): any => import('./pages/meter/meter.module').then((m) => m.MeterModule)
  },
  {path: '', redirectTo: 'dashboard', pathMatch: 'full'},
  {
    path: '**',
    redirectTo: 'dashboard'
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule {}
