import {Controller, Get} from '@nestjs/common';
import {AppService} from './app.service';
import {ApiOkResponse, ApiOperation, ApiTags} from '@nestjs/swagger';

@ApiTags('Health')
@Controller('health')
export class AppController {
  public constructor(private readonly appService: AppService) {}

  @Get('')
  @ApiOperation({
    description: 'Check if the application is online.'
  })
  @ApiOkResponse({
    type: String,
    description: 'The request was successful.'
  })
  public healthCheck(): string {
    return this.appService.healthCheck();
  }
}
