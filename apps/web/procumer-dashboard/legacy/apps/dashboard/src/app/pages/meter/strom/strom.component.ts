import {Component, OnInit} from '@angular/core';
import {ApiService} from '../../../core/services/api.service';
import {MeterReading, StromMeterReadingsDto} from '@procumer-dashboard/data';

@Component({
  selector: 'procumer-dashboard-meter',
  templateUrl: './strom.component.html',
  styleUrls: ['./strom.component.scss']
})
export class StromComponent implements OnInit {
  public headElements = ['Datum', 'Zählerstand'];
  public stromMeterReadings: Array<MeterReading>;
  public title = 'Zählerstände Strom';
  public unit = '';

  constructor(private readonly apiService: ApiService) {}

  ngOnInit(): void {
    this.apiService.getStromMeterReadings().subscribe((stromMeterReadingsDto: StromMeterReadingsDto) => {
      this.stromMeterReadings = stromMeterReadingsDto.meterReadings.reverse();
      this.unit = stromMeterReadingsDto.unit;
    });
  }
}
