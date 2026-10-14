import {Component} from '@angular/core';
import {Title} from '@angular/platform-browser';

@Component({
  templateUrl: './help.component.html',
  styleUrls: ['./help.component.scss']
})
export class HelpComponent {
  public constructor(private readonly titleService: Title) {
    this.titleService.setTitle('Hilfe');
  }
}
