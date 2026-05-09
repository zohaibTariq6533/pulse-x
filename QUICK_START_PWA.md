# 🚀 Quick Start: Install Pulse-X as PWA

## Step 1: Create Icons (Required)

Choose one method:

### Method A: HTML Generator (Recommended)
1. Open: `http://localhost:8000/generate-icons.html`
2. Click "Download All Icons"
3. Move files to `public/icons/` folder

### Method B: PHP Script
```bash
php public/create-icons.php
```

### Method C: Online Generator
Visit https://realfavicongenerator.net/ and create:
- `icon-192x192.png`
- `icon-512x512.png`  
- `apple-touch-icon.png`

Save all to `public/icons/`

## Step 2: Start Server

```bash
php artisan serve
```

For mobile access:
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

## Step 3: Install the App

### Desktop (Chrome/Edge)
1. Visit `http://localhost:8000`
2. Click install icon (➕) in address bar
3. Click "Install"

### Android (Chrome)
1. Visit `http://YOUR_IP:8000` on your phone
2. Menu (3 dots) → "Install app"
3. Done!

### iOS (Safari)
1. Visit `http://YOUR_IP:8000` in Safari
2. Share button → "Add to Home Screen"
3. Done!

## ✅ That's It!

Your app is now installable as a PWA!

For detailed info, see `PWA_COMPLETE_SETUP.md`


