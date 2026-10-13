import { filterPhotos, neighbour, photos, type Filter, type Photo } from './lib';

const $ = <T extends HTMLElement>(selector: string): T => {
  const el = document.querySelector<T>(selector);
  if (!el) throw new Error(`Missing element: ${selector}`);
  return el;
};

// Hero: rotate the four captions of the legacy slider.
const words = ['KIA Rio 1.4 EX', '87000 Km', '8-fach bereift', '3.850€ VB'];
const caption = $('#caption');
let word = 0;
setInterval(() => {
  word = (word + 1) % words.length;
  caption.textContent = words[word] ?? '';
}, 2500);

// Gallery with filter and lightbox (native <dialog>) including previous/next.
const grid = $('#gallery');
const dialog = $<HTMLDialogElement>('#lightbox');
const lightboxImg = $<HTMLImageElement>('#lightbox img');
const lightboxTitle = $('#lightbox p');
let shown: Photo[] = photos;
let current = 0;

const open = (index: number) => {
  const photo = shown[index];
  if (!photo) return;
  current = index;
  lightboxImg.src = `/images/prettyPhotoImages/${photo.id}.jpg`;
  lightboxImg.alt = photo.title;
  lightboxTitle.textContent = photo.title;
  if (!dialog.open) dialog.showModal();
};

const render = (filter: Filter) => {
  shown = filterPhotos(photos, filter);
  grid.replaceChildren(
    ...shown.map((photo, index) => {
      const figure = document.createElement('figure');
      const button = document.createElement('button');
      const img = document.createElement('img');
      img.src = `/images/prettyPhotoImages/thumb_${photo.id}.jpg`;
      img.alt = photo.title;
      img.loading = 'lazy';
      button.append(img);
      button.addEventListener('click', () => open(index));
      const label = document.createElement('figcaption');
      label.textContent = photo.title;
      figure.append(button, label);
      return figure;
    }),
  );
};

document.querySelectorAll<HTMLButtonElement>('[data-filter]').forEach((button) =>
  button.addEventListener('click', () => {
    document.querySelectorAll('[data-filter]').forEach((b) => b.classList.remove('selected'));
    button.classList.add('selected');
    render(button.dataset.filter as Filter);
  }),
);
$('#prev').addEventListener('click', () => open(neighbour(current, -1, shown.length)));
$('#next').addEventListener('click', () => open(neighbour(current, 1, shown.length)));
$('#close').addEventListener('click', () => dialog.close());
render('all');
