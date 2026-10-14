import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {AuthGuard} from './core/guards/auth.guard';
import {AdminGuard} from './core/guards/admin.guard';
import {ResetPasswordComponent} from './pages/reset-password/reset-password.component';

const routes: Routes = [
  {
    path: 'administration',
    loadChildren: () => import('./pages/admin/admin.module').then((m) => m.AdminModule),
    canActivate: [AuthGuard, AdminGuard]
  },
  {path: 'hilfe', loadChildren: () => import('./pages/help/help.module').then((m) => m.HelpModule)},
  {
    path: 'buecherregal',
    loadChildren: () => import('./pages/inventory/inventory.module').then((m) => m.InventoryModule),
    canActivate: [AuthGuard]
  },
  {path: 'login', loadChildren: () => import('./pages/login/login.module').then((m) => m.LoginModule)},
  {path: 'reset-password', component: ResetPasswordComponent},
  {path: '', redirectTo: 'login', pathMatch: 'full'},
  {
    path: '**',
    redirectTo: 'login'
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule {}
