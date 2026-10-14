import type { Config } from './config';

export interface Fill {
  levelCm: number;
  liters: number;
  percent: number;
}

/** HC-SR04 range from the datasheet; anything outside is a bad echo. */
const MIN_DISTANCE_CM = 2;
const MAX_DISTANCE_CM = 400;

/** Speed of sound in m/s (legacy approximation). */
export function soundSpeed(temperatureC: number): number {
  return 331.6 + 0.6 * temperatureC;
}

/** The echo pulse covers the way there and back. */
export function distanceFromEcho(echoMicros: number, temperatureC: number): number {
  return (echoMicros / 2) * soundSpeed(temperatureC) * 1e-4;
}

export function isPlausible(distanceCm: number): boolean {
  return distanceCm >= MIN_DISTANCE_CM && distanceCm <= MAX_DISTANCE_CM;
}

/** Mean of the plausible values, or null if fewer than half of the attempts are usable. */
export function meanOfValid(distances: (number | null)[]): number | null {
  const valid = distances.filter((d): d is number => d !== null && isPlausible(d));
  if (valid.length === 0 || valid.length < distances.length / 2) return null;
  return valid.reduce((a, b) => a + b, 0) / valid.length;
}

export function litersPerCm(diameterCm: number): number {
  return (Math.PI * (diameterCm / 2) ** 2) / 1000;
}

export function fillFromDistance(
  distanceCm: number,
  cfg: Pick<Config, 'diameterCm' | 'maxFillLevelCm' | 'sensorOffsetCm'>,
): Fill {
  const raw = cfg.maxFillLevelCm - (distanceCm - cfg.sensorOffsetCm);
  const levelCm = Math.min(Math.max(raw, 0), cfg.maxFillLevelCm);
  return {
    levelCm,
    liters: levelCm * litersPerCm(cfg.diameterCm),
    percent: (levelCm / cfg.maxFillLevelCm) * 100,
  };
}
