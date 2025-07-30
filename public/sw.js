// Service Worker pour N-C BTP
// Version de développement - vide pour éviter les erreurs

const CACHE_NAME = 'nc-btp-v1';
const urlsToCache = [
    '/',
    '/css/app.css',
    '/js/app.js',
    '/js/photo-gallery.js',
    '/favicon.ico',
    // Ajoutez vos ressources importantes
];

// Installation - mise en cache des ressources
self.addEventListener('install', function(event) {
    console.log('Service Worker: Installation en cours...');
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(function(cache) {
                console.log('Service Worker: Mise en cache des ressources');
                return cache.addAll(urlsToCache);
            })
            .catch(function(error) {
                console.error('Service Worker: Erreur lors de la mise en cache:', error);
            })
    );
});

// Activation - nettoyage des anciens caches
self.addEventListener('activate', function(event) {
    console.log('Service Worker: Activation en cours...');
    event.waitUntil(
        caches.keys().then(function(cacheNames) {
            return Promise.all(
                cacheNames.map(function(cacheName) {
                    if (cacheName !== CACHE_NAME) {
                        console.log('Service Worker: Suppression de l\'ancien cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});

// Fetch - servir depuis le cache si disponible
self.addEventListener('fetch', function(event) {
    // Ne traiter que les requêtes GET
    if (event.request.method !== 'GET') {
        return;
    }

    // Ignorer les requêtes vers l'API
    if (event.request.url.includes('/api/')) {
        return;
    }

    // Ignorer les requêtes de développement
    if (event.request.url.includes('localhost') || event.request.url.includes('127.0.0.1')) {
        return;
    }

    event.respondWith(
        caches.match(event.request)
            .then(function(response) {
                // Si la ressource est en cache, la retourner
                if (response) {
                    console.log('Service Worker: Ressource servie depuis le cache:', event.request.url);
                    return response;
                }

                // Sinon, faire la requête réseau
                console.log('Service Worker: Requête réseau pour:', event.request.url);
                return fetch(event.request)
                    .then(function(response) {
                        // Vérifier que la réponse est valide
                        if (!response || response.status !== 200 || response.type !== 'basic') {
                            return response;
                        }

                        // Cloner la réponse car elle ne peut être utilisée qu'une fois
                        var responseToCache = response.clone();

                        // Mettre en cache la nouvelle ressource
                        caches.open(CACHE_NAME)
                            .then(function(cache) {
                                cache.put(event.request, responseToCache);
                                console.log('Service Worker: Nouvelle ressource mise en cache:', event.request.url);
                            });

                        return response;
                    })
                    .catch(function(error) {
                        console.error('Service Worker: Erreur lors de la requête réseau:', error);
                        
                        // Pour les pages HTML, retourner une page d'erreur personnalisée
                        if (event.request.destination === 'document') {
                            return caches.match('/offline.html');
                        }
                        
                        return new Response('Erreur réseau', {
                            status: 503,
                            statusText: 'Service Unavailable'
                        });
                    });
            })
    );
});

// Gestion des messages depuis l'application
self.addEventListener('message', function(event) {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
    
    if (event.data && event.data.type === 'GET_VERSION') {
        event.ports[0].postMessage({ version: CACHE_NAME });
    }
});

// Gestion des erreurs
self.addEventListener('error', function(event) {
    console.error('Service Worker: Erreur:', event.error);
});

// Gestion des rejets de promesses non gérés
self.addEventListener('unhandledrejection', function(event) {
    console.error('Service Worker: Promesse rejetée non gérée:', event.reason);
}); 