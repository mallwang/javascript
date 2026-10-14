import {Controller, Get, Logger} from '@nestjs/common';
import {ApiInternalServerErrorResponse, ApiOkResponse, ApiOperation, ApiTags} from '@nestjs/swagger';
import {StromConsumptionMonthsDto, StromConsumptionYearsDto, StromMeterReadingsDto} from '@procumer-dashboard/data';
import {StromService} from './strom.service';

@ApiTags('Strom')
@Controller('strom')
export class StromController {
  public constructor(private readonly stromService: StromService) {}

  @Get('meterReadings')
  @ApiOperation({
    description: 'Get complete strom verbrauch data since ever.'
  })
  @ApiOkResponse({
    description: 'The request was successful.'
  })
  @ApiInternalServerErrorResponse({description: 'Internal Server Error.'})
  // @Cache({key: 'strom/meterReadings', ttl: 60})
  public async getMeterReadings(): Promise<StromMeterReadingsDto> {
    Logger.debug(`In /strom/meterReadings`, StromController.name);
    return this.stromService.getMeterReadings();
  }

  @Get('consumption/years')
  @ApiOperation({
    description: 'Get complete strom verbrauch data since ever.'
  })
  @ApiOkResponse({
    description: 'The request was successful.'
  })
  @ApiInternalServerErrorResponse({description: 'Internal Server Error.'})
  // @Cache({key: 'strom/consumptionYears', ttl: 60})
  public async getConsumptionYears(): Promise<StromConsumptionYearsDto> {
    Logger.debug(`In /strom/consumption/years`, StromController.name);
    return this.stromService.getConsumptionYears();
  }

  @Get('consumption/months')
  @ApiOperation({
    description: 'Get complete strom verbrauch data since ever.'
  })
  @ApiOkResponse({
    description: 'The request was successful.'
  })
  @ApiInternalServerErrorResponse({description: 'Internal Server Error.'})
  // @Cache({key: 'strom/consumptionYears', ttl: 60})
  public async getConsumptionMonths(): Promise<StromConsumptionMonthsDto> {
    Logger.debug(`In /strom/consumption/months`, StromController.name);
    return this.stromService.getConsumptionMonths();
  }
}
