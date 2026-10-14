// Static game data and pure rules (ported from the legacy PHP, see docs/bierspiel.md).

export type Category = 'beer' | 'spirits' | 'mix' | 'cocktail' | 'soft' | 'wine' | 'juice';

export const CATEGORIES: Record<
  Category,
  { label: string; ref: number; unit: number; range: [number, number] }
> = {
  beer: { label: 'Beer', ref: 2.3, unit: 0.5, range: [40, 50] },
  spirits: { label: 'Spirits', ref: 1.1, unit: 0.02, range: [12, 15] },
  mix: { label: 'Beer mixes', ref: 2.1, unit: 0.5, range: [8, 12] },
  cocktail: { label: 'Cocktails', ref: 5.2, unit: 0.4, range: [40, 50] },
  soft: { label: 'Non-alcoholic', ref: 1.8, unit: 0.5, range: [5, 7] },
  wine: { label: 'Wine', ref: 1.6, unit: 0.1, range: [3, 5] },
  juice: { label: 'Juice', ref: 1.5, unit: 0.5, range: [6, 8] },
};

const g = (cat: Category, list: string) =>
  list.split(',').map((s) => {
    const [key, label] = s.split(':');
    return { key: key!, label: label ?? key!, cat };
  });

// Order matters (legacy `preise` / menu column order).
export const GOODS = [
  ...g(
    'beer',
    'pils:Pils,helles:Helles,lager:Lager,weissbier:Weißbier,schwarzbier:Schwarzbier,altbier:Altbier',
  ),
  ...g('spirits', 'korn:Korn,whiskey:Whiskey,wodka:Wodka,obstbrand:Obstbrand,rum:Rum,likeur:Likör'),
  ...g(
    'mix',
    'almradler:Almradler,radler:Radler,diesel:Diesel,goass:Goaß,colaweizen:Colaweizen,russ:Russ',
  ),
  ...g(
    'cocktail',
    'sotb:Sex on the Beach,liit:Long Island Iced Tea,zombie:Zombie,hurricane:Hurricane,caipirinha:Caipirinha,mojito:Mojito',
  ),
  ...g(
    'soft',
    'mineralwasser:Mineralwasser,cola:Cola,zlimo:Zitronenlimo,olimo:Orangenlimo,apfelschorle:Apfelschorle,colamix:Colamix',
  ),
  ...g(
    'wine',
    'weisswein:Weißwein,rotwein:Rotwein,schaumwein:Schaumwein,likeurwein:Likörwein,rosewein:Roséwein',
  ),
  ...g(
    'juice',
    'asaft:Apfelsaft,osaft:Orangensaft,ksaft:Kirschsaft,bsaft:Bananensaft,jsaft:Johannisbeersaft,psaft:Pfirsichsaft',
  ),
];
export const GOOD_KEYS = new Set(GOODS.map((x) => x.key));
export const goodsOf = (cat: Category) => GOODS.filter((x) => x.cat === cat);

/** The NPC market quotes prices per this many liters (legacy cost = price * amount was unplayable). */
export const MARKET_LITERS = 20;

export const WARES = ['beer', 'spring_water', 'hops', 'barley', 'malt'] as const;
export type Ware = (typeof WARES)[number];

export const LOCATIONS = [
  'Ansbach',
  'Landshut',
  'München',
  'Bayreuth',
  'Regensburg',
  'Würzburg',
  'Augsburg',
];
export const DISTRICTS = ['Innenstadt', 'Wohngebiet', 'Gewerbegebiet'];

export const BUILDINGS: Record<
  string,
  { name: string; cost: number; minutes: number; popularity: number }
> = {
  kneipe: { name: 'Kneipe', cost: 100_000, minutes: 300, popularity: 300 },
  gaststaette: { name: 'Gaststätte', cost: 300_000, minutes: 600, popularity: 800 },
};
/** Construction provider 1-5. */
export const PROVIDER_COST = [1, 1.2, 1.5, 2, 2.5];
export const PROVIDER_TIME = [1, 0.9, 0.8, 0.6, 0.4];
export const MAX_BUILDINGS = 20;

export const STYLES = ['keinen', 'Modern', 'Rustikal', 'Edel'] as const;
export const GROUPS = [
  'keine',
  'Familie',
  'Teenager',
  'Junge Erwachsene',
  'Erwachsene',
  'Rentner',
] as const;
export const seatsOf = (index: number) => 20 + 4 * index;

/** Visitor group keys in `districts` / `demand` order. */
export const GROUP_KEYS = ['teen', 'young', 'family', 'senior', 'adult'] as const;
export type GroupKey = (typeof GROUP_KEYS)[number];
export const GROUP_OF: Record<string, GroupKey> = {
  Teenager: 'teen',
  'Junge Erwachsene': 'young',
  Familie: 'family',
  Rentner: 'senior',
  Erwachsene: 'adult',
};

export const round2 = (n: number) => Math.round(n * 100) / 100;

export type Rng = () => number;
export const randInt = (rng: Rng, lo: number, hi: number) => lo + Math.floor(rng() * (hi - lo + 1));

/** Demand multiplier for ratio r = own price / reference price. */
export function priceFactor(r: number): number {
  const up: [number, number][] = [
    [2, 0],
    [1.75, 0.15],
    [1.5, 0.25],
    [1.35, 0.35],
    [1.3, 0.5],
    [1.25, 0.6],
    [1.2, 0.7],
    [1.15, 0.8],
    [1.1, 0.9],
    [1, 0.95],
  ];
  if (r === 1) return 1;
  if (r > 1) return up.find(([t]) => r > t)?.[1] ?? 0;
  const down: [number, number][] = [
    [0.95, 1.1],
    [0.9, 1.2],
    [0.8, 1.3],
    [0.7, 1.35],
    [0.6, 1.5],
    [0.5, 1.7],
    [0.35, 2],
    [0.25, 2.6],
    [0.15, 3.5],
    [0.1, 4.5],
    [0.05, 7],
  ];
  return down.find(([t]) => r >= t)?.[1] ?? 10;
}

/** Random split of 100 % over n goods (shares sum to 1). */
export function distribution(rng: Rng, n: number): number[] {
  if (n === 0) return [];
  const arr = new Array<number>(n).fill(0);
  let used = 0;
  for (let k = n; k > 1; k--) {
    const w = (100 - used) / k;
    const z = Math.min(Math.max(randInt(rng, Math.floor(w - 8), Math.ceil(w + 8)), 0), 100 - used);
    arr[k - 1] = z / 100;
    used += z;
  }
  arr[0] = (100 - used) / 100;
  for (let i = arr.length - 1; i > 0; i--) {
    const j = randInt(rng, 0, i);
    [arr[i], arr[j]] = [arr[j]!, arr[i]!];
  }
  return arr;
}

/** Visitor share multipliers per group for a building style. */
export function styleFactor(style: string, group: GroupKey): number {
  if (style === 'Modern') return group === 'teen' || group === 'young' ? 1.1 : 1;
  if (style === 'Rustikal')
    return group === 'adult' || group === 'senior' || group === 'family' ? 1.066 : 1;
  if (style === 'Edel') return 1.05;
  return 0.5;
}

export const DRIFT = [
  0, 0, 1, -1, 1, -1, 1, -1, 1, -1, 1, -1, 2, -2, 2, -2, 2, -2, 2, -2, 3, -3, 3, -3, 3, -3, 4, -4,
  4, -4, 5, -5,
];
