<?php
// Oturum başlatma
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Temel yapılandırma dosyasını dahil et
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/actions/process-donation.php';

// Yetkilendirme kontrolü - sadece yetkili admin kullanıcıları erişebilir
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: " . BASE_URL . "/admin/login.php");
    exit;
}

// Bağış ID'sini al
$donationId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($donationId <= 0) {
    header("Location: " . BASE_URL . "/admin/donations-made.php");
    exit;
}

// Durum güncelleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $newStatus = isset($_POST['status']) ? sanitize_input($_POST['status']) : '';
    $csrfToken = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
    
    // CSRF token kontrolü
    if (!verify_csrf_token($csrfToken)) {
        $error_message = "Güvenlik doğrulaması başarısız oldu. Lütfen tekrar deneyin.";
    } else if (empty($newStatus)) {
        $error_message = "Geçerli bir durum seçmelisiniz.";
    } else {
        // Durumu güncelle
        $updated = update_donation_status($donationId, $newStatus);
        
        if ($updated) {
            $success_message = "Bağış durumu başarıyla güncellendi.";
        } else {
            $error_message = "Bağış durumu güncellenirken bir hata oluştu.";
        }
    }
}

// Bağış detaylarını getir
$donation = get_donation_by_id($donationId);

if (!$donation) {
    header("Location: " . BASE_URL . "/admin/donations-made.php");
    exit;
}

// Sayfa başlığı
$page_title = "Bağış Detayı #" . $donationId;

// Başlık dahil edilir
include_once __DIR__ . '/includes/header.php';
?>

<div class="admin-content">
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="mt-4"><?= $page_title ?></h1>
            <a href="<?= BASE_URL ?>/admin/donations-made.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Bağış Listesine Dön
            </a>
        </div>
        
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?= $success_message ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?= $error_message ?></div>
        <?php endif; ?>
        
        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-info-circle me-1"></i>
                        Bağış Bilgileri
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th width="200">ID</th>
                                <td><?= $donation['id'] ?></td>
                            </tr>
                            <tr>
                                <th>Bağış Türü</th>
                                <td><?= htmlspecialchars($donation['donation_type']) ?></td>
                            </tr>
                            <tr>
                                <th>Bağış Miktarı</th>
                                <td>₺<?= number_format($donation['amount'], 2, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <th>Ödeme Durumu</th>
                                <td>
                                    <?php 
                                    $status_class = '';
                                    $status_text = '';
                                    
                                    switch($donation['payment_status']) {
                                        case 'pending':
                                            $status_class = 'badge bg-warning';
                                            $status_text = 'Beklemede';
                                            break;
                                        case 'completed':
                                            $status_class = 'badge bg-success';
                                            $status_text = 'Tamamlandı';
                                            break;
                                        case 'failed':
                                            $status_class = 'badge bg-danger';
                                            $status_text = 'Başarısız';
                                            break;
                                        default:
                                            $status_class = 'badge bg-secondary';
                                            $status_text = $donation['payment_status'];
                                    }
                                    ?>
                                    <span class="<?= $status_class ?>"><?= $status_text ?></span>
                                </td>
                            </tr>
                            <tr>
                                <th>Oluşturulma Tarihi</th>
                                <td><?= date('d.m.Y H:i:s', strtotime($donation['created_at'])) ?></td>
                            </tr>
                            <tr>
                                <th>Son Güncelleme</th>
                                <td><?= date('d.m.Y H:i:s', strtotime($donation['updated_at'])) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-user me-1"></i>
                        Bağışçı Bilgileri
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th width="200">Bağışçı Adı</th>
                                <td><?= htmlspecialchars($donation['donor_name']) ?></td>
                            </tr>
                            <tr>
                                <th>E-posta</th>
                                <td><?= htmlspecialchars($donation['donor_email']) ?></td>
                            </tr>
                            <tr>
                                <th>Telefon</th>
                                <td><?= !empty($donation['donor_phone']) ? htmlspecialchars($donation['donor_phone']) : '-' ?></td>
                            </tr>
                            <tr>
                                <th>Şehir</th>
                                <td><?= !empty($donation['city']) ? htmlspecialchars($donation['city']) : '-' ?></td>
                            </tr>
                            <tr>
                                <th>Bağışçı Tipi</th>
                                <td><?= $donation['donor_type'] == 'individual' ? 'Bireysel' : 'Kurumsal' ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-edit me-1"></i>
                        Durum Güncelle
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                            
                            <div class="mb-3">
                                <label for="status" class="form-label">Ödeme Durumu</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="">Durum Seçin</option>
                                    <option value="pending" <?= $donation['payment_status'] == 'pending' ? 'selected' : '' ?>>Beklemede</option>
                                    <option value="completed" <?= $donation['payment_status'] == 'completed' ? 'selected' : '' ?>>Tamamlandı</option>
                                    <option value="failed" <?= $donation['payment_status'] == 'failed' ? 'selected' : '' ?>>Başarısız</option>
                                </select>
                            </div>
                            
                            <button type="submit" name="update_status" class="btn btn-primary w-100">Durumu Güncelle</button>
                        </form>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-tasks me-1"></i>
                        Hızlı İşlemler
                    </div>
                    <div class="card-body">
                        <a href="mailto:<?= htmlspecialchars($donation['donor_email']) ?>" class="btn btn-info w-100 mb-2">
                            <i class="fas fa-envelope me-2"></i> E-posta Gönder
                        </a>
                        
                        <?php if (!empty($donation['donor_phone'])): ?>
                            <a href="tel:<?= htmlspecialchars($donation['donor_phone']) ?>" class="btn btn-success w-100">
                                <i class="fas fa-phone me-2"></i> Ara
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Alt bilgi dahil et
include_once __DIR__ . '/includes/footer.php';
?> 