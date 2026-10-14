import {Component, OnInit, ViewChild} from '@angular/core';
import {
  ConsumptionMonth,
  ConsumptionYear,
  PelletbetankungDto,
  PelletverbrauchDto,
  StromConsumptionMonthsDto,
  StromConsumptionYearsDto,
  WasserverbrauchDto
} from '@procumer-dashboard/data';
import {MatSort} from '@angular/material/sort';
import {ApiService} from '../../core/services/api.service';
import {MatTableDataSource} from '@angular/material/table';

@Component({
  selector: 'procumer-dashboard-consumption',
  templateUrl: './consumption.component.html',
  styleUrls: ['./consumption.component.scss']
})
export class ConsumptionComponent implements OnInit {
  public displayedColumnsConsumptionYears: string[] = ['year', 'consumption'];
  public displayedColumnsConsumptionMonths: string[] = ['date', 'consumption'];
  public title = 'Procumer Dashboard';
  public pelletbetankung: PelletbetankungDto;
  public pelletverbrauch: PelletverbrauchDto;
  public stromConsumptionYears: StromConsumptionYearsDto;
  public stromConsumptionMonths: StromConsumptionMonthsDto;
  public wasserverbrauch: WasserverbrauchDto;
  public dataSourceMeterReadings;
  public dataSourceConsumptionYears;
  public dataSourceConsumptionMonths;
  @ViewChild(MatSort) sort: MatSort;

  constructor(private readonly apiService: ApiService) {}

  ngOnInit() {
    // this.apiService.getPelletBetankung().subscribe((pelletbetankungDto: PelletbetankungDto) => {
    //   this.pelletbetankung = pelletbetankungDto;
    // });
    // this.apiService.getPelletVerbrauch().subscribe((pelletverbrauchDto: PelletverbrauchDto) => {
    //   this.pelletverbrauch = pelletverbrauchDto;
    // });
    this.apiService.getStromConsumptionYears().subscribe((stromConsumptionYearsDto: StromConsumptionYearsDto) => {
      this.stromConsumptionYears = stromConsumptionYearsDto;
      this.dataSourceConsumptionYears = new MatTableDataSource<ConsumptionYear>(stromConsumptionYearsDto.consumption);
      this.dataSourceConsumptionYears.sort = this.sort;
    });
    this.apiService.getStromConsumptionMonths().subscribe((stromConsumptionMonthsDto: StromConsumptionMonthsDto) => {
      this.stromConsumptionMonths = stromConsumptionMonthsDto;
      this.dataSourceConsumptionMonths = new MatTableDataSource<ConsumptionMonth>(
        stromConsumptionMonthsDto.consumption
      );
    });
    // this.apiService.getWasserVerbrauch().subscribe((wasserverbrauchDto: WasserverbrauchDto) => {
    //   this.wasserverbrauch = wasserverbrauchDto;
    // });
  }
}
