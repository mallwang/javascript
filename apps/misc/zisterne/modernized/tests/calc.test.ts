import { describe, expect, it } from 'vitest';
import { distanceFromEcho, fillFromDistance, litersPerCm, meanOfValid } from '../src/server/calc';

const tank = { diameterCm: 199.7, maxFillLevelCm: 184.5, sensorOffsetCm: 27 };

describe('calc', () => {
  it('converts echo time to distance (15 °C, 1 m = 5.87 ms round trip)', () => {
    expect(distanceFromEcho(5872, 15)).toBeCloseTo(100, 0);
  });

  it('uses the real cylinder volume, not the legacy formula', () => {
    expect(litersPerCm(199.7)).toBeCloseTo(31.32, 2);
  });

  it('maps distance to fill level and clamps it', () => {
    expect(fillFromDistance(27, tank).percent).toBeCloseTo(100);
    expect(fillFromDistance(27 + 184.5 / 2, tank).percent).toBeCloseTo(50);
    expect(fillFromDistance(300, tank).levelCm).toBe(0);
    expect(fillFromDistance(10, tank).percent).toBe(100);
  });

  it('ignores outliers and lost echoes, gives up when too few are valid', () => {
    expect(meanOfValid([100, 102, null, 450])).toBeCloseTo(101);
    expect(meanOfValid([100, null, null, null])).toBeNull();
    expect(meanOfValid([])).toBeNull();
  });
});
