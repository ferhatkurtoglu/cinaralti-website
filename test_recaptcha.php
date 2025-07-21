<?php
// Test sayfası - reCAPTCHA doğrulaması için
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';

// Güvenli session başlat
secure_session_start();

// Form gönderildi mi kontrol et
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recaptcha_response = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';
    
    echo "<h2>reCAPTCHA Test Sonucu</h2>";
    echo "<p><strong>Response alındı:</strong> " . (!empty($recaptcha_response) ? 'Evet' : 'Hayır') . "</p>";
    echo "<p><strong>Response uzunluğu:</strong> " . strlen($recaptcha_response) . "</p>";
    echo "<p><strong>DEBUG_MODE:</strong> " . (DEBUG_MODE ? 'Aktif' : 'Pasif') . "</p>";
    
    if (!empty($recaptcha_response)) {
        $result = verify_recaptcha($recaptcha_response);
        echo "<p><strong>Doğrulama sonucu:</strong> " . ($result ? 'Başarılı' : 'Başarısız') . "</p>";
    } else {
        echo "<p><strong>Doğrulama sonucu:</strong> Response boş</p>";
    }
    
    echo "<hr>";
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>reCAPTCHA Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .container { max-width: 600px; margin: 0 auto; }
        .form-group { margin-bottom: 20px; }
        .btn { background: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; }
        .btn:hover { background: #45a049; }
    </style>
</head>
<body>
    <div class="container">
        <h1>reCAPTCHA Test Sayfası</h1>
        
        <form method="POST">
            <div class="form-group">
                <label>reCAPTCHA:</label>
                <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn">Test Et</button>
            </div>
        </form>
        
        <div style="margin-top: 30px;">
            <h3>Debug Bilgileri:</h3>
            <p><strong>DEBUG_MODE:</strong> <?= DEBUG_MODE ? 'Aktif' : 'Pasif' ?></p>
            <p><strong>PAYMENT_DEBUG:</strong> <?= defined('PAYMENT_DEBUG') && PAYMENT_DEBUG ? 'Aktif' : 'Pasif' ?></p>
            <p><strong>Server:</strong> <?= $_SERVER['SERVER_NAME'] ?? 'Bilinmiyor' ?></p>
            <p><strong>Protocol:</strong> <?= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'HTTPS' : 'HTTP' ?></p>
        </div>
    </div>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>
</html> 