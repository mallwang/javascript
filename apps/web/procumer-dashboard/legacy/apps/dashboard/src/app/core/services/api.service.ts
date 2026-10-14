import {Injectable} from '@angular/core';
import {Observable} from 'rxjs';
import {
  PelletbetankungDto,
  PelletverbrauchDto,
  StromConsumptionMonthsDto,
  StromConsumptionYearsDto,
  StromMeterReadingsDto,
  WasserverbrauchDto
} from '@procumer-dashboard/data';
import {HttpClient} from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class ApiService {
  private baseUrl = '/api/v1';
  constructor(private readonly http: HttpClient) {}

  public getPelletBetankung(): Observable<PelletbetankungDto> {
    return this.http.get<PelletbetankungDto>(`${this.baseUrl}/pellet/betankung`);
  }

  public getPelletVerbrauch(): Observable<PelletverbrauchDto> {
    return this.http.get<PelletverbrauchDto>(`${this.baseUrl}/pellet/verbrauch`);
  }

  public getStromMeterReadings(): Observable<StromMeterReadingsDto> {
    return this.http.get<StromMeterReadingsDto>(`${this.baseUrl}/strom/meterReadings`);
  }

  public getStromConsumptionMonths(): Observable<StromConsumptionMonthsDto> {
    return this.http.get<StromConsumptionMonthsDto>(`${this.baseUrl}/strom/consumption/months`);
  }

  public getStromConsumptionYears(): Observable<StromConsumptionYearsDto> {
    return this.http.get<StromConsumptionYearsDto>(`${this.baseUrl}/strom/consumption/years`);
  }

  public getWasserVerbrauch(): Observable<WasserverbrauchDto> {
    return this.http.get<WasserverbrauchDto>(`${this.baseUrl}/wasser/verbrauch`);
  }
}
