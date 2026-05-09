// PWA Registration and Install Prompt Handler

let deferredPrompt;
let installButton = null;

// Register Service Worker
// Note: Service workers require HTTPS or localhost
// HTTP on local IP addresses won't work
function registerServiceWorker() {
  // Check if service workers are supported
  if (!('serviceWorker' in navigator)) {
    console.warn('[PWA] Service Workers not supported in this browser');
    return;
  }

  // Check if we're in a secure context (HTTPS or localhost)
  if (!window.isSecureContext) {
    console.warn('[PWA] Service Workers require HTTPS or localhost. Current URL:', window.location.href);
    console.warn('[PWA] For mobile testing, use ngrok or access via localhost on your computer');
    // Still try to register - some browsers may allow it
  }

  window.addEventListener('load', () => {
    navigator.serviceWorker
      .register('/sw.js')
      .then((registration) => {
        console.log('[PWA] Service Worker registered:', registration.scope);

        // Check for updates
        registration.addEventListener('updatefound', () => {
          const newWorker = registration.installing;
          newWorker.addEventListener('statechange', () => {
            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
              // New service worker available
              console.log('[PWA] New service worker available');
              // Optionally show update notification
              showUpdateNotification();
            }
          });
        });
      })
      .catch((error) => {
        console.error('[PWA] Service Worker registration failed:', error);
        if (error.message.includes('secure') || error.message.includes('HTTPS')) {
          console.warn('[PWA] Service Workers require HTTPS. For testing:');
          console.warn('[PWA] 1. Use ngrok: https://ngrok.com/');
          console.warn('[PWA] 2. Or test on localhost (not IP address)');
          console.warn('[PWA] 3. Or deploy to a server with HTTPS');
        }
      });

    // Check for updates on page load
    navigator.serviceWorker.getRegistration().then((registration) => {
      if (registration) {
        registration.update();
      }
    });
  });
}

// Only register if in secure context or allow fallback
if (window.isSecureContext || window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
  registerServiceWorker();
} else {
  console.warn('[PWA] Not in secure context. Service worker registration skipped.');
  console.warn('[PWA] PWA features may be limited. Use HTTPS for full functionality.');
}

// Handle Install Prompt
window.addEventListener('beforeinstallprompt', (e) => {
  console.log('[PWA] Install prompt available');
  // Prevent the mini-infobar from appearing on mobile
  e.preventDefault();
  // Stash the event so it can be triggered later
  deferredPrompt = e;
  // Show install button
  showInstallButton();
});

// Handle successful installation
window.addEventListener('appinstalled', () => {
  console.log('[PWA] App installed successfully');
  deferredPrompt = null;
  hideInstallButton();
  // Optionally show success message
  showInstallSuccessMessage();
});

// Show install button
function showInstallButton() {
  // Check if already installed
  if (window.matchMedia('(display-mode: standalone)').matches) {
    return; // Already installed
  }

  // Create or show install button
  let installBtn = document.getElementById('pwa-install-button');
  if (!installBtn) {
    installBtn = document.createElement('button');
    installBtn.id = 'pwa-install-button';
    installBtn.className = 'fixed bottom-20 right-4 z-50 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-full shadow-lg flex items-center space-x-2 transition-all duration-300';
    installBtn.innerHTML = `
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
      </svg>
      <span>Install App</span>
    `;
    installBtn.addEventListener('click', installApp);
    document.body.appendChild(installBtn);
  }
  installBtn.style.display = 'flex';
}

// Hide install button
function hideInstallButton() {
  const installBtn = document.getElementById('pwa-install-button');
  if (installBtn) {
    installBtn.style.display = 'none';
  }
}

// Install app
function installApp() {
  if (!deferredPrompt) {
    return;
  }

  // Show the install prompt
  deferredPrompt.prompt();

  // Wait for the user to respond to the prompt
  deferredPrompt.userChoice.then((choiceResult) => {
    if (choiceResult.outcome === 'accepted') {
      console.log('[PWA] User accepted the install prompt');
    } else {
      console.log('[PWA] User dismissed the install prompt');
    }
    deferredPrompt = null;
    hideInstallButton();
  });
}

// Show update notification
function showUpdateNotification() {
  // Create a simple notification banner
  const notification = document.createElement('div');
  notification.id = 'pwa-update-notification';
  notification.className = 'fixed top-4 left-4 right-4 z-50 bg-yellow-500 text-white px-4 py-3 rounded-lg shadow-lg flex items-center justify-between';
  notification.innerHTML = `
    <span>New version available. Refresh to update.</span>
    <button onclick="location.reload()" class="ml-4 bg-white text-yellow-600 px-3 py-1 rounded font-semibold">
      Refresh
    </button>
  `;
  document.body.appendChild(notification);

  // Auto-hide after 10 seconds
  setTimeout(() => {
    if (notification.parentNode) {
      notification.remove();
    }
  }, 10000);
}

// Show install success message
function showInstallSuccessMessage() {
  const message = document.createElement('div');
  message.className = 'fixed top-4 left-4 right-4 z-50 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg';
  message.textContent = 'App installed successfully!';
  document.body.appendChild(message);

  setTimeout(() => {
    if (message.parentNode) {
      message.remove();
    }
  }, 3000);
}

// Check if app is already installed
if (window.matchMedia('(display-mode: standalone)').matches) {
  console.log('[PWA] App is running in standalone mode');
  // Hide install button if already installed
  hideInstallButton();
}

// Export for use in other modules
export { installApp, showInstallButton, hideInstallButton };


