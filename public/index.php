<?php
// Hata raporlamayı başlat
error_reporting(E_ALL);
ini_set('display_errors', 1); // Hataları göster

// HTTPS Yönlendirmesi
if (defined('FORCE_HTTPS') && FORCE_HTTPS === true) {
    if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
        $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . $redirect);
        exit();
    }
}

// Temel dosyaları dahil et
require_once __DIR__ . '/../config/config.php';

// Güvenli oturum başlat
secure_session_start();

// URL işleme
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = BASE_URL;

if (strpos($uri, $basePath) === 0) {
    $baseLength = strlen($basePath);
    $uri = substr($uri, $baseLength);
}

$uri = rtrim($uri, '/');
if ($uri === '') {
    $uri = '/';
}

// Sayfa yönlendirmeleri
$routes = [
    '/' => 'home.php',
    '/home' => 'home.php',
    '/about' => 'about.php',
    '/video' => 'video.php',
    '/video-details' => 'video-details.php',
    '/blog' => 'blog.php',
    '/contact' => 'contact.php',
    '/contact-ank' => 'contact-ank.php',
    '/contact-ist' => 'contact-ist.php',
    '/contact-deu' => 'contact-deu.php',
    '/donate' => 'donate.php',
    '/donate-details' => 'donate-details.php',
    '/cart' => 'cart.php',
    '/success' => 'success.php',
    '/fail' => 'fail.php',
    '/blog-details' => 'blog-details.php',
    '/faq' => 'faq.php',
    '/career' => 'career.php',
    '/career-details' => 'career-details.php',
    // Yasal Sayfalar
    '/privacy-policy' => 'privacy-policy.php',
    '/distance-selling' => 'distance-selling.php',
    '/return-policy' => 'return-policy.php',
    '/ssl-info' => 'ssl-info.php',
    '/zakat-calculator' => 'zakat-calculator.php'
];

// Özel sayfalar (header/footer olmadan)
$specialPages = [
    '/payment' => 'payment.php'
];

// Özel sayfaları kontrol et (header/footer olmadan)
if (isset($specialPages[$uri])) {
    $page = $specialPages[$uri];
    $pagePath = __DIR__ . '/../pages/' . $page;
    
    if (file_exists($pagePath)) {
        require_once $pagePath;
    } else {
        require_once __DIR__ . '/../pages/404.php';
    }
    exit;
}

// Header'ı yükle
require_once __DIR__ . '/../includes/header.php';

// Sayfayı yükle
if (isset($routes[$uri])) {
    $page = $routes[$uri];
    $pagePath = __DIR__ . '/../pages/' . $page;
    
    if (file_exists($pagePath)) {
        require_once $pagePath;
    } else {
        require_once __DIR__ . '/../pages/404.php';
    }
} else {
    require_once __DIR__ . '/../pages/404.php';
}

// Footer'ı yükle
require_once __DIR__ . '/../includes/footer.php';