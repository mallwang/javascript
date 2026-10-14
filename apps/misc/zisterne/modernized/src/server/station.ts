import { distanceFromEcho, fillFromDistance, meanOfValid, type Fill } from './calc';
import type { Config } from './config';
import type { Sensor } from './sensor';

export type Measure = Fill & { distanceCm: number };

const sleep = (ms: number) => new Promise((r) => setTimeout(r, ms));

/** Owns the sensor: either the live loop (browser) or a one-off series (cron) uses it, never both. */
export function createStation(
  sensor: Sensor,
  cfg: Config,
  hooks: { onMeasure(m: Measure): void; onStop(reason: 'stopped' | 'timeout'): void },
) {
  let loop: NodeJS.Timeout | undefined;
  let timeout: NodeJS.Timeout | undefined;
  let seriesBusy = false;
  let pinging = false;
  const buffer: (number | null)[] = [];

  const ping = async () => {
    const us = await sensor.echoMicros();
    return us === null ? null : distanceFromEcho(us, cfg.temperatureC);
  };

  async function tick() {
    if (pinging) return;
    pinging = true;
    buffer.push(await ping());
    pinging = false;
    if (buffer.length < cfg.averageMeasurements) return;
    const distanceCm = meanOfValid(buffer.splice(0));
    if (distanceCm !== null) hooks.onMeasure({ distanceCm, ...fillFromDistance(distanceCm, cfg) });
  }

  function stop(reason: 'stopped' | 'timeout' = 'stopped') {
    if (!loop) return;
    clearInterval(loop);
    clearTimeout(timeout);
    loop = undefined;
    buffer.length = 0;
    hooks.onStop(reason);
  }

  return {
    get running() {
      return loop !== undefined;
    },
    /** False if a series is running; restarting an active loop just renews the timeout. */
    start() {
      if (seriesBusy) return false;
      clearTimeout(timeout);
      timeout = setTimeout(() => stop('timeout'), cfg.timeoutS * 1000);
      loop ??= setInterval(() => void tick(), cfg.triggerIntervalMs);
      return true;
    },
    stop,
    /** Cron-style reading: rounds x pulses, first round dropped (sensor warm-up), mean of the rest. */
    async series(rounds = 6, pulses = 20, pauseMs = 60): Promise<Measure | null> {
      if (loop || seriesBusy) throw new Error('busy');
      seriesBusy = true;
      try {
        const means: (number | null)[] = [];
        for (let r = 0; r < rounds; r++) {
          const ds: (number | null)[] = [];
          for (let p = 0; p < pulses; p++) {
            ds.push(await ping());
            await sleep(pauseMs);
          }
          if (r > 0) means.push(meanOfValid(ds));
        }
        const distanceCm = meanOfValid(means);
        return distanceCm === null ? null : { distanceCm, ...fillFromDistance(distanceCm, cfg) };
      } finally {
        seriesBusy = false;
      }
    },
  };
}

export type Station = ReturnType<typeof createStation>;
