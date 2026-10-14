import { soundSpeed } from './calc';
import type { Config } from './config';

export interface Sensor {
  /** One trigger pulse; resolves with the echo pulse width in microseconds or null (no echo). */
  echoMicros(): Promise<number | null>;
  close(): void;
}

/** Fake tank for development and tests: slow random walk, 5 % lost and 1 % garbage echoes. */
export function createSimulator(cfg: Config): Sensor {
  let levelCm = cfg.maxFillLevelCm * 0.6;
  return {
    async echoMicros() {
      const roll = Math.random();
      if (roll < 0.05) return null;
      levelCm = Math.min(Math.max(levelCm + (Math.random() - 0.5) * 0.4, 5), cfg.maxFillLevelCm);
      const distanceCm =
        roll < 0.06
          ? 450
          : cfg.sensorOffsetCm + cfg.maxFillLevelCm - levelCm + (Math.random() - 0.5) * 0.6;
      return (distanceCm * 2) / (soundSpeed(cfg.temperatureC) * 1e-4);
    },
    close() {},
  };
}

/** Real HC-SR04 on a Raspberry Pi. `pigpio` is optional, so it is only loaded on demand. */
export async function createPigpioSensor(cfg: Config): Promise<Sensor> {
  const { Gpio } = await import('pigpio');
  const trigger = new Gpio(cfg.triggerPin, { mode: Gpio.OUTPUT });
  const echo = new Gpio(cfg.echoPin, { mode: Gpio.INPUT, alert: true });
  trigger.digitalWrite(0);

  return {
    echoMicros: () =>
      new Promise((resolve) => {
        let start = 0;
        let started = false;
        const finish = (value: number | null) => {
          clearTimeout(timer);
          echo.removeListener('alert', onAlert);
          resolve(value);
        };
        const onAlert = (level: number, tick: number) => {
          if (level === 1) {
            start = tick;
            started = true;
          } else if (started) {
            finish((tick - start) >>> 0); // unsigned 32 bit, survives the tick wrap-around
          }
        };
        // 400 cm takes about 23 ms, so 60 ms without a falling edge means no echo
        const timer = setTimeout(() => finish(null), 60);
        echo.on('alert', onAlert);
        trigger.trigger(10, 1);
      }),
    close() {
      echo.disableAlert();
    },
  };
}

export async function createSensor(cfg: Config): Promise<Sensor> {
  return cfg.sensor === 'pigpio' ? createPigpioSensor(cfg) : createSimulator(cfg);
}
