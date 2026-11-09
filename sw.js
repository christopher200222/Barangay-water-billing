/* =====================================================================
   @@PWA  @@SERVICE-WORKER
   PHONE APP > Service worker: keeps the app's look (CSS / JS / logo) on the phone, shows a friendly page when there is no
   internet, and keeps a copy of the WATER READER pages so meter readings can still be typed in the field.
   - It never keeps pages of other roles (consumer, treasurer, admin) - a shared phone must not show someone else's bills.
   - It never touches payments, the sync API, setup, or the payment screenshots.
   FILE: sw.js   (change VERSION when you edit this file so phones pick up the new one)
   ===================================================================== */
const VERSION = 'tigbao-v2';
const STATIC_CACHE = VERSION + '-static';
const PAGE_CACHE = VERSION + '-pages';
const SCOPE = self.registration.scope;                       // always ends with "/"
const SCOPE_PATH = new URL(SCOPE).pathname;
const OFFLINE_URL = SCOPE + 'offline.html';
const KEEP_PAGES = /^meter-reader\/(readings|dashboard)\.php$/;   // pages kept for offline use
const NEVER = /^(api\/|sync\/|setup\.php|storage\/|manifest\.php|sw\.js|customer\/(pay|qr-pay|checkout|pay-return)\.php|treasurer\/|admin\/)/;

const PRECACHE = [
  'offline.html',
  'assets/css/style.css', 'assets/css/theme.css', 'assets/css/compact.css', 'assets/css/mobile.css',
  'assets/js/password-toggle.js', 'assets/js/phone-input.js', 'assets/js/table-enhance.js', 'assets/js/fit-inputs.js',
  'assets/js/qr-render.js', 'assets/js/pwa.js', 'assets/js/offline-readings.js',
  'assets/images/logo-seal.png', 'assets/icons/icon-192.png'
].map(function (p) { return SCOPE + p; });

self.addEventListener('install', function (event) {
  event.waitUntil(
    caches.open(STATIC_CACHE)
      .then(function (cache) { return Promise.all(PRECACHE.map(function (u) { return cache.add(u).catch(function () {}); })); })
      .then(function () { return self.skipWaiting(); })
  );
});

self.addEventListener('activate', function (event) {
  event.waitUntil(
    caches.keys()
      .then(function (keys) { return Promise.all(keys.filter(function (k) { return k.indexOf(VERSION) !== 0; }).map(function (k) { return caches.delete(k); })); })
      .then(function () { return self.clients.claim(); })
  );
});

// pwa.js tells us to forget saved pages (the login page was opened = nobody is signed in)
self.addEventListener('message', function (event) {
  if (event.data && event.data.type === 'clear-pages') { event.waitUntil(caches.delete(PAGE_CACHE)); }
});

function relPath(url) { return url.pathname.indexOf(SCOPE_PATH) === 0 ? url.pathname.slice(SCOPE_PATH.length) : url.pathname; }

async function offlinePage() {
  const hit = await caches.match(OFFLINE_URL);
  if (!hit) { return Response.error(); }
  const html = (await hit.text()).replace('<head>', '<head><base href="' + SCOPE + '">');   // so the links on it work from any address
  return new Response(html, { status: 200, headers: { 'Content-Type': 'text/html; charset=utf-8' } });
}

async function navigate(request, rel) {
  try {
    const res = await fetch(request);
    if (res.ok && !res.redirected && res.type === 'basic' && KEEP_PAGES.test(rel)) {
      const cache = await caches.open(PAGE_CACHE);
      cache.put(request, res.clone());
    }
    return res;
  } catch (err) {
    if (KEEP_PAGES.test(rel)) {
      const cache = await caches.open(PAGE_CACHE);
      const exact = await cache.match(request);
      if (exact) { return exact; }
      const any = await cache.match(request, { ignoreSearch: true });
      if (any) { return any; }
    }
    return offlinePage();
  }
}

async function staleWhileRevalidate(request) {
  const cache = await caches.open(STATIC_CACHE);
  const hit = await cache.match(request);
  const fresh = fetch(request).then(function (res) {
    if (res && res.ok && res.type === 'basic') { cache.put(request, res.clone()); }
    return res;
  }).catch(function () { return hit; });
  return hit || fresh;
}

self.addEventListener('fetch', function (event) {
  const request = event.request;
  if (request.method !== 'GET') { return; }                       // forms / payments / saves always go straight to the server
  const url = new URL(request.url);
  if (url.origin !== self.location.origin || url.pathname.indexOf(SCOPE_PATH) !== 0) { return; }
  const rel = relPath(url);
  if (NEVER.test(rel) && request.mode !== 'navigate') { return; }
  if (request.mode === 'navigate') {
    event.respondWith(navigate(request, rel));
    return;
  }
  if (/^assets\/(css|js|images|icons)\//.test(rel)) {
    event.respondWith(staleWhileRevalidate(request));
  }
});
