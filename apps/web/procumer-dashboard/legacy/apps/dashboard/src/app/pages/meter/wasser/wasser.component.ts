import {Component, OnInit} from '@angular/core';
import {ApiService} from '../../../core/services/api.service';
import {StromMeterReadingsDto} from '@procumer-dashboard/data';

@Component({
  selector: 'procumer-dashboard-meter',
  templateUrl: './wasser.component.html',
  styleUrls: ['./wasser.component.scss']
})
export class WasserComponent implements OnInit {
  public headElements = ['Datum', 'Zählerstand'];
  public stromMeterReadings: StromMeterReadingsDto;
  public title = 'Zählerstände Wasser';

  constructor(private readonly apiService: ApiService) {}

  ngOnInit(): void {
    this.apiService.getStromMeterReadings().subscribe((stromMeterReadingsDto: StromMeterReadingsDto) => {
      this.stromMeterReadings = stromMeterReadingsDto;
    });
  }
}
