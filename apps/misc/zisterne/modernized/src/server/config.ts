export interface Config {
  sensor: 'simulator' | 'pigpio';
  port: number;
  host: string;
  production: boolean;
  apiToken: string;
  dbPath: string;
  triggerPin: number;
  echoPin: number;
  diameterCm: number;
  maxFillLevelCm: number;
  sensorOffsetCm: number;
  temperatureC: number;
  triggerIntervalMs: number;
  averageMeasurements: number;
  timeoutS: number;
}

function num(env: NodeJS.ProcessEnv, key: string, fallback: number, min = 0): number {
  const raw = env[key];
  if (raw === undefined || raw === '') return fallback;
  const value = Number(raw);
  if (!Number.isFinite(value) || value < min) throw new Error(`${key} must be a number >= ${min}`);
  return value;
}

export function loadConfig(env: NodeJS.ProcessEnv = process.env): Config {
  const sensor = env.SENSOR || 'simulator';
  if (sensor !== 'simulator' && sensor !== 'pigpio') {
    throw new Error('SENSOR must be "simulator" or "pigpio"');
  }
  return {
    sensor,
    port: num(env, 'PORT', 3000),
    host: env.HOST || '0.0.0.0',
    production: env.NODE_ENV === 'production',
    apiToken: env.API_TOKEN ?? '',
    dbPath: env.DB_PATH || 'data/zisterne.db',
    triggerPin: num(env, 'TRIGGER_PIN', 17),
    echoPin: num(env, 'ECHO_PIN', 27),
    diameterCm: num(env, 'DIAMETER_CM', 199.7, 1),
    maxFillLevelCm: num(env, 'MAX_FILL_LEVEL_CM', 184.5, 1),
    sensorOffsetCm: num(env, 'SENSOR_OFFSET_CM', 27),
    temperatureC: num(env, 'TEMPERATURE_C', 15, -50),
    triggerIntervalMs: num(env, 'TRIGGER_INTERVAL_MS', 200, 60),
    averageMeasurements: num(env, 'AVERAGE_MEASUREMENTS', 10, 1),
    timeoutS: num(env, 'MEASUREMENT_TIMEOUT_S', 30, 1),
  };
}
