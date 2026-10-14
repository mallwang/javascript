import {Component, HostListener} from '@angular/core';
import {ModalController, ToastController} from '@ionic/angular';

@Component({
  selector: 'app-image-modal',
  templateUrl: './qr-code-modal.component.html',
  styleUrls: ['./qr-code-modal.component.scss']
})
export class QrCodeModalComponent {
  public title = 'Teile diese App';

  public constructor(
    private readonly modalController: ModalController,
    private readonly toastController: ToastController
  ) {}

  /**
   * Closes the modal.
   * @return {Promise<any>} Wait until the modal has been dismissed.
   */
  public async closeModal(): Promise<any> {
    const modal = await this.modalController.getTop();
    await modal.dismiss();
  }

  /**
   * Event handler for clicking on the qr code to show a toast message.
   * @return {Promise<any>} Wait until the toast has been displayed.
   */
  public async showToast(): Promise<any> {
    const toast = await this.toastController.create({
      message: 'Dieser QR Code kann geteilt werden.',
      duration: 3000
    });
    await toast.present();
  }

  /**
   * Event handler for clicking on the hardware back button to close the current modal.
   * @return {Promise<any>} Wait until the modal has been dismissed.
   */
  @HostListener('document:ionBackButton')
  public async overrideHardwareBackAction(): Promise<any> {
    const modal = await this.modalController.getTop();
    await modal.dismiss();
  }
}
