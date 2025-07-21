<?php
// Config dosyasını dahil et
require_once __DIR__ . '/../config/config.php';

// Güvenli oturum başlat
secure_session_start();

// Temel yapılandırma dosyasını dahil et
require_once __DIR__ . '/../config/config.php';

// Yetkilendirme kontrolü - sadece yetkili admin kullanıcıları erişebilir
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: " . BASE_URL . "/admin/login.php");
    exit;
}

// Sayfa başlığı
$page_title = "Bağış Yönetimi";

// Başlık dahil edilir
include_once __DIR__ . '/includes/header.php';

// Bağış verilerini getir
try {
    $db = db_connect();
    
    // Sayfalama için değişkenler
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 20; // Sayfa başına gösterilecek kayıt sayısı
    $offset = ($page - 1) * $limit;
    
    // Filtre değişkenleri
    $filter_status = isset($_GET['status']) ? sanitize_input($_GET['status']) : '';
    $filter_date = isset($_GET['date']) ? sanitize_input($_GET['date']) : '';
    $filter_type = isset($_GET['type']) ? sanitize_input($_GET['type']) : '';
    
    // Temel sorgu
    $sql = "SELECT * FROM donations_made WHERE 1=1";
    $count_sql = "SELECT COUNT(*) as total FROM donations_made WHERE 1=1";
    $params = [];
    
    // Filtreleri uygula
    if (!empty($filter_status)) {
        $sql .= " AND payment_status = ?";
        $count_sql .= " AND payment_status = ?";
        $params[] = $filter_status;
    }
    
    if (!empty($filter_date)) {
        if ($filter_date == 'today') {
            $sql .= " AND DATE(created_at) = CURDATE()";
            $count_sql .= " AND DATE(created_at) = CURDATE()";
        } elseif ($filter_date == 'week') {
            $sql .= " AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
            $count_sql .= " AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        } elseif ($filter_date == 'month') {
            $sql .= " AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
            $count_sql .= " AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
        }
    }
    
    if (!empty($filter_type)) {
        $sql .= " AND donation_type = ?";
        $count_sql .= " AND donation_type = ?";
        $params[] = $filter_type;
    }
    
    // Sıralama ve sayfalama
    $sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    
    // Toplam kayıt sayısını al
    $count_stmt = $db->prepare($count_sql);
    
    // Sayım parametrelerini bağla
    for ($i = 0; $i < count($params) - 2; $i++) {
        $count_stmt->bindValue($i + 1, $params[$i]);
    }
    
    $count_stmt->execute();
    $total_records = $count_stmt->fetch()['total'];
    $total_pages = ceil($total_records / $limit);
    
    // Verileri al
    $stmt = $db->prepare($sql);
    
    // Parametreleri bağla
    for ($i = 0; $i < count($params); $i++) {
        $stmt->bindValue($i + 1, $params[$i]);
    }
    
    $stmt->execute();
    $donations = $stmt->fetchAll();
    
    // Bağış türlerini al (filtre için)
    $type_stmt = $db->prepare("SELECT DISTINCT donation_type FROM donations_made ORDER BY donation_type");
    $type_stmt->execute();
    $donation_types = $type_stmt->fetchAll();
    
} catch (Exception $e) {
    error_log("Bağış verileri getirme hatası: " . $e->getMessage());
    $error_message = "Verileri getirirken bir hata oluştu.";
}
?>

<div class="admin-content">
    <div class="container-fluid px-4">
        <h1 class="mt-4"><?= $page_title ?></h1>
        
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-filter me-1"></i>
                Bağış Filtreleri
            </div>
            <div class="card-body">
                <form method="get" class="row align-items-end">
                    <div class="col-md-3 mb-3">
                        <label for="status">Ödeme Durumu</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Tümü</option>
                            <option value="pending" <?= $filter_status == 'pending' ? 'selected' : '' ?>>Beklemede</option>
                            <option value="completed" <?= $filter_status == 'completed' ? 'selected' : '' ?>>Tamamlandı</option>
                            <option value="failed" <?= $filter_status == 'failed' ? 'selected' : '' ?>>Başarısız</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="date">Tarih</label>
                        <select name="date" id="date" class="form-select">
                            <option value="">Tümü</option>
                            <option value="today" <?= $filter_date == 'today' ? 'selected' : '' ?>>Bugün</option>
                            <option value="week" <?= $filter_date == 'week' ? 'selected' : '' ?>>Son 7 Gün</option>
                            <option value="month" <?= $filter_date == 'month' ? 'selected' : '' ?>>Son 30 Gün</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="type">Bağış Türü</label>
                        <select name="type" id="type" class="form-select">
                            <option value="">Tümü</option>
                            <?php foreach ($donation_types as $type): ?>
                                <option value="<?= htmlspecialchars($type['donation_type']) ?>" <?= $filter_type == $type['donation_type'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($type['donation_type']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button type="submit" class="btn btn-primary w-100">Filtrele</button>
                        <a href="<?= BASE_URL ?>/admin/donations-made.php" class="btn btn-secondary w-100 mt-2">Filtreleri Temizle</a>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Yapılan Bağışlar
            </div>
            <div class="card-body">
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?= $error_message ?></div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="donations-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Bağışçı</th>
                                    <th>İletişim</th>
                                    <th>Bağış Türü</th>
                                    <th>Miktar</th>
                                    <th>Durum</th>
                                    <th>Tarih</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($donations) > 0): ?>
                                    <?php foreach ($donations as $donation): ?>
                                        <tr>
                                            <td><?= $donation['id'] ?></td>
                                            <td>
                                                <?= htmlspecialchars($donation['donor_name']) ?>
                                                <small class="d-block text-muted"><?= htmlspecialchars($donation['donor_type']) == 'individual' ? 'Bireysel' : 'Kurumsal' ?></small>
                                            </td>
                                            <td>
                                                <?= htmlspecialchars($donation['donor_email']) ?>
                                                <?php if(!empty($donation['donor_phone'])): ?>
                                                    <small class="d-block"><?= htmlspecialchars($donation['donor_phone']) ?></small>
                                                <?php endif; ?>
                                                <?php if(!empty($donation['city'])): ?>
                                                    <small class="d-block text-muted"><?= htmlspecialchars($donation['city']) ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($donation['donation_type']) ?></td>
                                            <td class="text-end">₺<?= number_format($donation['amount'], 2, ',', '.') ?></td>
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
                                            <td><?= date('d.m.Y H:i', strtotime($donation['created_at'])) ?></td>
                                            <td>
                                                <a href="<?= BASE_URL ?>/admin/donation-detail.php?id=<?= $donation['id'] ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center">Kayıt bulunamadı</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if ($total_pages > 1): ?>
                        <nav aria-label="Sayfalama">
                            <ul class="pagination justify-content-center">
                                <?php if ($page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?= $page - 1 ?>&status=<?= $filter_status ?>&date=<?= $filter_date ?>&type=<?= $filter_type ?>">
                                            Önceki
                                        </a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $i ?>&status=<?= $filter_status ?>&date=<?= $filter_date ?>&type=<?= $filter_type ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>
                                
                                <?php if ($page < $total_pages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?= $page + 1 ?>&status=<?= $filter_status ?>&date=<?= $filter_date ?>&type=<?= $filter_type ?>">
                                            Sonraki
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                    
                    <!-- İstatistikler -->
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white mb-4">
                                <div class="card-body">
                                    Toplam Bağış Sayısı
                                    <h2 class="mb-0"><?= $total_records ?></h2>
                                </div>
                            </div>
                        </div>
                        <?php
                        // Toplam başarılı bağış tutarını hesapla
                        try {
                            $total_sql = "SELECT SUM(amount) as total FROM donations_made WHERE payment_status = 'completed'";
                            $total_stmt = $db->prepare($total_sql);
                            $total_stmt->execute();
                            $total_amount = $total_stmt->fetch()['total'] ?? 0;
                        } catch (Exception $e) {
                            $total_amount = 0;
                        }
                        ?>
                        <div class="col-md-4">
                            <div class="card bg-success text-white mb-4">
                                <div class="card-body">
                                    Toplam Başarılı Bağış
                                    <h2 class="mb-0">₺<?= number_format($total_amount, 2, ',', '.') ?></h2>
                                </div>
                            </div>
                        </div>
                        <?php
                        // Tamamlanan bağış sayısını hesapla
                        try {
                            $completed_sql = "SELECT COUNT(*) as total FROM donations_made WHERE payment_status = 'completed'";
                            $completed_stmt = $db->prepare($completed_sql);
                            $completed_stmt->execute();
                            $completed_count = $completed_stmt->fetch()['total'] ?? 0;
                            
                            // Başarı oranını hesapla
                            $success_rate = $total_records > 0 ? round(($completed_count / $total_records) * 100) : 0;
                        } catch (Exception $e) {
                            $completed_count = 0;
                            $success_rate = 0;
                        }
                        ?>
                        <div class="col-md-4">
                            <div class="card bg-info text-white mb-4">
                                <div class="card-body">
                                    Başarı Oranı
                                    <h2 class="mb-0">%<?= $success_rate ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
// Alt bilgi dahil et
include_once __DIR__ . '/includes/footer.php';
?> 