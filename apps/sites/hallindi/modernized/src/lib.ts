export type Kind = 'websites' | 'apps';
export type Filter = Kind | 'all';

export interface Project {
  title: string;
  url: string;
  date: string;
  kind: Kind;
  image: string;
}

export const projects: Project[] = [
  {
    title: 'i-use-my-brain.com',
    url: 'http://www.i-use-my-brain.com',
    date: '31.03.2014',
    kind: 'websites',
    image: 'pic05',
  },
  {
    title: 'photovoltaik-allwang.de',
    url: 'http://www.photovoltaik-allwang.de',
    date: '30.09.2014',
    kind: 'websites',
    image: 'pic06',
  },
  {
    title: 'Wo ist was?! - PLZ',
    url: 'https://play.google.com/store/apps/details?id=de.iusemybrain.woistwas',
    date: '17.06.2013',
    kind: 'apps',
    image: 'pic1',
  },
  {
    title: 'Wo ist was?! - Besondere Orte',
    url: 'https://play.google.com/store/apps/details?id=de.iusemybrain.woistwas_bo',
    date: '26.06.2013',
    kind: 'apps',
    image: 'pic2',
  },
  {
    title: 'MySunPower',
    url: 'https://play.google.com/store/apps/details?id=de.iusemybrain.solarappnew',
    date: '16.10.2014',
    kind: 'apps',
    image: 'pic3',
  },
];

export const filterProjects = (list: Project[], filter: Filter): Project[] =>
  filter === 'all' ? list : list.filter((p) => p.kind === filter);

/** Linear count-up from 0 to `target` over `duration` ms. */
export const counterValue = (target: number, elapsed: number, duration: number): number =>
  Math.round(target * Math.min(Math.max(elapsed / duration, 0), 1));
