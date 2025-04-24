<?php
// Oturum başlatma
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="fail-container">
    <div class="fail-card">
        <div class="fail-icon">
            <i class="fas fa-times-circle"></i>
        </div>
        <div class="fail-title">Ödeme Başarısız</div>
        <div class="fail-message">
            Ödeme işleminiz sırasında bir sorun oluştu. Lütfen bilgilerinizi kontrol edip tekrar deneyiniz.
        </div>
        <div class="fail-details">
            <?php if (isset($_GET['ErrorCode'])) { ?>
                <div class="detail-item">
                    <span class="detail-label">Hata Kodu:</span>
                    <span class="detail-value"><?= htmlspecialchars($_GET['ErrorCode']) ?></span>
                </div>
            <?php } ?>
            
            <?php if (isset($_GET['ErrorMessage'])) { ?>
                <div class="detail-item">
                    <span class="detail-label">Hata Mesajı:</span>
                    <span class="detail-value"><?= htmlspecialchars($_GET['ErrorMessage']) ?></span>
                </div>
            <?php } ?>
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
    
    .btn-retry, .btn-home {
        width: 100%;
    }
}
</style> 