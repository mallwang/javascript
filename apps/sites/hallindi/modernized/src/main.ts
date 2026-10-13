import { counterValue, filterProjects, projects, type Filter } from './lib';

const $ = <T extends HTMLElement>(selector: string): T => {
  const el = document.querySelector<T>(selector);
  if (!el) throw new Error(`Missing element: ${selector}`);
  return el;
};

// Hero: rotate the three captions of the legacy slider.
const words = ['DESIGNEN.', 'VERBESSERN.', 'OPTIMIEREN.'];
const caption = $('#caption');
let word = 0;
setInterval(() => {
  word = (word + 1) % words.length;
  caption.textContent = words[word];
}, 3000);

// Counters: count up once when scrolled into view; the last one ends in "... viele".
const counters = document.querySelectorAll<HTMLElement>('[data-count]');
const counterObserver = new IntersectionObserver((entries) => {
  for (const entry of entries) {
    if (!entry.isIntersecting) continue;
    counterObserver.unobserve(entry.target);
    const el = entry.target as HTMLElement;
    const target = Number(el.dataset.count);
    const start = performance.now();
    const tick = (now: number) => {
      el.textContent = String(counterValue(target, now - start, 2000));
      if (now - start < 2000) requestAnimationFrame(tick);
      else if (el.dataset.suffix) el.textContent = el.dataset.suffix;
    };
    requestAnimationFrame(tick);
  }
});
counters.forEach((el) => counterObserver.observe(el));

// Projects: filter buttons and lightbox (native <dialog>).
const grid = $('#projects');
const dialog = $<HTMLDialogElement>('#lightbox');
const lightboxImg = $<HTMLImageElement>('#lightbox img');

const renderProjects = (filter: Filter) => {
  grid.replaceChildren(
    ...filterProjects(projects, filter).map((p) => {
      const figure = document.createElement('figure');
      const button = document.createElement('button');
      const img = document.createElement('img');
      img.src = `/images/prettyPhotoImages/thumb_${p.image}.jpg`;
      img.alt = p.title;
      button.append(img);
      button.addEventListener('click', () => {
        lightboxImg.src = `/images/prettyPhotoImages/${p.image}.jpg`;
        lightboxImg.alt = p.title;
        dialog.showModal();
      });
      const caption = document.createElement('figcaption');
      const link = document.createElement('a');
      link.href = p.url;
      link.target = '_blank';
      link.rel = 'noopener';
      link.textContent = p.title;
      const date = document.createElement('small');
      date.textContent = p.date;
      caption.append(link, date);
      figure.append(button, caption);
      return figure;
    }),
  );
};

document.querySelectorAll<HTMLButtonElement>('[data-filter]').forEach((button) =>
  button.addEventListener('click', () => {
    document.querySelectorAll('[data-filter]').forEach((b) => b.classList.remove('selected'));
    button.classList.add('selected');
    renderProjects(button.dataset.filter as Filter);
  }),
);
dialog.addEventListener('click', () => dialog.close());
renderProjects('all');

// Contact form: no backend, hand the message to the visitor's mail client.
$<HTMLFormElement>('#contact').addEventListener('submit', (event) => {
  event.preventDefault();
  const data = new FormData(event.target as HTMLFormElement);
  const body = `${data.get('message')}\n\n${data.get('name')} <${data.get('email')}>`;
  location.href = `mailto:info@hallindi.de?subject=${encodeURIComponent(String(data.get('subject') ?? ''))}&body=${encodeURIComponent(body)}`;
});
