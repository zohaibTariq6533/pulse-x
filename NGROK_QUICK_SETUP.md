# 🚀 Quick ngrok Setup for PWA Testing

## What is ngrok?
ngrok creates a secure HTTPS tunnel to your local server, allowing service workers to work on mobile devices.

## Installation

### Windows
1. Download from: https://ngrok.com/download
2. Extract `ngrok.exe` to a folder (e.g., `C:\ngrok\`)
3. Add to PATH or use full path

### Or use Chocolatey:
```powershell
choco install ngrok
```

### Mac/Linux
```bash
# Using Homebrew (Mac)
brew install ngrok/ngrok/ngrok

# Or download from ngrok.com
```

## Usage

### Step 1: Start Laravel Server
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

### Step 2: Start ngrok (New Terminal)
```bash
ngrok http 8000
```

### Step 3: Copy HTTPS URL
ngrok will show something like:
```
Forwarding  https://abc123.ngrok.io -> http://localhost:8000
```

### Step 4: Use on Mobile
- Open the HTTPS URL on your phone: `https://abc123.ngrok.io`
- Service workers will now work!
- PWA install will work properly!

## Example Output
```
ngrok by @inconshreveable

Session Status                online
Account                       Your Name (Plan: Free)
Version                       3.x.x
Region                        United States (us)
Latency                       45ms
Web Interface                 http://127.0.0.1:4040
Forwarding                    https://abc123.ngrok.io -> http://localhost:8000

Connections                   ttl     opn     rt1     rt5     p50     p90
                              0       0       0.00    0.00    0.00    0.00
```

## Important Notes

1. **Free URLs Change:** Free ngrok URLs change each time you restart ngrok
2. **HTTPS Required:** Service workers only work with the HTTPS URL
3. **Web Interface:** Visit `http://127.0.0.1:4040` to see request logs
4. **Production:** For production, use a real domain with SSL certificate

## Troubleshooting

### ngrok not found
- Make sure ngrok is in your PATH
- Or use full path: `C:\ngrok\ngrok.exe http 8000`

### Port already in use
- Make sure Laravel is running on port 8000
- Or use a different port: `ngrok http 3000`

### Connection refused
- Make sure Laravel server is running
- Check firewall isn't blocking port 8000

## Alternative: ngrok with Custom Domain (Paid)

If you have ngrok paid plan, you can use a fixed domain:
```bash
ngrok http 8000 --domain=your-custom-domain.ngrok.io
```

---

**Quick Command:**
```bash
# Terminal 1
php artisan serve --host=0.0.0.0 --port=8000

# Terminal 2
ngrok http 8000
```

Then use the HTTPS URL on your mobile device! 🎉


