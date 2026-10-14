export interface Pelletbetankung {
  date: Date;
  amountInTons: number;
  pricePerTon: number;
  currency: string;
  potHoursTotal: number;
  consumptionTotalKg: number;
}

export interface Pelletverbrauch {
  date: Date;
  potHoursTotal: number;
  consumptionTotalKg: number;
}

export interface PelletverbrauchDto {
  data: Array<Pelletverbrauch>;
  filePath: string;
  sheetName: string;
}

export interface PelletbetankungDto {
  data: Array<Pelletbetankung>;
  filePath: string;
  sheetName: string;
}
