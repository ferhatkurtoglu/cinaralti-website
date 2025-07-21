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
    if (is_array($data)) {
        return array_map('sanitize_input', $data);
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function generate_csrf_token($regenerate = false) {
    // Token'ın geçerlilik süresi (30 dakika)
    $token_lifetime = 1800; // 30 * 60 saniye
    
    // Mevcut token'ı kontrol et
    if (!$regenerate && isset($_SESSION['csrf_token']) && isset($_SESSION['csrf_token_time'])) {
        // Token hâlâ geçerli mi?
        if (time() - $_SESSION['csrf_token_time'] < $token_lifetime) {
            return $_SESSION['csrf_token'];
        }
    }
    
    // Yeni token oluştur
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    $_SESSION['csrf_token_time'] = time();
    
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    // Token var mı?
    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    
    // Token zamanı var mı?
    if (!isset($_SESSION['csrf_token_time'])) {
        return false;
    }
    
    // Token süresı dolmuş mu? (30 dakika)
    if (time() - $_SESSION['csrf_token_time'] > 1800) {
        unset($_SESSION['csrf_token']);
        unset($_SESSION['csrf_token_time']);
        return false;
    }
    
    // Token'ları karşılaştır (timing attack'e karşı hash_equals kullan)
    $is_valid = hash_equals($_SESSION['csrf_token'], $token);
    
    // Başarılı doğrulamadan sonra token'ı yenile (one-time use için)
    if ($is_valid) {
        generate_csrf_token(true);
    }
    
    return $is_valid;
}

// Session güvenlik ayarlarını yap (global)
function configure_session_security() {
    // Session ayarları artık config.php'de global olarak yapılıyor
    // Bu fonksiyon compatibility için korunuyor
    return true;
}

// Session güvenliği fonksiyonları
function secure_session_start() {
    // Session'ı başlat (eğer başlatılmamışsa) - ayarlar config.php'de yapıldı
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Session hijacking koruması
    $need_regenerate = false;
    
    // IP adresi kontrolü (dev ortamında devre dışı)
    if (!defined('DEBUG_MODE') || !DEBUG_MODE) {
        if (!isset($_SESSION['user_ip'])) {
            $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
        } elseif ($_SESSION['user_ip'] !== $_SERVER['REMOTE_ADDR']) {
            $need_regenerate = true;
        }
    }
    
    // User agent kontrolü
    if (!isset($_SESSION['user_agent'])) {
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
    } elseif ($_SESSION['user_agent'] !== ($_SERVER['HTTP_USER_AGENT'] ?? '')) {
        // Dev ortamında daha esnek ol
        if (!defined('DEBUG_MODE') || !DEBUG_MODE) {
            $need_regenerate = true;
        }
    }
    
    // Session timeout kontrolü (2 saat)
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 7200)) {
        $need_regenerate = true;
    }
    
    // Session yenilemesi gerekiyorsa
    if ($need_regenerate) {
        session_destroy();
        // Ayarlar zaten config.php'de yapıldı, tekrar session başlat
        session_start();
        $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
    }
    
    $_SESSION['last_activity'] = time();
}

// Rate limiting fonksiyonu
function check_rate_limit($action, $max_attempts = 5, $time_window = 300) {
    // DEBUG_MODE'da rate limiting devre dışı
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
        return true;
    }
    
    $ip = $_SERVER['REMOTE_ADDR'];
    $key = "rate_limit_{$action}_{$ip}";
    
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = ['count' => 0, 'first_attempt' => time()];
    }
    
    $rate_data = $_SESSION[$key];
    
    // Zaman penceresi sıfırlanmış mı?
    if (time() - $rate_data['first_attempt'] > $time_window) {
        $_SESSION[$key] = ['count' => 1, 'first_attempt' => time()];
        return true;
    }
    
    // Maksimum denemye ulaşılmış mı?
    if ($rate_data['count'] >= $max_attempts) {
        return false;
    }
    
    // Deneme sayısını artır
    $_SESSION[$key]['count']++;
    return true;
}

// Rate limiting verilerini temizle (development için)
function clear_rate_limits() {
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
        foreach ($_SESSION as $key => $value) {
            if (strpos($key, 'rate_limit_') === 0) {
                unset($_SESSION[$key]);
            }
        }
        return true;
    }
    return false;
}

// Gelişmiş input validation
function validate_donation_amount($amount) {
    // Sayısal değer mi?
    if (!is_numeric($amount)) {
        return false;
    }
    
    $amount = (float)$amount;
    
    // Minimum ve maksimum limitler
    if ($amount < 1 || $amount > 100000) {
        return false;
    }
    
    return true;
}

function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validate_phone($phone) {
    // Türkiye telefon formatı kontrolü
    $phone = preg_replace('/[^0-9]/', '', $phone);
    return preg_match('/^(\+90|0)?[5-7][0-9]{9}$/', $phone);
}

function validate_card_number($card_number) {
    // Luhn algoritması ile kart numarası doğrulama
    $card_number = preg_replace('/[^0-9]/', '', $card_number);
    
    if (strlen($card_number) < 13 || strlen($card_number) > 19) {
        return false;
    }
    
    $sum = 0;
    $reverse = strrev($card_number);
    
    for ($i = 0; $i < strlen($reverse); $i++) {
        $digit = (int)$reverse[$i];
        
        if ($i % 2 == 1) {
            $digit *= 2;
            if ($digit > 9) {
                $digit -= 9;
            }
        }
        
        $sum += $digit;
    }
    
    return $sum % 10 === 0;
}

// reCAPTCHA doğrulama fonksiyonu
function verify_recaptcha($recaptcha_response, $secret_key = null) {
    // DEBUG_MODE'da reCAPTCHA doğrulamasını atla
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
        error_log('DEBUG_MODE: reCAPTCHA doğrulaması atlandı');
        return true;
    }
    
    // Eğer secret key belirtilmemişse, varsayılan test key kullan
    if ($secret_key === null) {
        $secret_key = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe'; // Test secret key
    }
    
    // reCAPTCHA response boş mu?
    if (empty($recaptcha_response)) {
        error_log('reCAPTCHA response boş');
        return false;
    }
    
    // Debug için response'u logla
    if (defined('PAYMENT_DEBUG') && PAYMENT_DEBUG) {
        error_log('reCAPTCHA response alındı: ' . substr($recaptcha_response, 0, 50) . '...');
    }
    
    // Google reCAPTCHA API'sine istek gönder
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => $secret_key,
        'response' => $recaptcha_response,
        'remoteip' => $_SERVER['REMOTE_ADDR'] ?? ''
    ];
    
    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        ]
    ];
    
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    
    if ($result === false) {
        error_log('reCAPTCHA API isteği başarısız');
        return false;
    }
    
    $response = json_decode($result, true);
    
    // Debug için log
    if (defined('PAYMENT_DEBUG') && PAYMENT_DEBUG) {
        error_log('reCAPTCHA API response: ' . print_r($response, true));
    }
    
    // Başarı kontrolü
    $success = isset($response['success']) && $response['success'] === true;
    
    if (!$success && defined('PAYMENT_DEBUG') && PAYMENT_DEBUG) {
        error_log('reCAPTCHA doğrulama başarısız. Hata kodları: ' . (isset($response['error-codes']) ? implode(', ', $response['error-codes']) : 'Bilinmeyen hata'));
    }
    
    return $success;
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

// Cart güvenlik fonksiyonları
function validate_cart_item($item) {
    $errors = [];
    
    // Zorunlu alanlar kontrolü
    if (empty($item['title'])) {
        $errors[] = 'Bağış başlığı gereklidir';
    }
    
    if (empty($item['amount']) || !is_numeric(str_replace(['₺', ',', '.'], '', $item['amount']))) {
        $errors[] = 'Geçersiz bağış tutarı';
    }
    
    // Tutar kontrolü
    $amount = (float)preg_replace('/[^\d.]/', '', $item['amount']);
    if ($amount < PAYMENT_MIN_AMOUNT || $amount > PAYMENT_MAX_AMOUNT) {
        $errors[] = 'Bağış tutarı belirlenen limitler dışında';
    }
    
    // Email kontrolü
    if (!empty($item['donorEmail']) && !validate_email($item['donorEmail'])) {
        $errors[] = 'Geçersiz email adresi';
    }
    
    // Telefon kontrolü
    if (!empty($item['donorPhone']) && !validate_phone($item['donorPhone'])) {
        $errors[] = 'Geçersiz telefon numarası';
    }
    
    return $errors;
}

function sanitize_cart_item($item) {
    return [
        'title' => sanitize_input($item['title'] ?? ''),
        'amount' => sanitize_input($item['amount'] ?? ''),
        'donationType' => sanitize_input($item['donationType'] ?? 'Standart Bağış'),
        'donorName' => sanitize_input($item['donorName'] ?? ''),
        'donorPhone' => sanitize_input($item['donorPhone'] ?? ''),
        'donorEmail' => sanitize_input($item['donorEmail'] ?? ''),
        'donorType' => sanitize_input($item['donorType'] ?? 'Bireysel'),
        'image' => sanitize_input($item['image'] ?? '')
    ];
}

function get_server_cart() {
    return $_SESSION['server_cart'] ?? [];
}

function set_server_cart($cart) {
    $_SESSION['server_cart'] = $cart;
}

function add_to_server_cart($item) {
    // Item'ı sanitize et
    $sanitized_item = sanitize_cart_item($item);
    
    // Validate et
    $errors = validate_cart_item($sanitized_item);
    if (!empty($errors)) {
        return ['success' => false, 'errors' => $errors];
    }
    
    // Sepete ekle
    $cart = get_server_cart();
    $sanitized_item['id'] = uniqid('cart_', true);
    $sanitized_item['added_at'] = time();
    
    $cart[] = $sanitized_item;
    set_server_cart($cart);
    
    return ['success' => true, 'item_id' => $sanitized_item['id']];
}

function remove_from_server_cart($item_id) {
    $cart = get_server_cart();
    
    foreach ($cart as $key => $item) {
        if ($item['id'] === $item_id) {
            unset($cart[$key]);
            break;
        }
    }
    
    set_server_cart(array_values($cart));
    return true;
}

function clear_server_cart() {
    unset($_SESSION['server_cart']);
    return true;
}

function calculate_cart_total() {
    $cart = get_server_cart();
    $total = 0;
    
    foreach ($cart as $item) {
        $amount = (float)preg_replace('/[^\d.]/', '', $item['amount']);
        $total += $amount;
    }
    
    return $total;
}

function validate_cart_for_checkout() {
    $cart = get_server_cart();
    $errors = [];
    
    if (empty($cart)) {
        $errors[] = 'Sepet boş';
        return ['valid' => false, 'errors' => $errors];
    }
    
    $total = 0;
    foreach ($cart as $item) {
        $item_errors = validate_cart_item($item);
        if (!empty($item_errors)) {
            $errors = array_merge($errors, $item_errors);
        }
        
        $amount = (float)preg_replace('/[^\d.]/', '', $item['amount']);
        $total += $amount;
    }
    
    // Toplam tutar kontrolü
    if ($total < PAYMENT_MIN_AMOUNT) {
        $errors[] = 'Minimum bağış tutarı ₺' . PAYMENT_MIN_AMOUNT;
    }
    
    if ($total > PAYMENT_MAX_AMOUNT) {
        $errors[] = 'Maksimum bağış tutarı ₺' . PAYMENT_MAX_AMOUNT;
    }
    
    return [
        'valid' => empty($errors),
        'errors' => $errors,
        'total' => $total,
        'item_count' => count($cart)
    ];
}

// Encryption ve sensitive data protection fonksiyonları
function get_encryption_key() {
    // .env dosyasından encryption key al
    $key = getenv_var('ENCRYPTION_KEY', '');
    
    if (empty($key)) {
        // Fallback: güvenli key oluştur (production'da .env kullanılmalı)
        $key = hash('sha256', 'cinaralti_secure_key_' . (defined('DB_NAME') ? DB_NAME : 'default'));
    }
    
    return hex2bin($key);
}

function encrypt_sensitive_data($data) {
    if (empty($data)) {
        return $data;
    }
    
    try {
        $key = get_encryption_key();
        $iv = random_bytes(16); // AES-256-CBC için 16 byte IV
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);
        
        // IV ve encrypted data'yı birleştir
        return base64_encode($iv . $encrypted);
    } catch (Exception $e) {
        error_log("Encryption error: " . $e->getMessage());
        return $data; // Fallback olarak plain text döndür
    }
}

function decrypt_sensitive_data($encrypted_data) {
    if (empty($encrypted_data)) {
        return $encrypted_data;
    }
    
    try {
        $key = get_encryption_key();
        $data = base64_decode($encrypted_data);
        
        // IV ve encrypted data'yı ayır
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        
        return openssl_decrypt($encrypted, 'AES-256-CBC', $key, 0, $iv);
    } catch (Exception $e) {
        error_log("Decryption error: " . $e->getMessage());
        return $encrypted_data; // Fallback
    }
}

// PII (Personally Identifiable Information) masking
function mask_email($email) {
    if (empty($email) || strpos($email, '@') === false) {
        return $email;
    }
    
    $parts = explode('@', $email);
    $username = $parts[0];
    $domain = $parts[1];
    
    $username_length = strlen($username);
    if ($username_length <= 2) {
        $masked_username = str_repeat('*', $username_length);
    } else {
        $visible_chars = min(2, floor($username_length / 2));
        $masked_username = substr($username, 0, $visible_chars) . 
                          str_repeat('*', $username_length - $visible_chars);
    }
    
    return $masked_username . '@' . $domain;
}

function mask_phone($phone) {
    if (empty($phone)) {
        return $phone;
    }
    
    $phone = preg_replace('/[^0-9]/', '', $phone);
    $length = strlen($phone);
    
    if ($length <= 4) {
        return str_repeat('*', $length);
    }
    
    // Son 4 rakamı göster, geri kalanını maskele
    return str_repeat('*', $length - 4) . substr($phone, -4);
}

function mask_card_number($card_number) {
    if (empty($card_number)) {
        return $card_number;
    }
    
    $card_number = preg_replace('/[^0-9]/', '', $card_number);
    $length = strlen($card_number);
    
    if ($length < 8) {
        return str_repeat('*', $length);
    }
    
    // İlk 4 ve son 4 rakamı göster
    return substr($card_number, 0, 4) . str_repeat('*', $length - 8) . substr($card_number, -4);
}

// Güvenli logging
function log_sensitive_operation($operation, $data = []) {
    // Sensitive data'yı maskele
    $safe_data = $data;
    
    if (isset($safe_data['donor_email'])) {
        $safe_data['donor_email'] = mask_email($safe_data['donor_email']);
    }
    
    if (isset($safe_data['donor_phone'])) {
        $safe_data['donor_phone'] = mask_phone($safe_data['donor_phone']);
    }
    
    if (isset($safe_data['card_number'])) {
        $safe_data['card_number'] = mask_card_number($safe_data['card_number']);
    }
    
    // Log verisini hazırla
    $log_entry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'operation' => $operation,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? 'unknown', 0, 200),
        'data' => $safe_data
    ];
    
    error_log('SENSITIVE_OPERATION: ' . json_encode($log_entry));
}

// GDPR compliance helpers
function should_encrypt_field($field_name) {
    $sensitive_fields = [
        'donor_email', 'donor_phone', 'donor_name', 
        'card_number', 'card_holder_name', 'donor_address'
    ];
    
    return in_array($field_name, $sensitive_fields);
}

function prepare_donation_data_for_storage($data) {
    $prepared_data = $data;
    
    // Sensitive alanları encrypt et
    foreach ($data as $key => $value) {
        if (should_encrypt_field($key) && !empty($value)) {
            $prepared_data[$key] = encrypt_sensitive_data($value);
        }
    }
    
    return $prepared_data;
}

function prepare_donation_data_for_display($data) {
    $display_data = $data;
    
    // Sensitive alanları decrypt et
    foreach ($data as $key => $value) {
        if (should_encrypt_field($key) && !empty($value)) {
            $display_data[$key] = decrypt_sensitive_data($value);
        }
    }
    
    return $display_data;
}