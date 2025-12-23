const CACHE_NAME = 'brewly-cache-v1';
const ASSETS_TO_CACHE = [
  '/',
  '/offline.html' // A fallback page
];

// A placeholder offline page. In a real scenario, you'd create this file.
const OFFLINE_PAGE_CONTENT = `
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brewly Offline</title>
    <style>
        body { font-family: sans-serif; text-align: center; padding-top: 50px; }
    </style>
</head>
<body>
    <h1>You are offline</h1>
    <p>This page cannot be displayed because you are not connected to the internet.</p>
</body>
</html>
`;


// Install event: cache the app shell and the offline page
self.addEventListener('install', (event) => {
  console.log('[Service Worker] Install');
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      console.log('[Service Worker] Caching app shell');
      // Cache the placeholder offline page content
      cache.put('/offline.html', new Response(OFFLINE_PAGE_CONTENT, { headers: { 'Content-Type': 'text/html' } }));
      return cache.addAll(ASSETS_TO_CACHE);
    })
  );
  self.skipWaiting();
});

// Activate event: clean up old caches
self.addEventListener('activate', (event) => {
  console.log('[Service Worker] Activate');
  event.waitUntil(
    caches.keys().then((keyList) => {
      return Promise.all(keyList.map((key) => {
        if (key !== CACHE_NAME) {
          console.log('[Service Worker] Removing old cache', key);
          return caches.delete(key);
        }
      }));
    })
  );
  return self.clients.claim();
});

// Fetch event: serve content from cache or network
self.addEventListener('fetch', (event) => {
  const url = new URL(event.request.url);

  // Always bypass the service worker for non-GET requests and for the login page.
  // This prevents CSRF token issues with cached forms.
  // Let the browser handle these requests, ensuring they always go to the network.
  if (event.request.method !== 'GET' || url.pathname === '/login') {
    return;
  }

  // For navigation requests (HTML pages), use network first, then cache, with an offline fallback
  if (event.request.mode === 'navigate') {
    event.respondWith(
      fetch(event.request)
        .catch(() => {
          // If network fails, try the cache
          return caches.match(event.request)
            .then((response) => {
              // If it's in the cache, serve it. Otherwise, show the offline page.
              return response || caches.match('/offline.html');
            });
        })
    );
    return;
  }

  // For other requests (CSS, JS, images, API calls), use a stale-while-revalidate strategy
  event.respondWith(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.match(event.request).then((response) => {
        // Fetch from network in the background to update the cache
        const fetchPromise = fetch(event.request).then((networkResponse) => {
          // Check if we received a valid response
          if(networkResponse && networkResponse.status === 200) {
            cache.put(event.request, networkResponse.clone());
          }
          return networkResponse;
        });

        // Return cached response if available, otherwise wait for the network
        return response || fetchPromise;
      });
    })
  );
});
