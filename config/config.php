<?php
// Session güvenlik ayarları - session başlatılmadan önce yapılmalı
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 1 : 0);
    ini_set('session.cookie_samesite', 'Strict');
    ini_set('session.use_strict_mode', 1);
    ini_set('session.gc_maxlifetime', 7200); // 2 saat
    ini_set('session.cookie_lifetime', 0); // Browser kapanınca expire
}

// Gerekli dosyaları dahil et - functions.php dosyasını önce dahil ediyoruz
require_once __DIR__ . '/../includes/functions.php';

// Temel URL tanımı - Geliştirme ve production ortamı için
if (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] === 'localhost' && isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '8000') {
    // PHP built-in server için (localhost:8000)
    define('BASE_URL', '');
} elseif (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] === 'localhost' && (strpos($_SERVER['SCRIPT_NAME'], '/cinaralti-website/') !== false)) {
    // XAMPP için (localhost/cinaralti-website/)
    define('BASE_URL', '/cinaralti-website/public');
} else {
    // Production için - hosting'de public klasörü root olacak
    define('BASE_URL', '');
}

// Geliştirme ortamında HTTP kullan, yayın ortamında HTTPS kullan
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
$httpHost = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
$baseUrl = BASE_URL ?: '';
define('SITE_URL', $protocol . '://' . $httpHost . $baseUrl);

// HTTPS zorunluluğu - production'da aktif olmalı
$is_production = !in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1']);
define('FORCE_HTTPS', $is_production);

// Site ayarları
define('SITE_NAME', 'Çınaraltı');
define('SITE_DESCRIPTION', 'Çınaraltı Resmi Web Sitesi');
define('SITE_KEYWORDS', 'çınaraltı, vakıf, dernek, yardım');

// Ödeme sistemi yapılandırmasını dahil et
require_once __DIR__ . '/payment_config.php';

// Debug modu - production'da kapalı olmalı
define('DEBUG_MODE', !$is_production);

// Zaman dilimi
date_default_timezone_set('Europe/Istanbul');

// Database dosyasını dahil et
require_once __DIR__ . '/database.php';