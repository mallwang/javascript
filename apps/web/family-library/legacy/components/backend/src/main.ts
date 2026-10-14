import {NestFactory} from '@nestjs/core';
import {AppModule} from './app.module';
import {FastifyAdapter, NestFastifyApplication} from '@nestjs/platform-fastify';
import {ConfigService} from './config/config.service';
import * as helmet from 'helmet';
import {Logger, ValidationPipe} from '@nestjs/common';
import * as fastifyRateLimit from 'fastify-rate-limit';
import {version} from '../package.json';
import {join} from 'path';
import {NotFoundExceptionFilter} from './filters/not-found-exception.filter';
import * as fs from 'fs';
import {DocumentBuilder, SwaggerModule} from '@nestjs/swagger';

/**
 * Bootstrap the NestJS application
 */
async function bootstrap(): Promise<any> {
  const app = await NestFactory.create<NestFastifyApplication>(AppModule, new FastifyAdapter());

  const configService = app.get(ConfigService);
  const apiVersion = configService.get('API_VERSION');
  const swaggerApiEndpoint = configService.get('SWAGGER_API_ENDPOINT');

  // TODO: consider versioning (https://github.com/nestjsx/nest-router)
  app.setGlobalPrefix(apiVersion);

  // Multiple security features (see https://helmetjs.github.io/docs/)
  app.use(helmet());

  // Protect all endpoints using the ValidationPipe
  app.useGlobalPipes(
    // new ValidationPipe({
    //   disableErrorMessages: true,
    //   whitelist: true,
    //   transform: true
    // })
    new ValidationPipe()
  );

  // TODO: disable for production because the webapp will be statically served by the backend
  app.enableCors({
    origin: '*'
  });

  // TODO: setup CSRF protection
  // app.use(cookieParser());
  // app.use(csurf({cookie: true}));

  // Rate limiting for protection against DDoS attacks
  app.register(fastifyRateLimit, {
    timeWindow: 15 * 60 * 1000, // 15 minutes
    max: 1000 // limit each IP to 1000 requests per timeWindow
  });

  // Host the Angular app from custom directory
  app.useStaticAssets({root: join(__dirname, configService.get('FRONTEND_STATIC_DIR')), prefix: ''});

  // Exception filter for non-existing paths to be handled by the Angular router
  app.useGlobalFilters(new NotFoundExceptionFilter(configService));

  const stage =
    configService.environment === 'development' ? 'Dev' : configService.environment === 'test' ? 'Test' : '';

  // initialize swagger
  const options = new DocumentBuilder()
    .setTitle(`Family Library Backend ${stage}`)
    .setDescription('The Family Library Backend API description.')
    .setVersion(version)
    .addBearerAuth()
    .build();
  const document = SwaggerModule.createDocument(app, options);
  SwaggerModule.setup(swaggerApiEndpoint, app, document);

  await app.listen(configService.apiPort, configService.apiHost);
  const appUrl = await app.getUrl();
  if (fs.existsSync(join(__dirname, configService.get('FRONTEND_STATIC_DIR')))) {
    Logger.log(`Frontend running on ${appUrl}`, 'NestApplication');
  }
  Logger.log(`Backend running on ${appUrl}/${apiVersion}`, 'NestApplication');
  Logger.log(`Swagger running on ${appUrl}/${swaggerApiEndpoint}`, 'NestApplication');
}
bootstrap();
