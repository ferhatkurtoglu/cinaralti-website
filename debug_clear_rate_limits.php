<?php
// Debug: Rate limit verilerini temizle
require_once __DIR__ . '/config/config.php';

// Sadece DEBUG_MODE'da çalışsın
if (!defined('DEBUG_MODE') || !DEBUG_MODE) {
    http_response_code(404);
    exit('Not Found');
}

secure_session_start();

// Rate limit verilerini temizle
$cleared_count = 0;
foreach ($_SESSION as $key => $value) {
    if (strpos($key, 'rate_limit_') === 0) {
        unset($_SESSION[$key]);
        $cleared_count++;
    }
}

// CSRF token'ı da yenile
if (isset($_SESSION['csrf_token'])) {
    unset($_SESSION['csrf_token']);
    unset($_SESSION['csrf_token_time']);
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Debug: Rate Limit Cleared</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { color: #28a745; font-size: 18px; margin-bottom: 20px; }
        .info { color: #6c757d; font-size: 14px; }
        .btn { background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 20px; }
        .btn:hover { background: #218838; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧹 Rate Limit Temizlendi</h1>
        
        <div class="success">
            ✅ Başarılı! <?= $cleared_count ?> rate limit verisi temizlendi.
        </div>
        
        <div class="info">
            <strong>Temizlenen veriler:</strong><br>
            • Rate limit sayaçları<br>
            • CSRF token'ları<br>
            • Session zamanlayıcıları
        </div>
        
        <div class="info" style="margin-top: 15px;">
            <strong>Bu işlem sadece DEBUG_MODE aktifken çalışır.</strong><br>
            Production ortamında bu sayfaya erişilemez.
        </div>
        
        <a href="<?= BASE_URL ?>/cart" class="btn">🛒 Sepete Git</a>
        <a href="<?= BASE_URL ?>/donate" class="btn">💝 Bağış Yap</a>
        
        <div class="info" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
            <strong>Debug Bilgileri:</strong><br>
            • DEBUG_MODE: <?= DEBUG_MODE ? 'Aktif' : 'Pasif' ?><br>
            • Rate Limit Enabled: <?= PAYMENT_RATE_LIMIT_ENABLED ? 'Evet' : 'Hayır' ?><br>
            • Session ID: <?= session_id() ?><br>
            • Zaman: <?= date('Y-m-d H:i:s') ?>
        </div>
    </div>
</body>
</html> 