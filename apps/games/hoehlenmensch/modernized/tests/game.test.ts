import { describe, expect, it } from 'vitest';
import { activity, reward } from '../src/lib/game';
import { openDb } from '../src/lib/db';
import { getPlayer, register, settle, start } from '../src/lib/player';

const T0 = 1_000_000;
function setup() {
  const db = openDb(':memory:');
  const r = register(db, 'grog', 'password1');
  if ('error' in r) throw new Error(r.error);
  return { db, id: r.id };
}

describe('reward', () => {
  it('gives wood within the duration range', () => {
    const a = activity(5)!;
    expect(reward(a, 30, () => 0).amount).toBe(8);
    expect(reward(a, 30, () => 0.999).amount).toBe(12);
    expect(reward(a, 240, () => 0.999)).toEqual({ amount: 64, skill: 8 });
  });
  it('scales food points by duration and gives 1 crafted item', () => {
    expect(reward(activity(1)!, 60, Math.random, 10).amount).toBe(17.5);
    expect(reward(activity(8)!, 60, Math.random).amount).toBe(1);
  });
});

describe('register', () => {
  it('rejects duplicates and weak input, starts with 10/10/10/0/0', () => {
    const { db, id } = setup();
    expect(register(db, 'GROG', 'password1')).toHaveProperty('error');
    expect(register(db, 'x', 'password1')).toHaveProperty('error');
    expect(register(db, 'ugg', 'short')).toHaveProperty('error');
    expect(getPlayer(db, id, T0).resources).toEqual({
      animal_food: 10,
      plant_food: 10,
      stone: 0,
      water: 10,
      wood: 0,
    });
  });
});

describe('jobs', () => {
  it('consumes food and water, credits once after the end', () => {
    const { db, id } = setup();
    expect(start(db, id, 5, 60, T0)).toBeUndefined();
    let p = getPlayer(db, id, T0 + 59 * 60_000);
    expect(p.resources).toMatchObject({ animal_food: 9.2, water: 9.5, wood: 0 });
    expect(p.activity).toBe(5);
    const end = T0 + 60 * 60_000;
    settle(db, id, end, () => 0);
    settle(db, id, end, () => 0); // second settle must not credit again
    p = getPlayer(db, id, end);
    expect(p.resources.wood).toBe(14);
    expect(p.skills).toEqual({ woodcutting: 2 });
    expect(p.xp).toBe(1);
    expect(p.activity).toBe(0);
  });
  it('refuses a second job, bad input and an empty larder', () => {
    const { db, id } = setup();
    expect(start(db, id, 99, 60, T0)).toMatch(/Unknown/);
    expect(start(db, id, 5, 45, T0)).toMatch(/Unknown/);
    expect(start(db, id, 5, 30, T0)).toBeUndefined();
    expect(start(db, id, 6, 30, T0)).toMatch(/busy/);
    db.prepare("UPDATE stock SET qty = 0 WHERE name IN ('animal_food', 'plant_food')").run();
    expect(start(db, id, 6, 30, T0 + 31 * 60_000)).toMatch(/Not enough/);
  });
  it('crafting always takes 60 minutes and yields an item', () => {
    const { db, id } = setup();
    expect(start(db, id, 8, 240, T0)).toBeUndefined();
    const p = getPlayer(db, id, T0 + 60 * 60_000);
    expect(p.items).toEqual({ axe: 1 });
    expect(p.skills).toEqual({ axe: 2 });
  });
});
