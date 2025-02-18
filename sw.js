// sw.js

const CACHE_NAME = 'video-cache-v1';
const MAX_VIDEO_AGE = 24 * 60 * 60 * 1000;  // 1 day in milliseconds
const MAX_CACHE_ITEMS = 5;                  // maximum allowed cached videos

/******************
 * IndexedDB Setup
 ******************/
function openDB() {
  return new Promise((resolve, reject) => {
    const request = indexedDB.open('video-cache-metadata', 1);
    request.onupgradeneeded = event => {
      const db = event.target.result;
      if (!db.objectStoreNames.contains('videos')) {
        db.createObjectStore('videos', {keyPath: 'url'});
      }
    };
    request.onsuccess = event => resolve(event.target.result);
    request.onerror = event => reject(event.target.error);
  });
}

/**
 * Updates or creates the metadata record for a cached video.
 * @param {string} url - The video request URL.
 */
function updateCacheMetadata(url) {
  openDB()
      .then(db => {
        const tx = db.transaction('videos', 'readwrite');
        const store = tx.objectStore('videos');
        store.put({url: url, timestamp: Date.now()});
      })
      .catch(err => console.error('Error updating metadata:', err));
}

/**
 * Deletes cached videos that have exceeded the maximum age.
 */
function cleanUpExpiredVideos() {
  openDB()
      .then(db => {
        const tx = db.transaction('videos', 'readwrite');
        const store = tx.objectStore('videos');
        const request = store.getAll();
        request.onsuccess = event => {
          const items = event.target.result;
          const now = Date.now();
          items.forEach(item => {
            if (now - item.timestamp > MAX_VIDEO_AGE) {
              store.delete(item.url);
              caches.open(CACHE_NAME).then(cache => {
                cache.delete(item.url).then(deleted => {
                  if (deleted) {
                    console.log('Deleted expired video from cache:', item.url);
                  }
                });
              });
            }
          });
        };
        request.onerror = event => {
          console.error('Error reading from IndexedDB:', event);
        };
      })
      .catch(err => console.error('Error during cleanup:', err));
}

/**
 * Enforces a maximum number of cached videos by deleting the oldest entries.
 * @param {number} limit - The maximum number of allowed cached videos.
 */
function enforceCacheLimit(limit = MAX_CACHE_ITEMS) {
  openDB()
      .then(db => {
        const tx = db.transaction('videos', 'readwrite');
        const store = tx.objectStore('videos');
        const request = store.getAll();
        request.onsuccess = event => {
          const items = event.target.result;
          if (items.length > limit) {
            // Sort videos by their cached timestamp (oldest first)
            items.sort((a, b) => a.timestamp - b.timestamp);
            const itemsToDelete = items.slice(0, items.length - limit);
            itemsToDelete.forEach(item => {
              store.delete(item.url);
              caches.open(CACHE_NAME).then(cache => {
                cache.delete(item.url).then(deleted => {
                  if (deleted) {
                    console.log('Deleted video due to cache limit:', item.url);
                  }
                });
              });
            });
          }
        };
        request.onerror = event => {
          console.error('Error enforcing cache limit:', event);
        };
      })
      .catch(err => console.error('Error enforcing limit:', err));
}

/***********************
 * Service Worker Events
 ***********************/

// Installation: Open (or create) the cache and immediately take control.
self.addEventListener('install', event => {
  self.skipWaiting();
  event.waitUntil(caches.open(CACHE_NAME).then(cache => {
    console.log('Service Worker installed. Cache opened:', CACHE_NAME);
    return cache;
  }));
});

// Activation: Clean up any outdated caches.
self.addEventListener('activate', event => {
  event.waitUntil(caches.keys().then(cacheNames => {
    return Promise.all(
        cacheNames.filter(name => name !== CACHE_NAME).map(name => {
          console.log('Deleting outdated cache:', name);
          return caches.delete(name);
        }));
  }));
  self.clients.claim();
});

// Fetch: Intercept video requests and apply caching logic.
self.addEventListener('fetch', event => {
  // Debug log for every fetch event.
  console.log(
      'Fetch event:', event.request.url,
      'Destination:', event.request.destination);

  // Adjust the matching if needed (e.g., include 'media' if applicable).
  if (event.request.destination === 'video' ||
      event.request.destination === 'media') {
    event.respondWith(caches.match(event.request).then(cachedResponse => {
      if (cachedResponse) {
        console.log('Serving cached video:', event.request.url);
        return cachedResponse;
      }
      // Otherwise, fetch from the network.
      return fetch(event.request)
          .then(networkResponse => {
            if (!networkResponse || networkResponse.status !== 200) {
              return networkResponse;
            }
            const responseClone = networkResponse.clone();
            caches.open(CACHE_NAME).then(cache => {
              cache.put(event.request, responseClone);
              // Save the timestamp for expiration.
              updateCacheMetadata(event.request.url);
              // Cleanup expired items and enforce a maximum cache limit.
              cleanUpExpiredVideos();
              enforceCacheLimit();
            });
            return networkResponse;
          })
          .catch(error => {
            console.error('Fetching video failed:', error);
            // Optionally, return a fallback response here.
          });
    }));
  } else {
    // For non-video requests, perform a normal fetch.
    event.respondWith(fetch(event.request));
  }
});
