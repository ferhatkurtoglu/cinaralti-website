<?php
// Güvenli session başlatma
require_once __DIR__ . '/../includes/functions.php';
secure_session_start();

// Config dosyaları
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/payment_config.php';

// Rate limiting kontrolü
if (PAYMENT_RATE_LIMIT_ENABLED && !check_rate_limit('fail_page', 10, 300)) {
    log_payment_security_event('rate_limit_exceeded', ['action' => 'fail_page_access']);
    // Fail sayfasında sonsuz döngüyü önlemek için sadece log at
    error_log('SECURITY: Fail page rate limit exceeded from IP: ' . $_SERVER['REMOTE_ADDR']);
}

// Process-donation dosyasını dahil et
require_once __DIR__ . '/../includes/actions/process-donation.php';

// Güvenli input handling
$orderId = isset($_POST['oid']) ? sanitize_input($_POST['oid']) : 
          (isset($_GET['OrderId']) ? sanitize_input($_GET['OrderId']) : '');
$errorCode = isset($_POST['ErrorCode']) ? sanitize_input($_POST['ErrorCode']) : 
           (isset($_GET['ErrorCode']) ? sanitize_input($_GET['ErrorCode']) : '');
$errorMessage = isset($_POST['ErrorMessage']) ? sanitize_input($_POST['ErrorMessage']) : 
              (isset($_GET['ErrorMessage']) ? sanitize_input($_GET['ErrorMessage']) : '');
$errorType = isset($_GET['error']) ? sanitize_input($_GET['error']) : 'payment_failed';
$procReturnCode = isset($_POST['ProcReturnCode']) ? sanitize_input($_POST['ProcReturnCode']) : '';
$response = isset($_POST['Response']) ? sanitize_input($_POST['Response']) : '';

// Input validation
if (!empty($orderId) && !preg_match('/^[A-Z0-9_-]+$/i', $orderId)) {
    log_payment_security_event('invalid_order_id_fail_page', ['order_id' => $orderId]);
    $orderId = ''; // Güvenlik için temizle
}

if (!empty($errorCode) && !preg_match('/^[A-Z0-9_-]+$/i', $errorCode)) {
    log_payment_security_event('invalid_error_code', ['error_code' => $errorCode]);
    $errorCode = ''; // Güvenlik için temizle
}

// Error type whitelist
$allowed_error_types = ['security', 'database', 'configuration', 'payment_gateway', 'invalid_amount', 
                       'invalid_card', 'network_error', 'rate_limit', 'amount_limit', 
                       'session_expired', 'validation_failed', 'payment_failed'];
if (!in_array($errorType, $allowed_error_types)) {
    log_payment_security_event('invalid_error_type', ['error_type' => $errorType]);
    $errorType = 'payment_failed'; // Varsayılan değer
}

// Ödeme sonucunu veritabanında güncelle
$donationId = isset($_SESSION['donation_made_id']) ? (int)$_SESSION['donation_made_id'] : 0;

// Kuveyt Türk'ten dönen "oid" içinde OrderNumber bilgisini ayıklama
if (empty($donationId) && !empty($orderId) && strpos($orderId, 'CIN') !== false) {
    // OrderId'den donationId'yi çıkarma işlemi (CINXXXXXXYYYY formatı için)
    $donation = get_recent_donation_by_order($orderId);
    if ($donation) {
        $donationId = $donation['id'];
    }
}

// Ödeme durumunu güncelle (eğer valid bir donation ID varsa)
if ($donationId > 0) {
    try {
        // Ödeme başarısız olduğu için durumu "failed" olarak güncelle
        $updated = update_donation_status($donationId, 'failed');
        
        // Hata detaylarını günlüğe kaydet
        if (DEBUG_MODE) {
            error_log("Bağış durumu güncellendi: ID=$donationId, Status=failed, Sonuç=" . ($updated ? 'Başarılı' : 'Başarısız'));
            error_log("Ödeme hatası: OrderId=$orderId, ErrorCode=$errorCode, ErrorMessage=$errorMessage, ProcReturnCode=$procReturnCode, Response=$response");
        }
    } catch (Exception $e) {
        error_log("Bağış durumu güncelleme hatası: " . $e->getMessage());
    }
}

// Hata mesajını hazırla
$failMessage = "Ödeme işleminiz sırasında bir sorun oluştu. Lütfen bilgilerinizi kontrol edip tekrar deneyiniz.";

// Hata tipine göre mesaj özelleştir
if ($errorType == 'security') {
    $failMessage = "Güvenlik doğrulaması başarısız oldu. Lütfen tekrar deneyiniz.";
} elseif ($errorType == 'database') {
    $failMessage = "Bağış bilgileriniz kaydedilirken bir sorun oluştu. Lütfen tekrar deneyiniz.";
} elseif ($errorType == 'configuration') {
    $failMessage = "Ödeme sistemi yapılandırması tamamlanmamış. Lütfen site yöneticisiyle iletişime geçin.";
} elseif ($errorType == 'payment_gateway') {
    $failMessage = "Ödeme sistemiyle iletişim sırasında bir sorun oluştu. Lütfen tekrar deneyiniz.";
}

// Kuveyt Türk'ten gelen hata mesajı varsa ekle
if (!empty($response)) {
    $failMessage .= "<br><br>Hata detayı: " . htmlspecialchars($response);
}
?>

<div class="fail-container">
    <div class="fail-card">
        <div class="fail-icon">
            <i class="fas fa-times-circle"></i>
        </div>
        <div class="fail-title">Ödeme Başarısız</div>
        <div class="fail-message">
            <?= $failMessage ?>
        </div>
        <div class="fail-details">
            <?php if (!empty($errorCode)) { ?>
            <div class="detail-item">
                <span class="detail-label">Hata Kodu:</span>
                <span class="detail-value"><?= htmlspecialchars($errorCode) ?></span>
            </div>
            <?php } ?>

            <?php if (!empty($errorMessage)) { ?>
            <div class="detail-item">
                <span class="detail-label">Hata Mesajı:</span>
                <span class="detail-value"><?= htmlspecialchars($errorMessage) ?></span>
            </div>
            <?php } ?>

            <div class="detail-item">
                <span class="detail-label">Tarih:</span>
                <span class="detail-value"><?= date('d.m.Y H:i') ?></span>
            </div>
        </div>
        <div class="fail-actions">
            <a href="<?= BASE_URL ?>/payment" class="btn-retry">Tekrar Dene</a>
            <a href="<?= BASE_URL ?>/" class="btn-home">Ana Sayfaya Dön</a>
        </div>
    </div>
</div>

<style>
.fail-container {
    max-width: 800px;
    margin: 120px auto 60px;
    padding: 0 20px;
}

.fail-card {
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    padding: 40px;
    text-align: center;
}

.fail-icon {
    font-size: 80px;
    color: #F44336;
    margin-bottom: 20px;
}

.fail-title {
    font-size: 28px;
    font-weight: 700;
    color: #333;
    margin-bottom: 15px;
}

.fail-message {
    font-size: 18px;
    color: #666;
    margin-bottom: 25px;
    line-height: 1.5;
}

.fail-details {
    background-color: #fff8f8;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 30px;
    text-align: left;
    border: 1px solid #ffebee;
}

.detail-item {
    margin-bottom: 10px;
    font-size: 16px;
}

.detail-label {
    font-weight: 600;
    color: #555;
    margin-right: 10px;
}

.detail-value {
    color: #333;
}

.fail-actions {
    margin-top: 10px;
    display: flex;
    justify-content: center;
    gap: 15px;
}

.btn-retry {
    display: inline-block;
    background-color: #F44336;
    color: white;
    padding: 12px 25px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: 600;
    transition: background-color 0.3s;
}

.btn-home {
    display: inline-block;
    background-color: #757575;
    color: white;
    padding: 12px 25px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: 600;
    transition: background-color 0.3s;
}

.btn-retry:hover {
    background-color: #d32f2f;
    text-decoration: none;
    color: white;
}

.btn-home:hover {
    background-color: #616161;
    text-decoration: none;
    color: white;
}

@media (max-width: 576px) {
    .fail-container {
        margin-top: 100px;
    }

    .fail-card {
        padding: 30px 20px;
    }

    .fail-icon {
        font-size: 60px;
    }

    .fail-title {
        font-size: 24px;
    }

    .fail-message {
        font-size: 16px;
    }

    .fail-actions {
        flex-direction: column;
        gap: 10px;
    }

    .btn-retry,
    .btn-home {
        width: 100%;
    }
}
</style>