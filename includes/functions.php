<?php
// Env yardımcı fonksiyonu
function getenv_var($key, $default = null) {
    $value = getenv($key);
    if ($value === false) {
        // .env dosyasını okuma
        static $env = null;
        if ($env === null) {
            $env = [];
            $envFile = dirname(__DIR__) . '/.env';
            if (file_exists($envFile)) {
                $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    // Yorum satırlarını atla
                    if (strpos(trim($line), '#') === 0) {
                        continue;
                    }
                    // Key-value ayrıştırma
                    list($envKey, $envValue) = explode('=', $line, 2);
                    $env[trim($envKey)] = trim($envValue);
                }
            }
        }
        return isset($env[$key]) ? $env[$key] : $default;
    }
    return $value;
}

// Güvenlik fonksiyonları
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Veritabanı yardımcı fonksiyonları
function db_connect() {
    static $connection = null;
    
    if ($connection === null) {
        try {
            if (DEBUG_MODE) {
                error_log("Veritabanına bağlanmaya çalışılıyor: mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";socket=" . DB_SOCKET);
            }
            
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            
            // Eğer soket tanımlanmışsa, DSN'e ekle
            if (defined('DB_SOCKET')) {
                $dsn .= ";unix_socket=" . DB_SOCKET;
            }
            
            $connection = new PDO(
                $dsn,
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
            
            if (DEBUG_MODE) {
                error_log("Veritabanı bağlantısı başarıyla kuruldu");
            }
        } catch (PDOException $e) {
            error_log("Veritabanı bağlantı hatası: " . $e->getMessage());
            if (DEBUG_MODE) {
                error_log("Bağlantı parametreleri: HOST=" . DB_HOST . ", DB=" . DB_NAME . ", USER=" . DB_USER);
                error_log("PDO hatası: " . $e->getMessage());
            }
            throw new Exception("Veritabanına bağlanılamadı: " . $e->getMessage());
        }
    }
    
    return $connection;
}

// Cache yardımcı fonksiyonları
function get_cache($key) {
    $cache_file = CACHE_DIR . '/' . md5($key) . '.cache';
    if (file_exists($cache_file) && (time() - filemtime($cache_file) < CACHE_TIME)) {
        return unserialize(file_get_contents($cache_file));
    }
    return false;
}

function set_cache($key, $data) {
    $cache_file = CACHE_DIR . '/' . md5($key) . '.cache';
    return file_put_contents($cache_file, serialize($data));
}

// URL yardımcı fonksiyonları
function get_current_url() {
    return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . 
           "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}

function redirect($url, $status = 302) {
    header("Location: $url", true, $status);
    exit;
}

// Dil yardımcı fonksiyonları
function get_language() {
    return isset($_SESSION['lang']) ? $_SESSION['lang'] : 'tr';
}

function set_language($lang) {
    $_SESSION['lang'] = $lang;
}

// Hata yönetimi
function handle_error($errno, $errstr, $errfile, $errline) {
    error_log("Hata [$errno]: $errstr in $errfile on line $errline");
    if (DEBUG_MODE) {
        echo "Hata: $errstr";
    } else {
        echo "Bir hata oluştu. Lütfen daha sonra tekrar deneyin.";
    }
    return true;
}

// Debug yardımcı fonksiyonu
function debug($data) {
    if (DEBUG_MODE) {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}

// Fonksiyonlar tanımlandıktan sonra, hata işleyiciyi ayarla
set_error_handler('handle_error');