<?php
/**
 * Ödeme Sistemi Yapılandırması
 * Bu dosya ödeme sistemi ayarlarını içerir
 */

// Güvenlik kontrolü
if (!defined('BASE_URL')) {
    header('HTTP/1.0 403 Forbidden');
    exit('Bu dosyaya doğrudan erişim izni yoktur.');
}

// Environment variables'dan ödeme sistemi ayarlarını al
define('PAYMENT_MERCHANT_ID', getenv_var('PAYMENT_MERCHANT_ID', ''));
define('PAYMENT_API_KEY', getenv_var('PAYMENT_API_KEY', ''));

// DEBUG_MODE aktifse PAYMENT_DEBUG'ı da aktif yap
$payment_debug = getenv_var('PAYMENT_DEBUG', 'false') === 'true';
if (defined('DEBUG_MODE') && DEBUG_MODE) {
    $payment_debug = true;
}
define('PAYMENT_DEBUG', $payment_debug);

// Production kontrolü - kritik bilgiler eksikse uyarı ver
if (empty(PAYMENT_MERCHANT_ID) || empty(PAYMENT_API_KEY) || 
    PAYMENT_MERCHANT_ID === 'your_merchant_id_here' || 
    PAYMENT_API_KEY === 'your_api_key_here') {
    
    error_log('UYARI: Ödeme sistemi API bilgileri eksik veya yapılandırılmamış!');
    
    if (!PAYMENT_DEBUG) {
        // Production'da kritik hata
        throw new Exception('Ödeme sistemi yapılandırması tamamlanmamış.');
    }
}

// Kuveyt Türk API ayarları
define('PAYMENT_API_URL', getenv_var('PAYMENT_API_URL', 'https://sanalpos.kuveytturk.com.tr/v1/vpos/nonThreeDPayment'));
define('PAYMENT_CURRENCY_CODE', getenv_var('PAYMENT_CURRENCY_CODE', '0949')); // TRY
define('PAYMENT_TRANSACTION_TYPE', getenv_var('PAYMENT_TRANSACTION_TYPE', '1')); // Satış

// Güvenlik ayarları
define('PAYMENT_FORCE_HTTPS', getenv_var('PAYMENT_FORCE_HTTPS', 'true') === 'true');
define('PAYMENT_CSRF_PROTECTION', getenv_var('PAYMENT_CSRF_PROTECTION', 'true') === 'true');

// Rate limiting ayarları - Development modunda daha esnek
$rate_limit_enabled = getenv_var('PAYMENT_RATE_LIMIT_ENABLED', 'true') === 'true';
// DEBUG_MODE aktifse rate limiting'i devre dışı bırak
if (defined('DEBUG_MODE') && DEBUG_MODE) {
    $rate_limit_enabled = false;
}
define('PAYMENT_RATE_LIMIT_ENABLED', $rate_limit_enabled);

// Development ortamında daha yüksek limitler
$max_attempts = defined('DEBUG_MODE') && DEBUG_MODE ? 50 : 3;
$rate_window = defined('DEBUG_MODE') && DEBUG_MODE ? 60 : 900; // Dev: 1 dakika, Prod: 15 dakika

define('PAYMENT_MAX_ATTEMPTS', (int)getenv_var('PAYMENT_MAX_ATTEMPTS', (string)$max_attempts));
define('PAYMENT_RATE_LIMIT_WINDOW', (int)getenv_var('PAYMENT_RATE_LIMIT_WINDOW', (string)$rate_window));

// Tutar limitleri
define('PAYMENT_MIN_AMOUNT', (float)getenv_var('PAYMENT_MIN_AMOUNT', '1.00'));
define('PAYMENT_MAX_AMOUNT', (float)getenv_var('PAYMENT_MAX_AMOUNT', '50000.00'));

// Timeout ayarları
define('PAYMENT_TIMEOUT', (int)getenv_var('PAYMENT_TIMEOUT', '30'));
define('PAYMENT_CONNECT_TIMEOUT', (int)getenv_var('PAYMENT_CONNECT_TIMEOUT', '10'));

// Hata mesajları
define('PAYMENT_ERROR_MESSAGES', [
    'security' => 'Güvenlik doğrulaması başarısız oldu. Lütfen tekrar deneyiniz.',
    'database' => 'Bağış bilgileriniz kaydedilirken bir sorun oluştu. Lütfen tekrar deneyiniz.',
    'configuration' => 'Ödeme sistemi yapılandırması tamamlanmamış. Lütfen site yöneticisiyle iletişime geçin.',
    'payment_gateway' => 'Ödeme sistemiyle iletişim sırasında bir sorun oluştu. Lütfen tekrar deneyiniz.',
    'invalid_amount' => 'Geçersiz bağış tutarı. Lütfen tutarı kontrol ediniz.',
    'invalid_card' => 'Geçersiz kart bilgileri. Lütfen kart bilgilerinizi kontrol ediniz.',
    'network_error' => 'Ağ bağlantısı sorunu. Lütfen internet bağlantınızı kontrol edip tekrar deneyiniz.',
    'rate_limit' => 'Çok fazla deneme yaptınız. Lütfen bir süre bekleyip tekrar deneyiniz.',
    'amount_limit' => 'Bağış tutarı belirlenen limitler dışında. (Min: ₺' . PAYMENT_MIN_AMOUNT . ' - Max: ₺' . PAYMENT_MAX_AMOUNT . ')',
    'session_expired' => 'Oturum süresi doldu. Lütfen sayfayı yenileyip tekrar deneyiniz.',
    'validation_failed' => 'Gönderilen veriler geçersiz. Lütfen form bilgilerini kontrol ediniz.'
]);

/**
 * Ödeme sisteminin yapılandırma durumunu kontrol eder
 * @return bool
 */
function is_payment_configured() {
    return !empty(PAYMENT_MERCHANT_ID) && 
           !empty(PAYMENT_API_KEY) && 
           PAYMENT_MERCHANT_ID !== 'your_merchant_id_here' && 
           PAYMENT_API_KEY !== 'your_api_key_here';
}

/**
 * Güvenli ödeme ortamı kontrolü
 * @return bool
 */
function is_secure_payment_environment() {
    if (PAYMENT_FORCE_HTTPS && (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on')) {
        return false;
    }
    
    if (!is_payment_configured()) {
        return false;
    }
    
    return true;
}

/**
 * Ödeme hatası mesajını güvenli şekilde döndürür
 * @param string $error_code
 * @return string
 */
function get_payment_error_message($error_code) {
    $messages = PAYMENT_ERROR_MESSAGES;
    
    if (isset($messages[$error_code])) {
        return $messages[$error_code];
    }
    
    // Varsayılan genel hata mesajı
    return 'Bir hata oluştu. Lütfen tekrar deneyiniz.';
}

// Güvenlik log fonksiyonu
function log_payment_security_event($event, $details = []) {
    $log_data = [
        'timestamp' => date('Y-m-d H:i:s'),
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
        'event' => $event,
        'details' => $details
    ];
    
    error_log('PAYMENT_SECURITY: ' . json_encode($log_data));
    
    // Kritik güvenlik olayları için ek uyarı
    $critical_events = ['rate_limit_exceeded', 'invalid_csrf', 'session_hijack_attempt'];
    if (in_array($event, $critical_events)) {
        error_log('CRITICAL_SECURITY_ALERT: ' . $event . ' from IP: ' . ($log_data['ip']));
    }
} 