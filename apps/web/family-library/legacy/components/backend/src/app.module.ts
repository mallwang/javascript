import {Module} from '@nestjs/common';
import {MongooseModule} from '@nestjs/mongoose';
import {BookModule} from './book/book.module';
import {UserModule} from './user/user.module';
import {AppController} from './app.controller';
import {AppService} from './app.service';
import {ConfigModule} from './config/config.module';
import {ConfigService} from './config/config.service';
import {AuthModule} from './auth/auth.module';

@Module({
  imports: [
    MongooseModule.forRootAsync({
      imports: [ConfigService],
      useFactory: async (configService: ConfigService) => ({
        uri: configService.get('MONGODB_URI'),
        useFindAndModify: false,
        useCreateIndex: true
      }),
      inject: [ConfigService]
    }),
    AuthModule,
    BookModule,
    ConfigModule,
    UserModule
  ],
  controllers: [AppController],
  providers: [AppService]
})
export class AppModule {}
