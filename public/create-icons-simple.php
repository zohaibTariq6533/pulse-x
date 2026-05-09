<?php
/**
 * Quick icon generator - creates simple colored icons for PWA
 * Access via: http://localhost:8000/create-icons-simple.php
 */

header('Content-Type: text/html; charset=utf-8');

$iconsDir = __DIR__ . '/icons';
if (!is_dir($iconsDir)) {
    mkdir($iconsDir, 0755, true);
}

// Check if GD is available
if (!function_exists('imagecreatetruecolor')) {
    die('GD library is not available. Please install php-gd extension.');
}

function createSimpleIcon($size, $filename) {
    $img = imagecreatetruecolor($size, $size);
    
    // Colors
    $darkBlue = imagecolorallocate($img, 15, 32, 39);   // #0F2027
    $midBlue = imagecolorallocate($img, 32, 58, 67);    // #203A43
    $lightBlue = imagecolorallocate($img, 44, 83, 100); // #2C5364
    $accentBlue = imagecolorallocate($img, 74, 144, 226); // #4A90E2
    $white = imagecolorallocate($img, 255, 255, 255);
    
    // Fill with gradient
    for ($y = 0; $y < $size; $y++) {
        $ratio = $y / $size;
        if ($ratio < 0.5) {
            $r = 15 + ($ratio * 2) * 17;
            $g = 32 + ($ratio * 2) * 26;
            $b = 39 + ($ratio * 2) * 28;
        } else {
            $r = 32 + (($ratio - 0.5) * 2) * 12;
            $g = 58 + (($ratio - 0.5) * 2) * 25;
            $b = 67 + (($ratio - 0.5) * 2) * 33;
        }
        $color = imagecolorallocate($img, (int)$r, (int)$g, (int)$b);
        imageline($img, 0, $y, $size, $y, $color);
    }
    
    // Draw "P" - using imagestring for simplicity
    $fontSize = 5; // Built-in font
    $text = 'P';
    $textWidth = imagefontwidth($fontSize) * strlen($text);
    $textHeight = imagefontheight($fontSize);
    $x = ($size - $textWidth) / 2;
    $y = ($size - $textHeight) / 2;
    
    imagestring($img, $fontSize, (int)$x, (int)$y, $text, $white);
    
    // Small "X" in corner
    imagestring($img, 3, (int)($size * 0.7), (int)($size * 0.15), 'X', $accentBlue);
    
    imagepng($img, $filename);
    imagedestroy($img);
    
    return file_exists($filename);
}

$icons = [
    ['size' => 192, 'file' => $iconsDir . '/icon-192x192.png'],
    ['size' => 512, 'file' => $iconsDir . '/icon-512x512.png'],
    ['size' => 180, 'file' => $iconsDir . '/apple-touch-icon.png'],
];

$created = [];
$failed = [];

foreach ($icons as $icon) {
    if (createSimpleIcon($icon['size'], $icon['file'])) {
        $created[] = basename($icon['file']);
    } else {
        $failed[] = basename($icon['file']);
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Icon Generator - Pulse-X</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
        .success { color: green; }
        .error { color: red; }
        .info { background: #e3f2fd; padding: 15px; border-radius: 5px; margin: 20px 0; }
        a { color: #2C5364; text-decoration: none; font-weight: bold; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h1>🎨 PWA Icon Generator</h1>
    
    <?php if (!empty($created)): ?>
        <div class="success">
            <h2>✅ Successfully Created:</h2>
            <ul>
                <?php foreach ($created as $file): ?>
                    <li><?php echo htmlspecialchars($file); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <div class="info">
            <p><strong>Icons created in:</strong> <code>public/icons/</code></p>
            <p><strong>Next steps:</strong></p>
            <ol>
                <li>Refresh your app: <a href="/">Go to App</a></li>
                <li>Clear browser cache (or use incognito mode)</li>
                <li>Try "Add to Home Screen" again</li>
            </ol>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($failed)): ?>
        <div class="error">
            <h2>❌ Failed to Create:</h2>
            <ul>
                <?php foreach ($failed as $file): ?>
                    <li><?php echo htmlspecialchars($file); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <?php if (empty($created) && empty($failed)): ?>
        <p>Click the button below to generate icons:</p>
        <form method="POST">
            <button type="submit" style="padding: 10px 20px; background: #2C5364; color: white; border: none; border-radius: 5px; cursor: pointer;">
                Generate Icons
            </button>
        </form>
    <?php endif; ?>
    
    <hr>
    <p><small>Icons are simple placeholders. Replace them with your custom designs later.</small></p>
</body>
</html>


