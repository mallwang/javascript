import {Component, OnInit} from '@angular/core';
import {SwUpdate} from '@angular/service-worker';
import {AlertController, Platform} from '@ionic/angular';

@Component({
  selector: 'app-root',
  templateUrl: 'app.component.html',
  styleUrls: ['app.component.scss']
})
export class AppComponent implements OnInit {
  public constructor(
    private readonly alertController: AlertController,
    private readonly platform: Platform,
    private readonly swUpdate: SwUpdate
  ) {
    this.setupUpdates();
  }

  public ngOnInit(): void {
    this.platform.ready().then(() => {
      // NOTE: completely deactivate the hardware button actions
      window.addEventListener('load', function () {
        window.history.pushState({}, '');
      });
      window.addEventListener('popstate', function () {
        window.history.pushState({}, '');
      });
    });
  }

  /**
   * Check if there is a update available.
   * If so, it displays an alert and asks the user to update the app.
   */
  private setupUpdates(): void {
    // update available
    this.swUpdate.available.subscribe(() => {
      console.log('[AppComponent] discovered updates');

      // download update
      this.swUpdate.activateUpdate().then(async () => {
        console.log('[AppComponent] downloaded updates');

        const alert = await this.alertController.create({
          message:
            'Die Anwendung wurde soeben im Hintergrund aktualisiert. Damit die Änderungen wirksam werden muss die Applikation neu geladen werden.',
          buttons: [
            {
              text: 'Ok',
              handler: () => {
                location.reload();
              }
            }
          ]
        });
        await alert.present();
      });
    });

    this.swUpdate.checkForUpdate();
  }
}
