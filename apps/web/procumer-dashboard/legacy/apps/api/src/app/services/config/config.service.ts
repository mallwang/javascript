import * as Joi from 'joi';
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
    const filePath = findConfig('assets/.env', {cwd: __dirname, dot: true, home: false});
    const config = parse(readFileSync(filePath));
    this.envConfig = this.validateInput(config);
    Logger.debug(`Running on NODE_ENV: '${this.envConfig['NODE_ENV']}'`, ConfigService.name);
  }

  /**
   * Ensures that all required variables are set or if not, set default values
   * @param {EnvConfig} envConfig: The environment-specific configuration.
   * @return {EnvConfig} The validated environment-specific configuration.
   */
  private validateInput(envConfig: EnvConfig): EnvConfig {
    const validateOptions = {allowUnknown: true}; // allow unknown keys to be ignored

    // schema containing default variables and default values
    const envVarsSchema: Joi.ObjectSchema = Joi.object({
      NODE_ENV: Joi.string().valid('development', 'production', 'test').default('development'),
      HOST: Joi.string().default('localhost'),
      PORT: Joi.number().default(3000),
      FILE_PATH: Joi.string().required(),
      API_VERSION: Joi.string().default('api/v1'),
      SWAGGER_API_ENDPOINT: Joi.string().default('api/docs'),
      CORS_WHITELISTED_ORIGINS: Joi.string().default('any'),
      SHEET_NAME_PELLET_BETANKUNG: Joi.string().default('Pellettankung'),
      SHEET_NAME_PELLET_VERBRAUCH: Joi.string().default('Pelletverbrauch'),
      SHEET_NAME_STROM: Joi.string().default('Strombezug'),
      SHEET_NAME_WASSER: Joi.string().default('Zaehler Wasser')
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
   * Return the port number.
   * @return {number} The port number.
   */
  public get apiPort(): number {
    return Number(this.get('PORT'));
  }

  /**
   * Return the host to bind to.
   * @return {string} The host name.
   */
  public get apiHost(): string {
    return this.get('HOST');
  }

  /**
   * Generic getter.
   * @param {string} key: The key to get.
   * @return {string} The value.
   */
  public get(key: string): string {
    return process.env[key] ? process.env[key] : this.envConfig[key];
  }
}
