<?php
// =====================================================================
// @@PWA  @@MOBILE-APP
// ROOT > The "install this app" description (name, icon, colors). Phones read this to offer "Install app / Add to Home screen".
// FILE: manifest.php   (linked from includes/pwa_head.php; works in any folder name because it uses BASE_URL)
// FIND: press Ctrl+Shift+F and type a tag (example: @@PWA) to see every related place.
// =====================================================================
// No login session needed here: the app folder is simply the folder this file sits in.
define('BASE_URL', rtrim(str_replace('\\', '/', dirname((string)($_SERVER['SCRIPT_NAME'] ?? '/manifest.php'))), '/'));

header('Content-Type: application/manifest+json; charset=utf-8');
header('Cache-Control: public, max-age=3600');

$base = BASE_URL . '/';
echo json_encode([
    'id'               => $base,
    'name'             => 'Barangay Tigbao Water District',
    'short_name'       => 'Tigbao Water',
    'description'      => 'Water billing, meter reading and online payment for Barangay Tigbao Water District.',
    'lang'             => 'en',
    'start_url'        => $base . 'index.php?source=pwa',
    'scope'            => $base,
    'display'          => 'standalone',
    'orientation'      => 'portrait',
    'theme_color'      => '#1c5cc9',
    'background_color' => '#f4f7fb',
    'icons' => [
        ['src' => BASE_URL . '/assets/icons/icon-192.png',          'sizes' => '192x192', 'type' => 'image/png', 'purpose' => 'any'],
        ['src' => BASE_URL . '/assets/icons/icon-512.png',          'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'any'],
        ['src' => BASE_URL . '/assets/icons/icon-maskable-512.png', 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'maskable'],
    ],
    'shortcuts' => [
        ['name' => 'Consumer login',     'url' => $base . 'customer/login.php'],
        ['name' => 'Water Reader login', 'url' => $base . 'meter-reader/login.php'],
    ],
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
