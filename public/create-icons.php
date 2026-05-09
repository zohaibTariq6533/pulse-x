<?php
/**
 * Simple PHP script to generate PWA icons for Pulse-X
 * Run this from command line: php public/create-icons.php
 * Or access via browser: http://localhost:8000/create-icons.php
 */

// Create icons directory if it doesn't exist
$iconsDir = __DIR__ . '/icons';
if (!is_dir($iconsDir)) {
    mkdir($iconsDir, 0755, true);
}

// Function to create a simple icon
function createIcon($size, $filename) {
    $image = imagecreatetruecolor($size, $size);
    
    // Create gradient background colors
    $darkBlue = imagecolorallocate($image, 15, 32, 39);   // #0F2027
    $midBlue = imagecolorallocate($image, 32, 58, 67);    // #203A43
    $lightBlue = imagecolorallocate($image, 44, 83, 100); // #2C5364
    $accentBlue = imagecolorallocate($image, 74, 144, 226); // #4A90E2
    $white = imagecolorallocate($image, 255, 255, 255);
    
    // Fill with gradient effect
    for ($y = 0; $y < $size; $y++) {
        $ratio = $y / $size;
        if ($ratio < 0.5) {
            $r = 15 + ($ratio * 2) * (32 - 15);
            $g = 32 + ($ratio * 2) * (58 - 32);
            $b = 39 + ($ratio * 2) * (67 - 39);
        } else {
            $r = 32 + (($ratio - 0.5) * 2) * (44 - 32);
            $g = 58 + (($ratio - 0.5) * 2) * (83 - 58);
            $b = 67 + (($ratio - 0.5) * 2) * (100 - 67);
        }
        $color = imagecolorallocate($image, (int)$r, (int)$g, (int)$b);
        imageline($image, 0, $y, $size, $y, $color);
    }
    
    // Draw "P" letter
    $fontSize = (int)($size * 0.6);
    $font = 5; // Built-in font (you can use imageloadfont for custom fonts)
    
    // Calculate text position (centered)
    $text = 'P';
    $textWidth = imagefontwidth($font) * strlen($text);
    $textHeight = imagefontheight($font);
    $x = ($size - $textWidth) / 2;
    $y = ($size - $textHeight) / 2;
    
    // Draw white "P"
    imagestring($image, $font, $x, $y, $text, $white);
    
    // Draw small "X" in corner
    $smallFont = 3;
    imagestring($image, $smallFont, (int)($size * 0.7), (int)($size * 0.15), 'X', $accentBlue);
    
    // Save image
    imagepng($image, $filename);
    imagedestroy($image);
    
    return file_exists($filename);
}

// Generate icons
$icons = [
    ['size' => 192, 'file' => $iconsDir . '/icon-192x192.png'],
    ['size' => 512, 'file' => $iconsDir . '/icon-512x512.png'],
    ['size' => 180, 'file' => $iconsDir . '/apple-touch-icon.png'],
];

$success = true;
foreach ($icons as $icon) {
    if (createIcon($icon['size'], $icon['file'])) {
        echo "✓ Created {$icon['file']} ({$icon['size']}x{$icon['size']})\n";
    } else {
        echo "✗ Failed to create {$icon['file']}\n";
        $success = false;
    }
}

if ($success) {
    echo "\n✅ All icons created successfully!\n";
    echo "Icons are located in: {$iconsDir}\n";
} else {
    echo "\n⚠️ Some icons failed to create. Make sure GD library is enabled in PHP.\n";
}


