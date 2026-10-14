import {Component} from '@angular/core';
import {AlertController, ModalController, ToastController} from '@ionic/angular';
import {ImageModalComponent} from '../image-modal/image-modal.component';
import {QrCodeModalComponent} from '../qr-code-modal/qr-code-modal.component';
import {environment} from '../../environments/environment';

@Component({
  selector: 'app-home',
  templateUrl: 'home.page.html',
  styleUrls: ['home.page.scss']
})
export class HomePage {
  public doors: Array<number> = [];
  private todayDate: number = new Date().getDate();
  private todayMonth: number = new Date().getMonth();
  private requiredMonth = environment.requiredMonth; // 0 = Januar, 11 = Dezember

  public constructor(
    private readonly toastController: ToastController,
    private readonly alertController: AlertController,
    private readonly modalController: ModalController
  ) {
    this.doors = this.shuffle([...Array(24).keys()]);
  }

  /**
   * Event handler for clicking on the div which represents a present.
   * @param {number} index: The index which has been clicked.
   * @return {Promise<void>} Wait until a toast or the image modal gets displayed.
   */
  public async openDoor(index: number): Promise<void> {
    // Check if the door is currently locked
    if (this.isLocked(index)) {
      let toast;

      if (this.todayMonth !== this.requiredMonth) {
        toast = await this.toastController.create({
          header: `Du musst bis ${this.getMonthText(this.requiredMonth)} warten ehe due das Päckchen öffnen darfst...`,
          duration: 3000
        });
      } else {
        toast = await this.toastController.create({
          header: 'Nur noch ein paar Tage schlafen...',
          duration: 3000
        });
      }

      await toast.present();
    } else {
      const imageModal = await this.modalController.create({
        component: ImageModalComponent,
        componentProps: {day: index + 1}
      });
      await imageModal.present();
    }
  }

  /**
   * Checks whether a specific day can be opened or is locked.
   * @param {number} day: The day which should be opened.
   * @return {boolean} Whether the day is locked and cannot be opened.
   */
  public isLocked(day: number): boolean {
    const correct = this.todayMonth === this.requiredMonth && this.todayDate >= day + 1;
    return !correct;
  }

  /**
   * Displays a modal with an qr code for sharing.
   * @return {Promise<void>} A modal containing the qr code.
   */
  public async displayQrCode(): Promise<void> {
    const imageModal = await this.modalController.create({
      component: QrCodeModalComponent
    });
    await imageModal.present();
  }

  /**
   * Shuffles a given list of numbers.
   * @param {Array<number>} array: A list of numbers.
   * @return {Array<number>} The shuffled list of numbers.
   * @private
   */
  private shuffle(array: Array<number>): Array<number> {
    array.sort(() => Math.random() - 0.5);
    return array;
  }

  /**
   * Event handler for manually refreshing the date.
   eslint-disable-next-line valid-jsdoc
   * @param {any} event: The refresher event.
   * @return {Promise<any>} When the refresh is completed.
   */
  // eslint-disable-next-line @typescript-eslint/explicit-module-boundary-types
  public async refreshDate(event: any): Promise<any> {
    this.todayDate = new Date().getDate();
    this.todayMonth = new Date().getMonth();

    setTimeout(() => {
      event.target.complete();
    }, 1000);
  }

  /**
   * Prevent the app from navigating to the previous page if a modal is currently open in the foreground.
   * Using the browser back button or mobile device hardware back button will at first close the modal.
   * @return {Promise<boolean>}
   */
  public canDeactivate(): Promise<boolean> {
    // eslint-disable-next-line no-async-promise-executor
    return new Promise(async (resolve) => {
      const modal = await this.modalController.getTop();
      if (modal) {
        await modal.dismiss();
        resolve(false);
      }
      resolve(true);
    });
  }

  /**
   * Get the name of the month based on the month number.
   * @param {string} month: The month number.
   * @return {string} The name of the month.
   * @private
   */
  private getMonthText(month: number): string {
    let monthText = '';
    switch (month) {
      case 0:
        monthText = 'Januar';
        break;
      case 1:
        monthText = 'Februar';
        break;
      case 2:
        monthText = 'März';
        break;
      case 3:
        monthText = 'April';
        break;
      case 4:
        monthText = 'Mai';
        break;
      case 5:
        monthText = 'Juni';
        break;
      case 6:
        monthText = 'Juli';
        break;
      case 7:
        monthText = 'August';
        break;
      case 8:
        monthText = 'September';
        break;
      case 9:
        monthText = 'Oktober';
        break;
      case 10:
        monthText = 'November';
        break;
      case 11:
        monthText = 'Dezember';
        break;
      default:
        monthText = 'zum nächsten Monat';
        break;
    }
    return monthText;
  }
}
