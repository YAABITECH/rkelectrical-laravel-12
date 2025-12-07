const applicationServerKey ='BBAcOTMNWar85vPicCDp8KboNzjsjqvucVNYVfyKpkR2dHBizflz4HP3Tsewi6qhfNWls-Hg7Pept6_TLkGL8ww';
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/sw.js')
    .then((swReg) => {
        swRegistration = swReg;
      });
  });
}
let deferredPrompt;
function checkPushPermission() {
    const er=document.querySelectorAll('.notiferrdiv');
    for (let i = 0; i < er.length; i++) {
      er[i].style.display = "block";
      er[i].innerHTML = 'loading...';
    }
    if ('PushManager' in window && 'showNotification' in ServiceWorkerRegistration.prototype) 
    {
      return new Promise((resolve, reject) => {
        if (Notification.permission === 'denied') {
          return reject(new Error('Push messages are blocked.'));
        }
        if (Notification.permission === 'granted') {
        return resolve();
        }
        if (Notification.permission === 'default') {
          return Notification.requestPermission().then(result => {
            if (result === 'denied') {
              reject(new Error('Permission denied'));
            } else if (result === 'granted') {
              resolve();
            }
          });
        }
        return reject(new Error('Unknown permission'));
      });
    } else
    {
      addToast('Push notifications are either not supported or disabled on this browser.');
    }
}
async function push_subscribe() {
    try {
        await checkPushPermission();
        const swRegistration = await navigator.serviceWorker.ready;
        const subscription = await swRegistration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: urlBase64ToUint8Array(applicationServerKey),
        });
        const subscription_2 = await push_sendSubscriptionToServer(subscription, 'POST');
        notifsubsdiv('y');
        return subscription_2;
    } catch (e) {
        if (Notification.permission === 'denied') {
          addToast('You have disabled or denied notification. So hereafter, you can only enable notifications in your browser\'s settings.');
          console.warn('Notifications are denied by the user.');
        } else {
          console.error('Impossible to subscribe to push notifications', e);
        }
      notifsubsdiv('y');
    }
  }
function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
    const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');

    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}
async function push_sendSubscriptionToServer(subscription, method) {
    const key = subscription.getKey('p256dh');
    const token = subscription.getKey('auth');
    const contentEncoding = (PushManager.supportedContentEncodings || ['aesgcm'])[0];
  
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const is_phone = isMobileDevice();
    const response = await fetch('/subscribe-push-notification', {
        method,
        headers: new Headers({
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }),
        body: JSON.stringify({
          endpoint: subscription.endpoint,
          publicKey: key ? btoa(String.fromCharCode.apply(null, new Uint8Array(key))) : null,
          authToken: token ? btoa(String.fromCharCode.apply(null, new Uint8Array(token))) : null,
          axn: 'subscribe',
          contentEncoding,
          is_phone: is_phone,
        }),
    });
    const result = await response.json();
    if (!result.success) {
      console.log(result.message);
    }
    return subscription;
  }
document.addEventListener('DOMContentLoaded', () => {
  prmptok('n');
  window.addEventListener('beforeinstallprompt', (e) => {      
    e.preventDefault();
    deferredPrompt = e;
    prmptok('y');
  });

  document.querySelectorAll('.appinsbtn').forEach(item => {
      item.addEventListener('click', () => {
          deferredPrompt.prompt();
          deferredPrompt.userChoice.then((choiceResult) => {
              if (choiceResult.outcome === 'accepted') {
                prmptok('n');
                window.addEventListener('appinstalled', () => {
                  addToast('Welcome to Yaabi Academy App!');
                });
              }
              deferredPrompt = null;
          });
      });
  });
});
function prmptok(x) {
  const appinsdivs = document.querySelectorAll('.appinsdiv');
  appinsdivs.forEach(item => {
    if(x == 'y')
    {
      item.style.display = 'block';
      notifsubsdiv('n');
    } else
    {
      item.style.display = 'none';
      notifsubsdiv('y');
    }
  });
}
function notifsubsdiv(x)
{
  const n=document.querySelectorAll('.notifsubsdiv');
  for (let i = 0; i < n.length; i++) {
    n[i].style.display = "none";
  }
  if(x=='y')
  {
    if ('PushManager' in window && 'showNotification' in ServiceWorkerRegistration.prototype) 
    {
      if (Notification.permission === 'denied') {
        for (let i = 0; i < n.length; i++) {
          n[i].style.display = "block";
        }
        const er=document.querySelectorAll('.notiferrdiv');
        for (let i = 0; i < er.length; i++) {
          er[i].style.display = "block";
        }
      }
      if (Notification.permission === 'granted') {
        for (let i = 0; i < n.length; i++) {
          n[i].style.display = "none";
        }
        const nok=document.querySelectorAll('.notifokdiv');
        for (let i = 0; i < nok.length; i++) {
          nok[i].style.display = "block";
        }
      }
      if (Notification.permission === 'default') {
        for (let i = 0; i < n.length; i++) {
          n[i].style.display = "block";
        }
      }
    } else
    {
      addToast('Push notification is not supported by this browser. Please try in different browser.');
    }
  }
}

function getCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') c = c.substring(1, c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
  }
  return null;
}

function isMobileDevice() {
  return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

if (window.screen && window.screen.orientation && window.screen.orientation.lock) {
  window.addEventListener("orientationchange", function() {
    window.screen.orientation.lock('natural');
  });
}