export const DURATIONS = [30, 60, 120, 240] as const;
export type Duration = (typeof DURATIONS)[number];
export const CRAFT_DURATION: Duration = 60;

// Per duration (minutes): food/water consumed, yield range, food multiplier, skill gain.
const COST = { 30: [0.4, 0.25], 60: [0.8, 0.5], 120: [1.6, 1], 240: [3.2, 2] } as const;
const YIELD = { 30: [8, 12], 60: [14, 22], 120: [26, 40], 240: [46, 64] } as const;
const FOOD_FACTOR = { 30: 1, 60: 1.75, 120: 3, 240: 4.5 } as const;
const SKILL_GAIN = { 30: 1, 60: 2, 120: 4, 240: 8 } as const;

export interface Activity {
  id: number;
  name: string;
  skill: string;
  /** gathering: resource to credit; with `foodKind` the amount is a random food row */
  resource?: string;
  foodKind?: string;
  /** crafting: item to credit */
  item?: string;
}

export const ACTIVITIES: Activity[] = [
  { id: 1, name: 'Hunt', skill: 'hunting', resource: 'animal_food', foodKind: 'small_animal' },
  { id: 2, name: 'Gather fruit', skill: 'gathering', resource: 'plant_food', foodKind: 'plant' },
  { id: 3, name: 'Fish', skill: 'fishing', resource: 'animal_food', foodKind: 'fish' },
  {
    id: 4,
    name: 'Gather mushrooms',
    skill: 'mushrooms',
    resource: 'plant_food',
    foodKind: 'mushroom',
  },
  { id: 5, name: 'Fell wood', skill: 'woodcutting', resource: 'wood' },
  { id: 6, name: 'Cut stone', skill: 'stonecutting', resource: 'stone' },
  { id: 7, name: 'Find water', skill: 'waterfinding', resource: 'water' },
  { id: 8, name: 'Axe', skill: 'axe', item: 'axe' },
  { id: 9, name: 'Hunting spear', skill: 'spear', item: 'spear' },
  { id: 10, name: 'Water bag', skill: 'waterbag', item: 'waterbag' },
  { id: 11, name: 'Basket', skill: 'basket', item: 'basket' },
  { id: 12, name: 'Loincloth', skill: 'loincloth', item: 'loincloth' },
  { id: 13, name: 'Shoes', skill: 'shoes', item: 'shoes' },
];

export const activity = (id: number) => ACTIVITIES.find((a) => a.id === id);
export const isDuration = (d: number): d is Duration =>
  (DURATIONS as readonly number[]).includes(d);

export const cost = (d: Duration) => ({ food: COST[d][0], water: COST[d][1] });

/** What a finished job credits. `rand` is injectable for tests; `foodPoints` is the drawn food row. */
export function reward(a: Activity, d: Duration, rand: () => number, foodPoints = 0) {
  const [lo, hi] = YIELD[d];
  const amount = a.item
    ? 1
    : a.foodKind
      ? Math.round(foodPoints * FOOD_FACTOR[d] * 100) / 100
      : lo + Math.floor(rand() * (hi - lo + 1));
  return { amount, skill: SKILL_GAIN[d] };
}
