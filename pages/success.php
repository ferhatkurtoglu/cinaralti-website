<?php
// Oturum başlatma
if (session_status() === PHP_SESSION_NONE) {
    session_start();
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
            <?php if (isset($_GET['OrderId'])) { ?>
            <div class="detail-item">
                <span class="detail-label">Sipariş No:</span>
                <span class="detail-value"><?= htmlspecialchars($_GET['OrderId']) ?></span>
            </div>
            <?php } ?>

            <?php if (isset($_GET['AuthCode'])) { ?>
            <div class="detail-item">
                <span class="detail-label">Yetkilendirme Kodu:</span>
                <span class="detail-value"><?= htmlspecialchars($_GET['AuthCode']) ?></span>
            </div>
            <?php } ?>
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