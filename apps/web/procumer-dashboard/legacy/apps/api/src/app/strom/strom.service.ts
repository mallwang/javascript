import {Injectable} from '@angular/core';
import {ConfigService} from '../services/config/config.service';
import {FileService} from '../services/file/file.service';
import {
  ConsumptionMonth,
  ConsumptionYear,
  MeterReading,
  StromConsumptionMonthsDto,
  StromConsumptionYearsDto,
  StromMeterReadingsDto
} from '@procumer-dashboard/data';

@Injectable({
  providedIn: 'root'
})
export class StromService {
  private readonly cellDate = 0;
  private readonly cellMeterReading = 1;
  private readonly unit = 'kWh';
  private readonly filePath: string;
  private readonly sheetName: string;

  constructor(private readonly configService: ConfigService, private readonly fileService: FileService) {
    this.filePath = this.configService.get('FILE_PATH');
    this.sheetName = this.configService.get('SHEET_NAME_STROM');
  }

  public async getMeterReadings(): Promise<StromMeterReadingsDto> {
    // Read and parse the sheet
    const data = await this.fileService.read(this.sheetName);

    // Create a list of meterReadings
    const stromMeterReadings: Array<MeterReading> = [];

    // Iterate over all rows and create meterReading
    data.forEach((row) => {
      // Only parse rows beginning with a date
      if (row[this.cellDate] && row[this.cellDate] instanceof Date) {
        const date = new Date(row[this.cellDate]);
        const meterReading = row[this.cellMeterReading];

        const stromMeterReading: MeterReading = {
          date: date,
          meterReading: Math.round(meterReading * 100) / 100
        };

        stromMeterReadings.push(stromMeterReading);
      }
    });

    return {
      meterReadings: stromMeterReadings,
      unit: this.unit,
      filePath: this.filePath,
      sheetName: this.sheetName
    };
  }

  public async getConsumptionYears(): Promise<StromConsumptionYearsDto> {
    // Get all meter readings
    const data = await this.getMeterReadings();
    const meterReadings = data.meterReadings;

    // Create helper variables
    const strom = {};
    const labels = [];

    // Iterate over each meterReading anc calculate yearly consumption
    meterReadings.forEach((meterReading: MeterReading, index: number) => {
      const year = meterReading.date.getFullYear();
      const consumption = Math.round(meterReading.meterReading - meterReadings[index - 1]?.meterReading) || 0;

      // add monthly consumption to existing value
      if (strom[year]) {
        strom[year] = strom[year] + consumption;
      } else {
        // create a new consumption year
        labels.push(year);
        strom[year] = {};
        strom[year] = consumption;
      }
    });

    // REFACTOR
    const stromConsumptionYears: ConsumptionYear[] = Object.keys(strom).map(
      (key: string): ConsumptionYear => {
        return {
          year: Number(key),
          consumption: strom[key]
        };
      }
    );

    return {
      consumption: stromConsumptionYears,
      unit: this.unit,
      filePath: this.filePath,
      sheetName: this.sheetName
    };
  }

  public async getConsumptionMonths(): Promise<StromConsumptionMonthsDto> {
    // Get all meter readings
    const data = await this.getMeterReadings();
    const meterReadings = data.meterReadings;

    // Create list of monthly consumptions
    const stromConsumptionMonths: ConsumptionMonth[] = [];

    // Iterate over each meterReading anc calculate monthly consumption
    meterReadings.forEach((meterReading: MeterReading, index: number) => {
      const year = meterReading.date.getFullYear();
      const month = meterReading.date.getMonth() + 1;
      const consumption = Math.round(meterReading.meterReading - meterReadings[index - 1]?.meterReading) || 0;

      const consumptionMonth: ConsumptionMonth = {
        month,
        year,
        date: meterReading.date,
        consumption
      };

      stromConsumptionMonths.push(consumptionMonth);
    });

    return {
      consumption: stromConsumptionMonths,
      unit: this.unit,
      filePath: this.filePath,
      sheetName: this.sheetName
    };
  }
}
