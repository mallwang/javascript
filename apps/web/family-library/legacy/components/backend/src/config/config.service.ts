import * as Joi from '@hapi/joi';
import {Injectable, Logger} from '@nestjs/common';
import * as findConfig from 'find-config';
import {parse} from 'dotenv';
import {readFileSync} from 'fs';

export interface EnvConfig {
  [key: string]: string;
}

@Injectable()
export class ConfigService {
  private readonly envConfig: EnvConfig;

  public constructor() {
    // Look for the config file the directory hierarchy upwards until it finds one
    Logger.debug('Loading environment file .env', ConfigService.name);
    const filePath = findConfig('.env', {cwd: __dirname, dot: true, home: false});
    const config = parse(readFileSync(filePath));
    this.envConfig = this.validateInput(config);
    Logger.debug(`Running on NODE_ENV: '${this.envConfig['NODE_ENV']}'`, ConfigService.name);

    // Print mongodb uri
    Logger.debug(`Using MONGODB_URI: '${this.envConfig['MONGODB_URI']}'`);
  }

  /**
   * Ensures that all required variables are set or if not, set default values
   * @param {EnvConfig} envConfig: The environment-specific configuration.
   * @return {EnvConfig} The validated environment-specific configuration.
   */
  private validateInput(envConfig: EnvConfig): EnvConfig {
    const validateOptions = {allowUnknown: true}; // allow unknown keys to be ignored

    // schema containing default variables and default values
    const envVarsSchema = Joi.object({
      NODE_ENV: Joi.string()
        .valid('test', 'development', 'production')
        .default('development'),
      BASE_DOMAIN: Joi.string().required(),
      HOST: Joi.string().required(),
      PORT: Joi.number().required(),
      FRONTEND_PORT: Joi.number().required(),
      MONGODB_URI: Joi.string().required(),
      API_VERSION: Joi.string().default('v1'),
      SWAGGER_API_ENDPOINT: Joi.string().default('api'),
      FRONTEND_STATIC_DIR: Joi.string().default('../../frontend'),
      OAUTH_TOKEN_EXPIRATION_MINUTES: Joi.string().required(),
      OAUTH_TOKEN_SECRET_KEY: Joi.string().required(),
      EMAIL_HOST: Joi.string().required(),
      EMAIL_PORT: Joi.string().required(),
      EMAIL_SECURE: Joi.boolean().required(),
      EMAIL_USER: Joi.string().required(),
      EMAIL_PASS: Joi.string().required()
    }).options(validateOptions);

    // validate configuration
    const {error, value: validatedEnvConfig} = envVarsSchema.validate(envConfig);

    // configuration contains errors
    if (error) {
      throw new Error(`Configuration validation error: ${error.message}`);
    }

    // configuration is valid
    return validatedEnvConfig;
  }

  /**
   * Return the environment
   */
  public get environment(): string {
    return this.envConfig.NODE_ENV;
  }

  /**
   * Return the port number
   */
  public get apiPort(): number {
    return Number(this.get('PORT'));
  }

  /**
   * Return the host to bind to.
   */
  public get apiHost(): string {
    return this.get('HOST');
  }

  /**
   * Generic getter
   * @param {string} key: The key of the environment variable.
   * @return {string} The value of the environment variable.
   */
  public get(key: string): string {
    return process.env[key] ? process.env[key] : this.envConfig[key];
  }
}
