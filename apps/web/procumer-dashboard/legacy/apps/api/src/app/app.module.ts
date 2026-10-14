import {Module} from '@nestjs/common';

import {AppController} from './app.controller';
import {AppService} from './app.service';
import {ConfigService} from './services/config/config.service';
import {PelletController} from './pellet/pellet.controller';
import {StromController} from './strom/strom.controller';
import {WasserController} from './wasser/wasser.controller';
import {FileService} from './services/file/file.service';
import {PelletService} from './pellet/pellet.service';
import {StromService} from './strom/strom.service';
import {WasserService} from './wasser/wasser.service';

@Module({
  imports: [],
  controllers: [AppController, PelletController, StromController, WasserController],
  providers: [AppService, ConfigService, FileService, PelletService, StromService, WasserService]
})
export class AppModule {}
