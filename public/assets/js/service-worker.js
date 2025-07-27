self.addEventListener('install', event => {
    event.waitUntil(
        caches.open('offline-cache').then(cache => {
            return cache.addAll([
                '/',
                '/offline', // your custom offline page
                '/css/app.css',
                '/js/app.js',
            ]);
        })
    );
});

self.addEventListener('fetch', event => {
    event.respondWith(
        fetch(event.request).catch(() => {
            return caches.match('/offline');
        })
    );
});
