import {Component} from '@angular/core';

@Component({
  selector: 'procumer-dashboard-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent {
  public title = 'Energy Dashboard';
  public primaryLinks = [
    {
      path: 'dashboard',
      title: 'Dashboard'
    },
    {
      path: 'consumption',
      title: 'Verbrauch'
    }
  ];
  public dropdownLinks = [
    {
      path: 'meter/pellet',
      title: 'Pellet'
    },
    {
      path: 'meter/strom',
      title: 'Strom'
    },
    {
      path: 'meter/wasser',
      title: 'Wasser'
    }
  ];
}
