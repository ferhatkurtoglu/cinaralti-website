<?php
// Gerekli dosyaları dahil et - functions.php dosyasını önce dahil ediyoruz
require_once __DIR__ . '/../includes/functions.php';

// Temel URL tanımı
define('BASE_URL', '/cinaralti-website/public');
// Geliştirme ortamında HTTP kullan, yayın ortamında HTTPS kullan
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
define('SITE_URL', $protocol . '://' . $_SERVER['HTTP_HOST'] . '/cinaralti-website/public');

// HTTPS zorunluluğu - geliştirme ortamında devre dışı bırakalım
define('FORCE_HTTPS', false);

// Site ayarları
define('SITE_NAME', 'Çınaraltı');
define('SITE_DESCRIPTION', 'Çınaraltı Resmi Web Sitesi');
define('SITE_KEYWORDS', 'çınaraltı, vakıf, dernek, yardım');

// Ödeme güvenliği ayarları - .env dosyasından al
define('PAYMENT_MERCHANT_ID', getenv_var('PAYMENT_MERCHANT_ID', ''));
define('PAYMENT_API_KEY', getenv_var('PAYMENT_API_KEY', ''));
// Ödeme hata ayıklamasını geçici olarak açık bırakalım (Geliştirme ortamı)
define('PAYMENT_DEBUG', true); // getenv_var('PAYMENT_DEBUG', 'false') === 'true');

// Zaman dilimi
date_default_timezone_set('Europe/Istanbul');

// Database dosyasını dahil et
require_once __DIR__ . '/database.php';