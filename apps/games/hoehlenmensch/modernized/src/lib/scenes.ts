// Image-map hotspots ported from the legacy scene pages. `to` is a scene name or a work id.
type Area = { coords: string; title: string; scene?: string; work?: number };
export const SCENES: Record<string, { title: string; image: string; areas: Area[] }> = {
  home: {
    title: 'Cave',
    image: 'Hauptseite.jpg',
    areas: [
      { coords: '54,10,137,157', title: 'Fell wood', work: 5 },
      { coords: '440,370,509,476', title: 'Fire', scene: 'fire' },
      { coords: '291,505,468,572', title: 'Bed', scene: 'bed' },
      { coords: '645,63,752,146', title: 'Camp', scene: 'camp' },
      { coords: '715,451,831,567', title: 'Hunt', work: 1 },
      { coords: '86,338,244,537', title: 'Surroundings', scene: 'surroundings' },
    ],
  },
  surroundings: {
    title: 'Surroundings',
    image: 'Umgebung_gemaessigt.jpg',
    areas: [
      { coords: '310,1,705,235', title: 'Forest', scene: 'forest' },
      { coords: '368,511,521,566', title: 'Back to the cave', scene: 'home' },
      ...[
        '0,225,104,393',
        '104,250,245,433',
        '245,290,394,467',
        '394,334,619,483',
        '619,374,888,551',
      ].map((coords) => ({ coords, title: 'Fish', work: 3 })),
    ],
  },
  forest: {
    title: 'Forest',
    image: 'Wald.jpg',
    areas: [
      { coords: '25,345,231,497', title: 'Gather fruit', work: 2 },
      { coords: '252,306,363,430', title: 'Hunt', work: 1 },
      { coords: '414,231,597,419', title: 'Fell wood', work: 5 },
      { coords: '589,391,781,526', title: 'Gather mushrooms', work: 4 },
      { coords: '358,509,531,568', title: 'Back to the surroundings', scene: 'surroundings' },
    ],
  },
  camp: {
    title: 'Camp',
    image: 'Lager.jpg',
    areas: [
      { coords: '352,154,658,424', title: 'Fell wood', work: 5 },
      { coords: '22,233,348,388', title: 'Cut stone', work: 6 },
      { coords: '367,498,527,568', title: 'Back to the cave', scene: 'home' },
    ],
  },
  bed: {
    title: 'Bed',
    image: 'Bett.jpg',
    areas: [{ coords: '30,505,198,568', title: 'Back to the cave', scene: 'home' }],
  },
  fire: {
    title: 'Fire',
    image: 'Feuer_an.jpg',
    areas: [{ coords: '360,505,531,567', title: 'Back to the cave', scene: 'home' }],
  },
};
