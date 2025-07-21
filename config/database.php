<?php
// Functions dosyasını dahil et
require_once __DIR__ . '/../includes/functions.php';

// Veritabanı bağlantı bilgileri - .env dosyasından al
define('DB_HOST', getenv_var('DB_HOST', 'localhost'));
define('DB_NAME', getenv_var('DB_NAME', 'cinaralti_db'));
define('DB_USER', getenv_var('DB_USER', 'root'));
define('DB_PASS', getenv_var('DB_PASS', ''));
define('DB_SOCKET', '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock');

// Cache ayarları
define('CACHE_DIR', __DIR__ . '/../cache');
define('CACHE_TIME', 3600); // 1 saat

// Debug modu config.php'den alınacak (zaten tanımlanmış)

// Oturum başlatma - config.php'de zaten güvenli session ayarları yapıldı
// Session başlatma functions.php'deki secure_session_start() ile yapılacak

// Hata raporlama
error_reporting(E_ALL);
ini_set('display_errors', DEBUG_MODE ? 1 : 0);
// Fonksiyon dosyası yüklendikten sonra hata işleyici çağrılacak
// set_error_handler('handle_error'); 