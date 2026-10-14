import {Controller, Get, Logger} from '@nestjs/common';
import {ApiInternalServerErrorResponse, ApiOkResponse, ApiOperation, ApiTags} from '@nestjs/swagger';
import {ConfigService} from '../services/config/config.service';
import {FileService} from '../services/file/file.service';
import {Wasserverbrauch, WasserverbrauchDto} from '@procumer-dashboard/data';

@ApiTags('Wasser')
@Controller('wasser')
export class WasserController {
  public constructor(private readonly configService: ConfigService, private readonly fileService: FileService) {}

  @Get('verbrauch')
  @ApiOperation({
    description: 'Get complete wasser verbrauch data since ever.'
  })
  @ApiOkResponse({
    description: 'The request was successful.'
  })
  @ApiInternalServerErrorResponse({description: 'Internal Server Error.'})
  // @Cache({key: 'wasser/verbrauch', ttl: 60})
  public async get(): Promise<WasserverbrauchDto> {
    Logger.debug(`In /wasser/verbrauch`, WasserController.name);

    // Set the cell indexes
    const cellDate = 0;
    const cellMeterReading = 1;

    // Read and parse the sheet
    const sheetName = this.configService.get('SHEET_NAME_WASSER');
    const data = await this.fileService.read(sheetName);

    // Setup the list of Wasserverbrauch
    const wasserverbrauchData: Array<Wasserverbrauch> = [];

    // iterate over all rows
    data.forEach((row) => {
      // Logger.debug(row, WasserController.name);

      // Only parse rows beginning with a date
      if (row[cellDate] && row[cellDate] instanceof Date) {
        const date = new Date(row[cellDate]);
        const meterReading = row[cellMeterReading];

        const wasserverbrauch: Wasserverbrauch = {
          date: date,
          meterReading: Math.round(meterReading * 100) / 100
        };

        wasserverbrauchData.push(wasserverbrauch);
      }
    });

    const wasserResponse: WasserverbrauchDto = {
      data: wasserverbrauchData,
      filePath: this.configService.get('FILE_PATH'),
      sheetName: sheetName
    };

    return wasserResponse;
  }
}
