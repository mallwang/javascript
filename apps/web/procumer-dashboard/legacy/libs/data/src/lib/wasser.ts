export interface Wasserverbrauch {
  date: Date;
  meterReading: number;
}

export interface WasserverbrauchDto {
  data: Array<Wasserverbrauch>;
  filePath: string;
  sheetName: string;
}
