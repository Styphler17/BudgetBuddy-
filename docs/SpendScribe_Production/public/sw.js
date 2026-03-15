const CACHE_NAME = 'spendscribe-v1';
const ASSETS_TO_CACHE = [
  './manifest.json',
  './css/style.css',
  './css/animations.css',
  './favicon.png',
  './SpendScribe.png'
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(ASSETS_TO_CACHE);
    })
  );
});

self.addEventListener('fetch', (event) => {
  event.respondWith(
    caches.match(event.request).then((response) => {
      return response || fetch(event.request);
    })
  );
});
