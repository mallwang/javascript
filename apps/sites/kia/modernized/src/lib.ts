export type Category = 'aussen' | 'innen';
export type Filter = Category | 'all';

export interface Photo {
  id: number;
  title: string;
  category: Category;
}

const outside: [number, string][] = [
  [1, 'Vorne Links'],
  [26, 'Vorne Rechts'],
  [7, 'Hinten 1'],
  [8, 'Hinten 2'],
  [9, 'Hinten 3'],
  [12, 'Hinten 4'],
  [10, 'Hinten Links'],
  [11, 'Hinten Rechts'],
  [21, 'Winterreifen 1'],
  [22, 'Winterreifen 2'],
  [23, 'Winterreifen 3'],
  [27, 'Sommerreifen'],
];

const inside: [number, string][] = [
  [2, 'USB, AUX, ESP, 12V'],
  [3, 'Cockpit 1'],
  [4, 'Cockpit 2'],
  [5, 'Cockpit 3'],
  [6, 'Himmel und Brillenfach'],
  [13, 'Klimaanlage und Lüftung'],
  [14, 'Kofferraum mit Abdeckung'],
  [15, 'Kofferraum ohne Abdeckung'],
  [16, 'Motor'],
  [17, 'Motorraum 1'],
  [18, 'Motorraum 2'],
  [19, 'Radio 1'],
  [20, 'Radio 2'],
  [24, 'Rücksitzbank'],
  [25, 'Tacho und Km-Stand'],
];

const tag = (list: [number, string][], category: Category): Photo[] =>
  list.map(([id, title]) => ({ id, title, category }));

export const photos: Photo[] = [...tag(outside, 'aussen'), ...tag(inside, 'innen')];

export const filterPhotos = (list: Photo[], filter: Filter): Photo[] =>
  filter === 'all' ? list : list.filter((p) => p.category === filter);

/** Index of the neighbour photo, wrapping around; `step` is 1 (next) or -1 (previous). */
export const neighbour = (index: number, step: 1 | -1, length: number): number =>
  (index + step + length) % length;
