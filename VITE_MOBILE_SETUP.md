# 📱 Vite Mobile Access Setup

## Problem
Vite's dev server by default only listens on `localhost`, which means mobile devices on your network can't access the assets.

## ✅ Solution Applied

The `vite.config.js` has been updated to allow external connections:

```js
server: {
    host: '0.0.0.0', // Allows connections from any IP on your network
    port: 5173,
    hmr: {
        host: 'localhost', // HMR (Hot Module Replacement) host
    },
}
```

## 🚀 How to Use

### Method 1: Build Assets (Best for Mobile Testing)

This creates static files that work reliably on mobile:

```bash
# Build the assets
npm run build

# Start Laravel server
php artisan serve --host=0.0.0.0 --port=8000
```

**Pros:**
- ✅ Works reliably on mobile
- ✅ No network issues
- ✅ Faster loading
- ✅ Works offline (with service worker)

**Cons:**
- ❌ No hot reload (need to rebuild after changes)

### Method 2: Vite Dev Server (For Development)

For live reload during development:

```bash
# Terminal 1: Start Laravel
php artisan serve --host=0.0.0.0 --port=8000

# Terminal 2: Start Vite
npm run dev
```

**Pros:**
- ✅ Hot Module Replacement (instant updates)
- ✅ Fast refresh on code changes

**Cons:**
- ⚠️ Requires both servers running
- ⚠️ Mobile device must be on same network
- ⚠️ May have network latency

### Method 3: Use Composer Dev Script

If you have the dev script in `composer.json`:

```bash
composer run dev
```

This runs both Laravel and Vite concurrently.

## 🔧 Troubleshooting

### Vite Assets Not Loading on Mobile

1. **Check Vite is running:**
   ```bash
   # Should see: "Local: http://localhost:5173/"
   npm run dev
   ```

2. **Check your IP address:**
   ```bash
   # Windows
   ipconfig
   
   # Mac/Linux
   ifconfig
   ```

3. **Verify network:**
   - Phone and computer must be on same Wi-Fi
   - Check firewall isn't blocking port 5173

4. **Try building assets instead:**
   ```bash
   npm run build
   php artisan serve --host=0.0.0.0 --port=8000
   ```

### HMR (Hot Reload) Not Working on Mobile

HMR uses WebSockets and may not work reliably on mobile. This is normal. Use `npm run build` for mobile testing, or accept that you'll need to manually refresh.

### Port Already in Use

If port 5173 is taken, Vite will automatically use the next available port. Check the terminal output for the actual port.

## 💡 Best Practice

**For Development:**
- Use `npm run dev` on your computer
- Test on desktop browser first
- Use `npm run build` when testing on mobile

**For Production:**
- Always use `npm run build`
- Deploy the built assets
- Never run Vite dev server in production

## 📝 Quick Reference

```bash
# Development (desktop)
npm run dev

# Mobile testing
npm run build
php artisan serve --host=0.0.0.0 --port=8000

# Production
npm run build
php artisan serve --host=0.0.0.0 --port=8000
```

---

**Status:** ✅ Vite configured for mobile access
**Last Updated:** December 2025


