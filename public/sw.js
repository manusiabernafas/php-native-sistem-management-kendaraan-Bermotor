const CACHE_NAME = 'simanda-v1-cache';

// Kita cache file-file statis penting agar aplikasi tetap terbuka tanpa internet
const ASSETS_TO_CACHE = [
    './',
    './index.php',
    './css/style.css',
    './js/app.js',
    './img/icon.svg',
    './manifest.json',
    'https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&family=Inter:wght@400;500;600&display=swap'
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            console.log('Opened cache');
            // Menghindari fail jika ada URL eksternal yang gagal
            return Promise.all(ASSETS_TO_CACHE.map(url => {
                return cache.add(url).catch(err => console.log('Failed to cache:', url));
            }));
        })
    );
});

self.addEventListener('fetch', event => {
    // Strategi: Network First, falling back to Cache
    // Hal ini agar data armada/laporan selalu fresh jika ada internet
    if (event.request.method !== 'GET') return;

    event.respondWith(
        fetch(event.request)
            .then(response => {
                // Jangan cache jika respons bukan HTML/Assets sukses
                if (!response || response.status !== 200 || response.type !== 'basic') {
                    return response;
                }
                
                // Kloning respons untuk disimpan di cache
                const responseToCache = response.clone();
                caches.open(CACHE_NAME).then(cache => {
                    cache.put(event.request, responseToCache);
                });
                
                return response;
            })
            .catch(() => {
                // Jika jaringan mati, kembalikan dari cache
                return caches.match(event.request);
            })
    );
});

self.addEventListener('activate', event => {
    // Hapus cache lama jika CACHE_NAME berubah
    const cacheAllowlist = [CACHE_NAME];
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheAllowlist.indexOf(cacheName) === -1) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});
