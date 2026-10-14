import { isLocked, lockedMessage, shuffledDays } from './calendar';

// ponytail: the unlock check is client-side only (as in the legacy app), photos are public files.
const requiredMonth = Number(import.meta.env.VITE_REQUIRED_MONTH ?? 11);
const $ = <T extends HTMLElement>(id: string) => document.getElementById(id) as T;

const toast = $('toast');
let toastTimer: number;
function showToast(text: string) {
  toast.textContent = text;
  toast.classList.add('show');
  clearTimeout(toastTimer);
  toastTimer = window.setTimeout(() => toast.classList.remove('show'), 3000);
}

const photo = $<HTMLDialogElement>('photo');
const photoImg = $<HTMLImageElement>('photo-img');
const qr = $<HTMLDialogElement>('qr');

function openDay(day: number) {
  const now = new Date(); // read per click, replaces the legacy pull-to-refresh
  if (isLocked(day, now, requiredMonth)) {
    showToast(lockedMessage(now, requiredMonth));
    return;
  }
  $('photo-title').textContent = `Tag ${day}`;
  photoImg.alt = `Foto zu Tag ${day}`;
  photoImg.src = `/assets/images/preview/${day}.jpg`;
  photoImg.dataset.day = String(day);
  photo.showModal();
}

const calendar = $('calendar');
for (const day of shuffledDays()) {
  const door = document.createElement('button');
  door.type = 'button';
  door.className = 'day';
  door.dataset.day = String(day);
  door.innerHTML = `<span class="rib v"></span><span class="rib h"></span><span>${day}</span>`;
  calendar.append(door);
}
function markLocked() {
  const now = new Date();
  for (const door of calendar.querySelectorAll<HTMLElement>('.day')) {
    door.classList.toggle('closed', isLocked(Number(door.dataset.day), now, requiredMonth));
  }
}
markLocked();
document.addEventListener('visibilitychange', markLocked);
calendar.addEventListener('click', (e) => {
  const door = (e.target as Element).closest<HTMLElement>('.day');
  if (door) {
    markLocked();
    openDay(Number(door.dataset.day));
  }
});

photoImg.addEventListener('click', () =>
  window.open(`/assets/images/full/${photoImg.dataset.day}.jpg`, '_blank', 'noopener'),
);
$('share').addEventListener('click', () => qr.showModal());
for (const dialog of [photo, qr]) {
  dialog.querySelector('.close')!.addEventListener('click', () => dialog.close());
  dialog.addEventListener('click', (e) => e.target === dialog && dialog.close()); // backdrop
}

// 12 snowflakes: [left %, fall delay s, shake delay s]
const flakes = [
  [1, 0, 0],
  [10, 1, 1],
  [20, 6, 0.5],
  [30, 4, 2],
  [40, 2, 2],
  [50, 8, 3],
  [60, 6, 2],
  [70, 2.5, 1],
  [80, 1, 0],
  [90, 3, 1.5],
  [25, 2, 0],
  [65, 4, 2.5],
];
$('snow').innerHTML = flakes
  .map(
    ([left, fall, shake], i) =>
      `<span class="snowflake" style="left:${left}%;animation-delay:${fall}s,${shake}s">${i % 2 ? '❆' : '❅'}</span>`,
  )
  .join('');

if (import.meta.env.PROD && 'serviceWorker' in navigator) {
  navigator.serviceWorker.register('/sw.js');
}
