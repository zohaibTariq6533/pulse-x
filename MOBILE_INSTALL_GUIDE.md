# 📱 Mobile Installation Guide - Pulse-X PWA

## Step 1: Make Your App Accessible on Mobile

Your phone and computer need to be on the **same Wi-Fi network**.

### Find Your Computer's IP Address

**Windows:**
```bash
ipconfig
```
Look for `IPv4 Address` (e.g., `192.168.1.100`)

**Mac/Linux:**
```bash
ifconfig
# or
ip addr show
```
Look for your local network IP (usually starts with `192.168.x.x` or `10.0.x.x`)

### Start Laravel Server for Mobile Access

Instead of the normal `php artisan serve`, use:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

This makes your server accessible from other devices on your network.

**Note:** Vite is now configured to allow mobile access! 

### For Development with Vite (Hot Reload)

If you're running `npm run dev` (Vite dev server), you have two options:

**Option 1: Use Built Assets (Recommended for Mobile Testing)**
```bash
# Build assets once
npm run build

# Then run Laravel server
php artisan serve --host=0.0.0.0 --port=8000
```
This creates static files that work perfectly on mobile.

**Option 2: Use Vite Dev Server (For Live Reload)**
```bash
# Vite is already configured to accept external connections
# Just make sure both servers are running:
# Terminal 1:
php artisan serve --host=0.0.0.0 --port=8000

# Terminal 2:
npm run dev
```

**Important:** When using Vite dev server, your mobile device needs to access the same network. The Vite dev server runs on port 5173 by default, but Laravel will proxy the requests automatically.

### Get Your Mobile URL

Your app will be accessible at:
```
http://YOUR_IP_ADDRESS:8000
```

Example: `http://192.168.1.100:8000`

---

## Step 2: Install on Android (Chrome)

### Method 1: Automatic Install Prompt

1. **Open Chrome** on your Android phone
2. **Navigate to** `http://YOUR_IP:8000` (replace YOUR_IP with your computer's IP)
3. **Wait a few seconds** - Chrome may show an install banner at the bottom
4. **Tap "Install"** or "Add to Home Screen" on the banner
5. **Confirm installation** when prompted
6. The app icon will appear on your home screen!

### Method 2: Manual Install via Menu

1. **Open Chrome** and go to `http://YOUR_IP:8000`
2. **Tap the menu** (3 dots in top-right corner)
3. **Select "Add to Home screen"** or **"Install app"**
4. **Customize the name** if desired (default: "Pulse-X")
5. **Tap "Add"** or **"Install"**
6. Done! The app icon appears on your home screen

### Method 3: Using the Install Button

1. **Open the app** in Chrome
2. **Look for a blue "Install App" button** in the bottom-right corner
3. **Tap it** to trigger the installation
4. **Follow the prompts** to install

### Verify Installation

- Open the app from your home screen
- It should open **without browser UI** (standalone mode)
- No address bar or browser controls visible
- Looks like a native app!

---

## Step 3: Install on iOS (Safari)

### Important Notes for iOS:
- **Only Safari** supports PWA installation on iOS
- iOS 11.3+ required
- Must use Safari (not Chrome or other browsers)

### Installation Steps:

1. **Open Safari** on your iPhone/iPad
2. **Navigate to** `http://YOUR_IP:8000`
3. **Tap the Share button** (square with arrow pointing up) at the bottom
4. **Scroll down** in the share menu
5. **Tap "Add to Home Screen"** (or "Add to Home Screen" icon)
6. **Edit the name** if desired (default: "Pulse-X")
7. **Tap "Add"** in the top-right corner
8. The app icon appears on your home screen!

### Verify Installation

- Open the app from your home screen
- It opens in **standalone mode** (no Safari UI)
- No address bar or browser controls
- Full-screen app experience

---

## Troubleshooting

### Can't Access App on Mobile

**Problem:** Phone can't connect to `http://YOUR_IP:8000`

**Solutions:**
1. ✅ Ensure phone and computer are on **same Wi-Fi network**
2. ✅ Check Windows Firewall isn't blocking port 8000
3. ✅ Verify Laravel is running with `--host=0.0.0.0`
4. ✅ Try accessing from computer's browser first: `http://localhost:8000`
5. ✅ Check your computer's IP address is correct

**Windows Firewall Fix:**
```bash
# Allow Laravel through firewall
netsh advfirewall firewall add rule name="Laravel Dev Server" dir=in action=allow protocol=TCP localport=8000
```

### Install Option Not Showing (Android)

**Problem:** "Add to Home Screen" option not visible

**Solutions:**
1. ✅ Visit the site **multiple times** (browsers require this)
2. ✅ **Interact with the page** (scroll, click, etc.)
3. ✅ Check if app is **already installed**
4. ✅ Ensure you're using **Chrome browser**
5. ✅ Check **DevTools** (if using remote debugging) for errors
6. ✅ Verify manifest and service worker are loading

### Install Option Not Showing (iOS)

**Problem:** "Add to Home Screen" not in Safari share menu

**Solutions:**
1. ✅ Make sure you're using **Safari** (not Chrome)
2. ✅ **Scroll down** in the share menu (it's at the bottom)
3. ✅ Check iOS version (11.3+ required)
4. ✅ Try **refreshing the page** first
5. ✅ Ensure manifest.json is loading correctly

### Service Worker Not Registering / "Not Supported"

**Problem:** Getting "Service Worker not supported" error

**Root Cause:** Service Workers **require HTTPS** (or localhost). HTTP with IP addresses won't work.

**Solutions:**

1. **Use ngrok (Recommended for Mobile Testing):**
   ```bash
   # Terminal 1: Start Laravel
   php artisan serve --host=0.0.0.0 --port=8000
   
   # Terminal 2: Start ngrok
   ngrok http 8000
   ```
   - Use the HTTPS URL from ngrok on your mobile device
   - Service workers will work!
   - See `NGROK_QUICK_SETUP.md` for details

2. **Test on localhost (Desktop only):**
   - Use `http://localhost:8000` instead of IP address
   - Service workers work on localhost

3. **Deploy to production:**
   - Deploy to a server with HTTPS
   - Service workers will work there

**Note:** PWA installation will still work without service worker, but offline features won't be available.

**Other fixes:**
- ✅ Clear browser cache and site data
- ✅ Check `sw.js` is accessible at `/sw.js`
- ✅ Verify service worker route in `routes/web.php`

### App Opens in Browser Instead of Standalone

**Problem:** Installed app opens with browser UI

**Solutions:**
1. ✅ Check `manifest.json` has `"display": "standalone"`
2. ✅ Uninstall and reinstall the app
3. ✅ Clear browser cache
4. ✅ Verify manifest is loading correctly

---

## Alternative: Using ngrok for Testing (HTTPS)

If you need HTTPS for testing (some features require it):

### Install ngrok:
```bash
# Download from https://ngrok.com/
# Or use package manager
```

### Start ngrok:
```bash
# In a new terminal, while Laravel is running
ngrok http 8000
```

### Use the HTTPS URL:
- ngrok provides a URL like: `https://abc123.ngrok.io`
- Use this URL on your mobile device
- This gives you HTTPS which is required for some PWA features

**Note:** Free ngrok URLs change each time. For production, use a real domain with SSL.

---

## Production Deployment

For production installation:

1. **Deploy to a server** with HTTPS (required for service workers)
2. **Get a domain name** with SSL certificate
3. **Users can install** using the same methods above
4. **No app store required!** PWAs install directly from the browser

---

## Quick Reference

### Android Chrome:
- Menu (3 dots) → "Add to Home screen" or "Install app"
- Or wait for automatic install banner

### iOS Safari:
- Share button → Scroll down → "Add to Home Screen"

### Test URLs:
- Local: `http://YOUR_IP:8000`
- Diagnostic: `http://YOUR_IP:8000/pwa-test.html`

---

**Need Help?** Check the browser console on your phone (use remote debugging) or test the diagnostic page at `/pwa-test.html`

