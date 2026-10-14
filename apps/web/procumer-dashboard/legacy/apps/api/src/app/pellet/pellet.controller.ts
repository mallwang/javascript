import {Controller, Get, Logger} from '@nestjs/common';
import {ApiInternalServerErrorResponse, ApiOkResponse, ApiOperation, ApiTags} from '@nestjs/swagger';
import {ConfigService} from '../services/config/config.service';
import {FileService} from '../services/file/file.service';
import {Pelletbetankung, PelletbetankungDto, Pelletverbrauch, PelletverbrauchDto} from '@procumer-dashboard/data';

@ApiTags('Pellet')
@Controller('pellet')
export class PelletController {
  public constructor(private readonly configService: ConfigService, private readonly fileService: FileService) {}

  @Get('betankung')
  @ApiOperation({
    description: 'Get complete pellet betankung data since ever.'
  })
  @ApiOkResponse({
    description: 'The request was successful.'
  })
  @ApiInternalServerErrorResponse({description: 'Internal Server Error.'})
  // @Cache({key: 'strom/betankung', ttl: 60})
  public async getBetankung(): Promise<PelletbetankungDto> {
    Logger.debug(`In /pellet/betankung`, PelletController.name);

    // Set the cell indexes
    const cellDate = 0;
    const cellAmountInTons = 1;
    const cellPricePerTon = 2;
    const cellPotHoursTotal = 4;
    const cellConsumptionTotalKg = 5;

    // Read and parse the sheet
    const sheetName = this.configService.get('SHEET_NAME_PELLET_BETANKUNG');
    const data = await this.fileService.read(sheetName);

    // Setup the list of Pelletverbrauch
    const betankungData: Array<Pelletbetankung> = [];

    // Iterate over all rows
    data.forEach((row) => {
      // Logger.debug(row, PelletController.name);

      // Only parse rows beginning with a date
      if (row[cellDate] && row[cellDate] instanceof Date) {
        const date = new Date(row[cellDate]);
        const amountInTons = row[cellAmountInTons];
        const pricePerTon = row[cellPricePerTon];
        const potHoursTotal = row[cellPotHoursTotal];
        const consumptionTotalKg = row[cellConsumptionTotalKg];

        const pelletbetankung: Pelletbetankung = {
          date: date,
          amountInTons: amountInTons,
          pricePerTon: pricePerTon,
          currency: 'EUR',
          potHoursTotal: potHoursTotal,
          consumptionTotalKg: consumptionTotalKg
        };

        betankungData.push(pelletbetankung);
      }
    });

    // Create response object
    const betankungResponse: PelletbetankungDto = {
      data: betankungData,
      filePath: this.configService.get('FILE_PATH'),
      sheetName: sheetName
    };

    return betankungResponse;
  }

  @Get('verbrauch')
  @ApiOperation({
    description: 'Get complete pellet verbrauch data since ever.'
  })
  @ApiOkResponse({
    description: 'The request was successful.'
  })
  @ApiInternalServerErrorResponse({description: 'Internal Server Error.'})
  // @Cache({key: 'strom/verbrauch', ttl: 60})
  public async getVerbrauch(): Promise<PelletverbrauchDto> {
    Logger.debug(`In /pellet/verbrauch`, PelletController.name);

    // Set the cell indexes
    const cellDate = 0;
    const cellPotHoursTotal = 2;
    const cellConsumptionTotalKg = 3;

    // Read and parse the sheet
    const sheetName = this.configService.get('SHEET_NAME_PELLET_VERBRAUCH');
    const data = await this.fileService.read(sheetName);

    // Setup the list of Pelletverbrauch
    const verbrauchData: Array<Pelletverbrauch> = [];

    // Iterate over all rows
    data.forEach((row) => {
      // Logger.debug(row, PelletController.name);

      // Only parse rows beginning with a date
      if (row[cellDate] && row[cellDate] instanceof Date) {
        const date = new Date(row[cellDate]);
        const potHoursTotal = row[cellPotHoursTotal];
        const consumptionTotalKg = row[cellConsumptionTotalKg];

        const pelletverbrauch: Pelletverbrauch = {
          date: date,
          potHoursTotal: potHoursTotal,
          consumptionTotalKg: consumptionTotalKg
        };

        verbrauchData.push(pelletverbrauch);
      }
    });

    // Create response object
    const verbrauchResponse: PelletverbrauchDto = {
      data: verbrauchData,
      filePath: this.configService.get('FILE_PATH'),
      sheetName: sheetName
    };

    return verbrauchResponse;
  }
}
