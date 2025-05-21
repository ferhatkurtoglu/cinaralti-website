<?php
// Oturum başlatma
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Process-donation dosyasını dahil et
require_once __DIR__ . '/../includes/actions/process-donation.php';

// Kuveyt Türk'ten gelen yanıtları al
$orderId = isset($_POST['oid']) ? sanitize_input($_POST['oid']) : 
          (isset($_GET['OrderId']) ? sanitize_input($_GET['OrderId']) : '');
$authCode = isset($_POST['authcode']) ? sanitize_input($_POST['authcode']) : 
           (isset($_GET['AuthCode']) ? sanitize_input($_GET['AuthCode']) : '');
$procReturnCode = isset($_POST['ProcReturnCode']) ? sanitize_input($_POST['ProcReturnCode']) : '';
$response = isset($_POST['Response']) ? sanitize_input($_POST['Response']) : '';

// Ödeme sonucunu veritabanında güncelle
$donationId = isset($_SESSION['donation_made_id']) ? (int)$_SESSION['donation_made_id'] : 0;

// Kuveyt Türk'ten dönen "oid" içinde CustomerId bilgisini ayıklama
if (empty($donationId) && !empty($orderId) && strpos($orderId, 'CIN') !== false) {
    // OrderId'den donationId'yi çıkarma işlemi (CINXXXXXXYYYY formatı için)
    $parts = explode('CIN', $orderId);
    if (count($parts) > 1) {
        $timeStampRand = $parts[1]; // timestamp ve random sayıyı içerir
        if (is_numeric($timeStampRand)) {
            // Süreç kaydedildi mi diye kontrol et
            $donation = get_recent_donation_by_order($orderId);
            if ($donation) {
                $donationId = $donation['id'];
            }
        }
    }
}

// Ödeme durumunu güncelle (eğer valid bir donation ID varsa)
if ($donationId > 0) {
    try {
        // Kuveyt Türk yanıtını kontrol et
        $isSuccessful = true; // Varsayılan olarak başarılı kabul ediyoruz
        
        if (isset($procReturnCode) && $procReturnCode != "00") {
            $isSuccessful = false;
        }
        
        if (isset($response) && strtoupper($response) != "APPROVED") {
            $isSuccessful = false;
        }
        
        // Ödeme başarılı olduğu için durumu "completed" olarak güncelle
        if ($isSuccessful) {
            $updated = update_donation_status($donationId, 'completed');
            
            if (DEBUG_MODE) {
                error_log("Bağış durumu güncellendi: ID=$donationId, Status=completed, Sonuç=" . ($updated ? 'Başarılı' : 'Başarısız'));
                error_log("Kuveyt Türk yanıtı: OrderId=$orderId, AuthCode=$authCode, ProcReturnCode=$procReturnCode, Response=$response");
            }
            
            // Oturumdaki bağış verilerini temizle
            unset($_SESSION['donation_made_id']);
            unset($_SESSION['cart_total']);
            unset($_SESSION['donation_type']);
            unset($_SESSION['donation_id']);
        } else {
            // Ödeme başarısız - fail sayfasına yönlendir
            header("Location: " . BASE_URL . "/fail?ErrorCode=$procReturnCode&ErrorMessage=" . urlencode($response));
            exit;
        }
    } catch (Exception $e) {
        error_log("Bağış durumu güncelleme hatası: " . $e->getMessage());
    }
}

// Bağış bilgilerini getir
$donationDetails = null;
if ($donationId > 0) {
    $donationDetails = get_donation_by_id($donationId);
}
?>

<div class="success-container">
    <div class="success-card">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="success-title">Ödeme Başarılı!</div>
        <div class="success-message">
            Bağışınız için teşekkür ederiz. İşleminiz başarıyla tamamlandı.
        </div>
        <div class="success-details">
            <?php if (isset($donationDetails) && $donationDetails) { ?>
            <div class="detail-item">
                <span class="detail-label">Bağış Türü:</span>
                <span class="detail-value"><?= htmlspecialchars($donationDetails['donation_type']) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Bağış Miktarı:</span>
                <span class="detail-value">₺<?= number_format($donationDetails['amount'], 2, ',', '.') ?></span>
            </div>
            <?php } ?>
            
            <?php if (!empty($orderId)) { ?>
            <div class="detail-item">
                <span class="detail-label">Sipariş No:</span>
                <span class="detail-value"><?= htmlspecialchars($orderId) ?></span>
            </div>
            <?php } ?>

            <?php if (!empty($authCode)) { ?>
            <div class="detail-item">
                <span class="detail-label">Yetkilendirme Kodu:</span>
                <span class="detail-value"><?= htmlspecialchars($authCode) ?></span>
            </div>
            <?php } ?>
            
            <div class="detail-item">
                <span class="detail-label">Tarih:</span>
                <span class="detail-value"><?= date('d.m.Y H:i') ?></span>
            </div>
        </div>
        <div class="success-actions">
            <a href="<?= BASE_URL ?>/" class="btn-home">Ana Sayfaya Dön</a>
        </div>
    </div>
</div>

<style>
.success-container {
    max-width: 800px;
    margin: 120px auto 60px;
    padding: 0 20px;
}

.success-card {
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    padding: 40px;
    text-align: center;
}

.success-icon {
    font-size: 80px;
    color: #4CAF50;
    margin-bottom: 20px;
}

.success-title {
    font-size: 28px;
    font-weight: 700;
    color: #333;
    margin-bottom: 15px;
}

.success-message {
    font-size: 18px;
    color: #666;
    margin-bottom: 25px;
    line-height: 1.5;
}

.success-details {
    background-color: #f9f9f9;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 30px;
    text-align: left;
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

.success-actions {
    margin-top: 10px;
}

.btn-home {
    display: inline-block;
    background-color: #4CAF50;
    color: white;
    padding: 12px 25px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: 600;
    transition: background-color 0.3s;
}

.btn-home:hover {
    background-color: #3d9140;
    text-decoration: none;
    color: white;
}

@media (max-width: 576px) {
    .success-container {
        margin-top: 100px;
    }

    .success-card {
        padding: 30px 20px;
    }

    .success-icon {
        font-size: 60px;
    }

    .success-title {
        font-size: 24px;
    }

    .success-message {
        font-size: 16px;
    }
}
</style>