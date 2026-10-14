/* global self, caches, fetch */
// Lazy cache: serve cached files first and refresh them in the background (works offline).
// ponytail: no precache or update prompt, bump CACHE to drop old files.
const CACHE = 'adventskalender-v1';

self.addEventListener('install', () => self.skipWaiting());
self.addEventListener('activate', (e) =>
  e.waitUntil(
    caches
      .keys()
      .then((keys) => Promise.all(keys.filter((k) => k !== CACHE).map((k) => caches.delete(k))))
      .then(() => self.clients.claim()),
  ),
);
self.addEventListener('fetch', (e) => {
  if (e.request.method !== 'GET' || !e.request.url.startsWith(self.location.origin)) return;
  e.respondWith(
    caches.open(CACHE).then(async (cache) => {
      const cached = await cache.match(e.request);
      const fresh = fetch(e.request)
        .then((res) => (res.ok && cache.put(e.request, res.clone()), res))
        .catch(() => cached);
      return cached ?? fresh;
    }),
  );
});
