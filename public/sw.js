'use strict';

const cacheName = 'cache-v1-1';
const dyncache = 'dyncache-v1-1';
const assets = [
  '/fallback',
  '/images/no-internet.jpg'
];

self.addEventListener('install', e => {
  e.waitUntil(
    caches.open(cacheName)
      .then(cache => {
        return cache.addAll(assets);
      })
  );
});

self.addEventListener('activate', e => {
  e.waitUntil(
    caches.keys().then(keys => {
      return Promise.all(keys.
        filter(key => key !== cacheName)
        .map(key => caches.delete(key))
      )
    })
  );
});

self.addEventListener('fetch', e => {
  e.respondWith(
    caches.match(e.request)
      .then(response => {
        if (response) {
          return response;
        }
        return fetch(e.request);
      }
    ).catch(() => caches.match('/fallback'))
  );
});

self.addEventListener('push', e => {
  if (!(self.Notification && self.Notification.permission === 'granted')) {
    return;
  }
  if (e.data) {
    const edata = e.data.text();
    const ndata=JSON.parse(edata);
    const promiseChain = self.registration.showNotification(ndata.title, ndata.options);
    e.waitUntil(promiseChain);
  }
});

self.addEventListener('notificationclick', e => {
  const nf = e.notification;
  nf.close();
  if (!e.action) {
    if(nf.data && nf.data.url)
    {
      const url = nf.data.url;
      urltoopen(url,e);
    }
    return;
  }
  if(e.action === 'close'){
    return;
  }
  if(nf.data && nf.data.listurl)
  {
    const listurl = nf.data.listurl;
    Object.keys(listurl).forEach((k) => {
      if(k===e.action)
      {
        const actionurl=listurl[k];
        urltoopen(actionurl,e);
      }
    });
  }
});

function urltoopen(url,e) {
  const urlToOpen = new URL(url, self.location.origin).href;
  const promiseChain = clients.matchAll({
    type: 'window',
    includeUncontrolled: true
  })
  .then((windowClients) => {
    let matchingClient = null;

    for (let i = 0; i < windowClients.length; i++) {
      const windowClient = windowClients[i];
      if (windowClient.url === urlToOpen) {
        matchingClient = windowClient;
        break;
      }
    }

    if (matchingClient) {
      return matchingClient.focus();
    } else {
      return clients.openWindow(urlToOpen);
    }
  });
  e.waitUntil(promiseChain);
}
