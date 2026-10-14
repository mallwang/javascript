export interface MeterReading {
  date: Date;
  meterReading: number;
}

export interface ConsumptionMonth {
  month: number;
  year: number;
  date: Date;
  consumption: number;
}

export interface ConsumptionYear {
  year: number;
  consumption: number;
}

export interface StromMeterReadingsDto {
  meterReadings: Array<MeterReading>;
  unit: string;
  filePath: string;
  sheetName: string;
}

export interface StromConsumptionMonthsDto {
  consumption: Array<ConsumptionMonth>;
  unit: string;
  filePath: string;
  sheetName: string;
}

export interface StromConsumptionYearsDto {
  consumption: Array<ConsumptionYear>;
  unit: string;
  filePath: string;
  sheetName: string;
}
