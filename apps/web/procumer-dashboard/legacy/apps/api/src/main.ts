import {Logger} from '@nestjs/common';
import {NestFactory} from '@nestjs/core';
import {FastifyAdapter, NestFastifyApplication} from '@nestjs/platform-fastify';
import {DocumentBuilder, SwaggerModule} from '@nestjs/swagger';
import * as cookieParser from 'cookie-parser';
import * as csurf from 'csurf';
import * as fastifyRateLimit from 'fastify-rate-limit';
import * as helmet from 'fastify-helmet';
import {version} from '../../../package.json';
import {AppModule} from './app/app.module';
import {ConfigService} from './app/services/config/config.service';

/**
 * Main function to bootstrap the NestJS application
 * @return {Promise<any>}
 */
async function bootstrap(): Promise<any> {
  const app = await NestFactory.create<NestFastifyApplication>(AppModule, new FastifyAdapter(), {
    logger:
      process.env.NODE_ENV === 'development' ? ['log', 'debug', 'error', 'verbose', 'warn'] : ['log', 'error', 'warn']
  });

  const configService = app.get(ConfigService);
  const apiVersion = configService.get('API_VERSION');
  const swaggerApiEndpoint = configService.get('SWAGGER_API_ENDPOINT');

  const basePath = 'api/v1';
  // TODO: consider versioning (https://github.com/nestjsx/nest-router)
  app.setGlobalPrefix(basePath);

  // Multiple security features (see https://helmetjs.github.io/docs/)
  // eslint-disable-next-line @typescript-eslint/ban-ts-comment
  // @ts-ignore
  await app.register(helmet, {
    contentSecurityPolicy: false
  });

  // Enable cors only for the origin
  const whitelistedOrigins = configService.get('CORS_WHITELISTED_ORIGINS').split(';');
  app.enableCors({
    origin: whitelistedOrigins[0] === 'any' ? '*' : whitelistedOrigins,
    optionsSuccessStatus: 200
  });

  // CSRF protection
  app.use(cookieParser());
  app.use(csurf({cookie: true}));

  // Rate limiting for protection against brute-force attacks
  // eslint-disable-next-line @typescript-eslint/ban-ts-comment
  // @ts-ignore
  await app.register(fastifyRateLimit, {
    timeWindow: 15 * 60 * 1000, // 15 minutes
    max: 1000 // limit each IP to 1000 requests per timeWindow
  });

  const stage =
    configService.environment === 'development' ? 'Dev' : configService.environment === 'test' ? 'Test' : '';

  // initialize swagger
  const options = new DocumentBuilder()
    .setTitle(`Procumer Dashboard API ${stage}`)
    .setDescription('Procumer Dashboard API description')
    .setVersion(version)
    .addServer('http://')
    .addServer('https://')
    .build();
  const document = SwaggerModule.createDocument(app, options);
  SwaggerModule.setup(swaggerApiEndpoint, app, document);

  await app.listen(configService.apiPort, configService.apiHost);
  const appUrl = await app.getUrl();
  Logger.log(`Backend running on ${appUrl}/${apiVersion}`, 'NestApplication');
  Logger.log(`Swagger running on ${appUrl}/${swaggerApiEndpoint}`, 'NestApplication');
}
bootstrap();
