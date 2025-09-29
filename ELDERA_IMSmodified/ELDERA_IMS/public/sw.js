/**
 * Service Worker for ELDERA IMS
 * Provides offline caching and background sync
 */
const CACHE_NAME = 'eldera-v1.0.0';
const STATIC_CACHE_NAME = 'eldera-static-v1.0.0';
const DYNAMIC_CACHE_NAME = 'eldera-dynamic-v1.0.0';

// Static assets to cache
const STATIC_ASSETS = [
    '/',
    '/Dashboard',
    '/Seniors',
    '/js/cache-manager.js',
    '/css/app.css',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css'
];

// API endpoints to cache
const API_ENDPOINTS = [
    '/api/seniors',
    '/api/events',
    '/api/barangay-stats'
];

// Install event - cache static assets
self.addEventListener('install', event => {
    console.log('Service Worker installing...');
    
    event.waitUntil(
        caches.open(STATIC_CACHE_NAME)
            .then(cache => {
                console.log('Caching static assets');
                return cache.addAll(STATIC_ASSETS);
            })
            .then(() => {
                console.log('Static assets cached successfully');
                return self.skipWaiting();
            })
            .catch(error => {
                console.error('Failed to cache static assets:', error);
            })
    );
});

// Activate event - clean up old caches
self.addEventListener('activate', event => {
    console.log('Service Worker activating...');
    
    event.waitUntil(
        caches.keys()
            .then(cacheNames => {
                return Promise.all(
                    cacheNames.map(cacheName => {
                        if (cacheName !== STATIC_CACHE_NAME && cacheName !== DYNAMIC_CACHE_NAME) {
                            console.log('Deleting old cache:', cacheName);
                            return caches.delete(cacheName);
                        }
                    })
                );
            })
            .then(() => {
                console.log('Service Worker activated');
                return self.clients.claim();
            })
    );
});

// Fetch event - serve from cache or network
self.addEventListener('fetch', event => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET requests
    if (request.method !== 'GET') {
        return;
    }

    // Handle different types of requests
    if (isStaticAsset(request)) {
        event.respondWith(handleStaticAsset(request));
    } else if (isApiRequest(request)) {
        event.respondWith(handleApiRequest(request));
    } else if (isPageRequest(request)) {
        event.respondWith(handlePageRequest(request));
    }
});

// Check if request is for static asset
function isStaticAsset(request) {
    const url = new URL(request.url);
    return url.pathname.match(/\.(css|js|png|jpg|jpeg|gif|svg|woff|woff2|ttf|eot)$/);
}

// Check if request is for API
function isApiRequest(request) {
    const url = new URL(request.url);
    return url.pathname.startsWith('/api/');
}

// Check if request is for page
function isPageRequest(request) {
    const url = new URL(request.url);
    return url.pathname.startsWith('/') && !url.pathname.includes('.');
}

// Handle static asset requests
async function handleStaticAsset(request) {
    try {
        // Try cache first
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }

        // Fetch from network
        const networkResponse = await fetch(request);
        
        // Cache successful responses
        if (networkResponse.ok) {
            const cache = await caches.open(STATIC_CACHE_NAME);
            cache.put(request, networkResponse.clone());
        }

        return networkResponse;
    } catch (error) {
        console.error('Static asset fetch error:', error);
        return new Response('Asset not available offline', { status: 404 });
    }
}

// Handle API requests
async function handleApiRequest(request) {
    try {
        // Try network first for API requests
        const networkResponse = await fetch(request);
        
        if (networkResponse.ok) {
            // Cache successful API responses
            const cache = await caches.open(DYNAMIC_CACHE_NAME);
            cache.put(request, networkResponse.clone());
        }

        return networkResponse;
    } catch (error) {
        console.log('Network failed, trying cache for API:', request.url);
        
        // Fallback to cache
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }

        // Return offline response
        return new Response(JSON.stringify({
            error: 'Offline',
            message: 'This data is not available offline'
        }), {
            status: 503,
            headers: { 'Content-Type': 'application/json' }
        });
    }
}

// Handle page requests
async function handlePageRequest(request) {
    try {
        // Try network first
        const networkResponse = await fetch(request);
        
        if (networkResponse.ok) {
            // Cache successful page responses
            const cache = await caches.open(DYNAMIC_CACHE_NAME);
            cache.put(request, networkResponse.clone());
        }

        return networkResponse;
    } catch (error) {
        console.log('Network failed, trying cache for page:', request.url);
        
        // Fallback to cache
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }

        // Fallback to index page
        const indexResponse = await caches.match('/');
        if (indexResponse) {
            return indexResponse;
        }

        // Return offline page
        return new Response(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>ELDERA IMS - Offline</title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <style>
                    body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
                    .offline-message { color: #666; font-size: 18px; }
                </style>
            </head>
            <body>
                <h1>ELDERA IMS</h1>
                <div class="offline-message">
                    <p>You are currently offline.</p>
                    <p>Some features may not be available.</p>
                    <p>Please check your internet connection and try again.</p>
                </div>
            </body>
            </html>
        `, {
            status: 200,
            headers: { 'Content-Type': 'text/html' }
        });
    }
}

// Background sync for form submissions
self.addEventListener('sync', event => {
    if (event.tag === 'form-sync') {
        event.waitUntil(syncFormData());
    }
});

// Sync form data when back online
async function syncFormData() {
    try {
        const formData = await getStoredFormData();
        if (formData && formData.length > 0) {
            for (const data of formData) {
                try {
                    await fetch(data.url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': data.csrfToken
                        },
                        body: JSON.stringify(data.formData)
                    });
                    
                    // Remove synced data
                    await removeStoredFormData(data.id);
                } catch (error) {
                    console.error('Failed to sync form data:', error);
                }
            }
        }
    } catch (error) {
        console.error('Background sync error:', error);
    }
}

// Store form data for later sync
async function storeFormData(url, formData, csrfToken) {
    try {
        const data = {
            id: Date.now(),
            url: url,
            formData: formData,
            csrfToken: csrfToken,
            timestamp: Date.now()
        };
        
        // Store in IndexedDB or localStorage
        const stored = JSON.parse(localStorage.getItem('pending_forms') || '[]');
        stored.push(data);
        localStorage.setItem('pending_forms', JSON.stringify(stored));
    } catch (error) {
        console.error('Failed to store form data:', error);
    }
}

// Get stored form data
async function getStoredFormData() {
    try {
        return JSON.parse(localStorage.getItem('pending_forms') || '[]');
    } catch (error) {
        console.error('Failed to get stored form data:', error);
        return [];
    }
}

// Remove stored form data
async function removeStoredFormData(id) {
    try {
        const stored = JSON.parse(localStorage.getItem('pending_forms') || '[]');
        const filtered = stored.filter(item => item.id !== id);
        localStorage.setItem('pending_forms', JSON.stringify(filtered));
    } catch (error) {
        console.error('Failed to remove stored form data:', error);
    }
}
