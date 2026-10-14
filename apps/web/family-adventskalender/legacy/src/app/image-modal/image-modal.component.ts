import {Component, HostListener} from '@angular/core';
import {ModalController, NavParams} from '@ionic/angular';

@Component({
  selector: 'app-image-modal',
  templateUrl: './image-modal.component.html',
  styleUrls: ['./image-modal.component.scss']
})
export class ImageModalComponent {
  public day: number;
  public title: string;

  public constructor(private readonly modalController: ModalController, private readonly navParams: NavParams) {
    this.day = navParams.get('day');

    // TODO: use individual title based on the day
    this.title = `Tag ${this.day}`;
  }

  /**
   * Closes the modal.
   * @return {Promise<any>} Wait until the modal has been dismissed.
   */
  public async closeModal(): Promise<any> {
    const modal = await this.modalController.getTop();
    await modal.dismiss();
  }

  /**
   * Event handler for clicking on the image for opening it into full screen.
   */
  public openFullScreen(): void {
    window.open(`../../assets/images/full/${this.day}.jpg`);
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
