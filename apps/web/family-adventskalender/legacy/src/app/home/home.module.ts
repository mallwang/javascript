import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {IonicModule} from '@ionic/angular';
import {FormsModule} from '@angular/forms';
import {RouterModule, Routes} from '@angular/router';
import {HomePage} from './home.page';
import {ImageModalComponent} from '../image-modal/image-modal.component';
import {QrCodeModalComponent} from '../qr-code-modal/qr-code-modal.component';
import {CanDeactivateGuard} from '../can-deactivate.guard';

const routes: Routes = [
  {
    path: '',
    component: HomePage,
    canDeactivate: [CanDeactivateGuard]
  }
];

@NgModule({
  imports: [CommonModule, FormsModule, IonicModule, RouterModule.forChild(routes)],
  declarations: [HomePage, ImageModalComponent, QrCodeModalComponent]
})
export class HomePageModule {}
