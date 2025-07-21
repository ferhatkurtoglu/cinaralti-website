<!-- Ödeme Sayfası -->
<?php
// Güvenli session başlatma
require_once __DIR__ . '/../includes/functions.php';
secure_session_start();

// Debug bilgilerini göster
if (defined('DEBUG_MODE') && DEBUG_MODE) {
    echo "<!-- DEBUG_MODE: Aktif -->\n";
    echo "<!-- PAYMENT_DEBUG: " . (defined('PAYMENT_DEBUG') && PAYMENT_DEBUG ? 'Aktif' : 'Pasif') . " -->\n";
    echo "<!-- Server: " . ($_SERVER['SERVER_NAME'] ?? 'Bilinmiyor') . " -->\n";
    echo "<!-- Protocol: " . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'HTTPS' : 'HTTP') . " -->\n";
}

// Config dosyalarını dahil et
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/payment_config.php';

// DEBUG_MODE'da rate limit verilerini temizle
if (defined('DEBUG_MODE') && DEBUG_MODE) {
    clear_rate_limits();
}

// Güvenli ödeme ortamı kontrolü
if (!is_secure_payment_environment()) {
    if (PAYMENT_FORCE_HTTPS && (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on')) {
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        exit();
    } else {
        // Yapılandırma hatası
        header('Location: ' . BASE_URL . '/fail?error=configuration');
        exit();
    }
}

// Rate limiting kontrolü (DEBUG_MODE'da devre dışı)
if (PAYMENT_RATE_LIMIT_ENABLED && !check_rate_limit('payment_page', PAYMENT_MAX_ATTEMPTS, PAYMENT_RATE_LIMIT_WINDOW)) {
    log_payment_security_event('rate_limit_exceeded', ['action' => 'payment_page_access']);
    // DEBUG_MODE'da warning göster ama engelleme
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
        error_log('WARNING: Rate limit exceeded but allowing due to DEBUG_MODE');
    } else {
        header('Location: ' . BASE_URL . '/fail?error=rate_limit');
        exit();
    }
}

// Process-donation dosyasını dahil et
require_once __DIR__ . '/../includes/actions/process-donation.php';

// Header'ı include et
require_once __DIR__ . '/../includes/header.php';

// Sepet ve bağış verilerini oturumdan al
$donationAmount = isset($_SESSION['cart_total']) ? $_SESSION['cart_total'] : 0;
$donationType = isset($_SESSION['donation_type']) ? $_SESSION['donation_type'] : (isset($_SESSION['selected_category']) ? $_SESSION['selected_category'] : 'Genel Bağış');
$donationId = isset($_SESSION['donation_id']) ? $_SESSION['donation_id'] : 0;

// ÖN KONTROLLER - Gerekli veriler var mı kontrol et
$requiredSessionData = ['cart_total', 'donor_name', 'donor_email', 'donor_phone'];
$missingData = [];

foreach ($requiredSessionData as $field) {
    if (!isset($_SESSION[$field]) || empty($_SESSION[$field])) {
        $missingData[] = $field;
    }
}

// Sepet tutarı kontrolü
if ($donationAmount <= 0) {
    $missingData[] = 'cart_total';
}

// Eğer gerekli veriler eksikse bağış sayfasına yönlendir
if (!empty($missingData)) {
    error_log("Payment sayfasına eksik bilgilerle erişim. Eksik veriler: " . implode(', ', $missingData));
    
    // Kullanıcıyı bilgilendirme mesajı ile birlikte bağış sayfasına yönlendir
    $_SESSION['payment_error'] = 'Ödeme yapabilmek için önce bağış bilgilerinizi tamamlamanız gerekiyor.';
    header('Location: ' . BASE_URL . '/donate');
    exit();
}

// Bağışçı bilgileri session'dan alınır
$donorName = isset($_SESSION['donor_name']) ? $_SESSION['donor_name'] : '';
$donorEmail = isset($_SESSION['donor_email']) ? $_SESSION['donor_email'] : '';
$donorPhone = isset($_SESSION['donor_phone']) ? $_SESSION['donor_phone'] : '';
$donorCity = isset($_SESSION['donor_city']) ? $_SESSION['donor_city'] : '';
$donorType = isset($_SESSION['donor_type']) ? $_SESSION['donor_type'] : 'individual';

// Oturumdan bağış bilgilerini al
$amount = number_format($donationAmount, 2, ',', '.');

// Geçerli sayfanın tam URL'sini al
$currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

// Kuveyt Türk için benzersiz sipariş no oluştur
$orderNo = "CIN" . time() . rand(1000, 9999);
?>

<div class="payment-container">
    <!-- Yükleme ekranı -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-spinner"></div>
        <div class="loading-text">Ödeme sistemine yönlendiriliyor...</div>
    </div>

    <!-- Ödeme Durumu Göstergesi -->
    <div id="paymentStatusIndicator" class="payment-status-indicator" style="display: none;">
        <div class="status-content">
            <div class="status-icon">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="status-message">
                <div class="status-title">Ödeme Durumu</div>
                <div class="status-description">İşleminiz işleniyor...</div>
            </div>
        </div>
    </div>

    <div class="payment-header">
        <div class="payment-steps">
            <a href="<?= BASE_URL ?>/cart" class="step-link">
                <i class="fas fa-chevron-left"></i> Sepetini Düzenle
            </a>
            <div class="payment-step-title">
                <span>Sepet Tutarı: </span>
                <span class="payment-amount">₺<?= $amount ?></span>
            </div>
        </div>
    </div>

    <div class="payment-content">
        <div class="payment-section">
            <h2 class="section-title">Ödeme Yönteminizi Seçin</h2>

            <div class="payment-methods">
                <div class="payment-method active" data-method="card">
                    <div class="method-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div class="method-text">Kartla Ödeme</div>
                    <div class="method-check">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="payment-section">
            <h2 class="section-title">Ödeme Bilgilerinizi Girin</h2>

            <!-- Kart ile ödeme içeriği -->
            <div class="card-info payment-content-section" id="cardPaymentContent">
                <!-- Kuveyt Türk NonThreeD Payment API için form -->
                <form id="paymentForm" method="post" action="<?= htmlspecialchars($currentUrl) ?>">
                    <input type="hidden" id="donation_id" name="donation_id" value="<?= $donationId ?>">
                    <input type="hidden" id="donation_type" name="donation_type"
                        value="<?= htmlspecialchars($donationType) ?>">
                    <input type="hidden" id="amount" name="amount" value="<?= $donationAmount * 100 ?>">
                    <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                    <input type="hidden" name="order_no" value="<?= $orderNo ?>">

                    <!-- Kişisel bilgiler session'dan alındığı için hidden field olarak eklenir -->
                    <input type="hidden" name="donor_name" value="<?= htmlspecialchars($donorName) ?>">
                    <input type="hidden" name="donor_email" value="<?= htmlspecialchars($donorEmail) ?>">
                    <input type="hidden" name="donor_phone" value="<?= htmlspecialchars($donorPhone) ?>">
                    <input type="hidden" name="city" value="<?= htmlspecialchars($donorCity) ?>">
                    <input type="hidden" name="donor_type" value="<?= htmlspecialchars($donorType) ?>">

                    <!-- Kuveyt Türk NonThreeD Payment API için kart bilgi alanları -->
                    <div class="card-label">KART BİLGİSİ</div>

                    <div class="card-number-container">
                        <input type="text" id="cardNumber" name="cardNumber" class="card-number-input"
                            placeholder="Kart Numarası" maxlength="19" autocomplete="cc-number">
                        <div class="card-type-display" id="cardTypeDisplay"></div>
                        <div class="card-validation-icon" id="cardValidationIcon"></div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Son Kul. Tarihi</label>
                            <input type="text" id="cardExpiry" name="cardExpiry" class="form-control"
                                placeholder="MM/YY" autocomplete="cc-exp">
                        </div>
                        <div class="form-group col-md-6">
                            <label>CVV</label>
                            <input type="text" id="cardCvv" name="cardCvv" class="form-control" placeholder="CVV"
                                autocomplete="cc-csc">
                        </div>
                    </div>

                    <!-- Kart sahibi adı -->
                    <div class="form-group">
                        <label>Kart Sahibinin Adı</label>
                        <input type="text" id="cardHolderName" name="cardHolderName" class="form-control"
                            placeholder="Kart üzerindeki isim">
                    </div>

                    <!-- Şehir Seçme Alanı -->
                    <div class="form-group mb-3">
                        <label for="city">Şehir</label>
                        <select id="city" name="city" class="form-control" required>
                            <option value="">Şehir Seçiniz</option>
                            <option value="Adana">Adana</option>
                            <option value="Adıyaman">Adıyaman</option>
                            <option value="Afyonkarahisar">Afyonkarahisar</option>
                            <option value="Ağrı">Ağrı</option>
                            <option value="Amasya">Amasya</option>
                            <option value="Ankara">Ankara</option>
                            <option value="Antalya">Antalya</option>
                            <option value="Artvin">Artvin</option>
                            <option value="Aydın">Aydın</option>
                            <option value="Balıkesir">Balıkesir</option>
                            <option value="Bilecik">Bilecik</option>
                            <option value="Bingöl">Bingöl</option>
                            <option value="Bitlis">Bitlis</option>
                            <option value="Bolu">Bolu</option>
                            <option value="Burdur">Burdur</option>
                            <option value="Bursa">Bursa</option>
                            <option value="Çanakkale">Çanakkale</option>
                            <option value="Çankırı">Çankırı</option>
                            <option value="Çorum">Çorum</option>
                            <option value="Denizli">Denizli</option>
                            <option value="Diyarbakır">Diyarbakır</option>
                            <option value="Edirne">Edirne</option>
                            <option value="Elazığ">Elazığ</option>
                            <option value="Erzincan">Erzincan</option>
                            <option value="Erzurum">Erzurum</option>
                            <option value="Eskişehir">Eskişehir</option>
                            <option value="Gaziantep">Gaziantep</option>
                            <option value="Giresun">Giresun</option>
                            <option value="Gümüşhane">Gümüşhane</option>
                            <option value="Hakkari">Hakkari</option>
                            <option value="Hatay">Hatay</option>
                            <option value="Isparta">Isparta</option>
                            <option value="Mersin">Mersin</option>
                            <option value="İstanbul">İstanbul</option>
                            <option value="İzmir">İzmir</option>
                            <option value="Kars">Kars</option>
                            <option value="Kastamonu">Kastamonu</option>
                            <option value="Kayseri">Kayseri</option>
                            <option value="Kırklareli">Kırklareli</option>
                            <option value="Kırşehir">Kırşehir</option>
                            <option value="Kocaeli">Kocaeli</option>
                            <option value="Konya">Konya</option>
                            <option value="Kütahya">Kütahya</option>
                            <option value="Malatya">Malatya</option>
                            <option value="Manisa">Manisa</option>
                            <option value="Kahramanmaraş">Kahramanmaraş</option>
                            <option value="Mardin">Mardin</option>
                            <option value="Muğla">Muğla</option>
                            <option value="Muş">Muş</option>
                            <option value="Nevşehir">Nevşehir</option>
                            <option value="Niğde">Niğde</option>
                            <option value="Ordu">Ordu</option>
                            <option value="Rize">Rize</option>
                            <option value="Sakarya">Sakarya</option>
                            <option value="Samsun">Samsun</option>
                            <option value="Siirt">Siirt</option>
                            <option value="Sinop">Sinop</option>
                            <option value="Sivas">Sivas</option>
                            <option value="Tekirdağ">Tekirdağ</option>
                            <option value="Tokat">Tokat</option>
                            <option value="Trabzon">Trabzon</option>
                            <option value="Tunceli">Tunceli</option>
                            <option value="Şanlıurfa">Şanlıurfa</option>
                            <option value="Uşak">Uşak</option>
                            <option value="Van">Van</option>
                            <option value="Yozgat">Yozgat</option>
                            <option value="Zonguldak">Zonguldak</option>
                        </select>
                    </div>

                    <div class="form-group message-group">
                        <label class="small-label">Çınaraltına Mesajınız</label>
                        <textarea class="form-control message-input" name="message" rows="2"
                            placeholder="İsteğe bağlı"></textarea>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="regularDonation" name="is_regular">
                        <label class="form-check-label" for="regularDonation">Düzenli bağış yapmak istiyorum</label>
                    </div>

                    <div id="regularDonationOptions" style="display: none;">
                        <div class="regular-donation-details">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tekrar süresi</label>
                                        <select class="form-control" name="donation_period">
                                            <option selected>1 ay</option>
                                            <option>2 ay</option>
                                            <option>3 ay</option>
                                            <option>4 ay</option>
                                            <option>5 ay</option>
                                            <option>6 ay</option>
                                            <option>7 ay</option>
                                            <option>8 ay</option>
                                            <option>9 ay</option>
                                            <option>10 ay</option>
                                            <option>11 ay</option>
                                            <option>12 ay</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ödeme günü</label>
                                        <select class="form-control" name="payment_day">
                                            <option selected>1. günü</option>
                                            <option>5. günü</option>
                                            <option>8. günü</option>
                                            <option>10. günü</option>
                                            <option>15. günü</option>
                                            <option>Son günü</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="payment-note">
                                İlk tahsilat şimdi yapılacaktır. Debit (atm, maaş) kartları için talimat verilemediğini
                                unutmayın.
                            </div>
                        </div>
                    </div>

                    <div class="recaptcha-container">
                        <div class="g-recaptcha" id="recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"
                            data-theme="light" data-size="normal" data-type="image"></div>
                        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                    </div>

                    <button type="submit" class="btn-pay" id="payButton">ÖDEME YAP</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
// Form gönderildiğinde işlem yap
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debug bilgilerini logla
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
        error_log('PAYMENT DEBUG: Form gönderildi');
        error_log('PAYMENT DEBUG: POST verileri: ' . print_r($_POST, true));
        error_log('PAYMENT DEBUG: reCAPTCHA response: ' . (isset($_POST['g-recaptcha-response']) ? 'Var (' . strlen($_POST['g-recaptcha-response']) . ' karakter)' : 'Yok'));
    }
    
    // Rate limiting kontrolü (ödeme denemesi) - DEBUG_MODE'da devre dışı
    if (PAYMENT_RATE_LIMIT_ENABLED && !check_rate_limit('payment_attempt', PAYMENT_MAX_ATTEMPTS, PAYMENT_RATE_LIMIT_WINDOW)) {
        log_payment_security_event('rate_limit_exceeded', ['action' => 'payment_attempt']);
        // DEBUG_MODE'da warning göster ama engelleme
        if (defined('DEBUG_MODE') && DEBUG_MODE) {
            error_log('WARNING: Payment attempt rate limit exceeded but allowing due to DEBUG_MODE');
        } else {
            echo "<script>window.location.href = '" . BASE_URL . "/fail?error=rate_limit';</script>";
            exit;
        }
    }
    
    // CSRF token kontrolü
    if (PAYMENT_CSRF_PROTECTION && (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token']))) {
        log_payment_security_event('invalid_csrf', ['ip' => $_SERVER['REMOTE_ADDR']]);
        error_log("Ödeme işlemi CSRF token hatası!");
        echo "<script>window.location.href = '" . BASE_URL . "/fail?error=security';</script>";
        exit;
    }
    
    // Input validation ve sanitization
    $donationData = [
        'donation_option_id' => isset($_POST['donation_id']) ? (int)$_POST['donation_id'] : 0,
        'donor_name' => isset($_POST['donor_name']) ? sanitize_input($_POST['donor_name']) : '',
        'donor_email' => isset($_POST['donor_email']) ? sanitize_input($_POST['donor_email']) : '',
        'donor_phone' => isset($_POST['donor_phone']) ? sanitize_input($_POST['donor_phone']) : '',
        'city' => isset($_POST['city']) ? sanitize_input($_POST['city']) : '',
        'amount' => isset($_POST['amount']) ? (float)($_POST['amount'] / 100) : 0, // Kuruştan TL'ye çevir
        'donation_option' => isset($_POST['donation_type']) ? sanitize_input($_POST['donation_type']) : 'Genel Bağış',
        'donor_type' => isset($_POST['donor_type']) ? sanitize_input($_POST['donor_type']) : 'individual',
        'payment_status' => 'pending', // Başlangıç durumu: beklemede
        'order_number' => $orderNo // Sipariş numarasını ekle
    ];
    
    // Comprehensive input validation
    $validation_errors = [];
    
    // Bağış tutarı kontrolü
    if (!validate_donation_amount($donationData['amount'])) {
        $validation_errors[] = 'Geçersiz bağış tutarı';
    }
    
    if ($donationData['amount'] < PAYMENT_MIN_AMOUNT || $donationData['amount'] > PAYMENT_MAX_AMOUNT) {
        $validation_errors[] = 'Bağış tutarı belirlenen limitler dışında';
    }
    
    // Email kontrolü
    if (empty($donationData['donor_email']) || !validate_email($donationData['donor_email'])) {
        $validation_errors[] = 'Geçersiz email adresi';
    }
    
    // Telefon kontrolü
    if (empty($donationData['donor_phone']) || !validate_phone($donationData['donor_phone'])) {
        $validation_errors[] = 'Geçersiz telefon numarası';
    }
    
    // İsim kontrolü
    if (empty($donationData['donor_name']) || strlen($donationData['donor_name']) < 2) {
        $validation_errors[] = 'Geçersiz isim';
    }
    
    // Şehir kontrolü
    if (empty($donationData['city'])) {
        $validation_errors[] = 'Şehir seçimi zorunludur';
    }
    
    // Kart bilgileri kontrolü
    $cardNumber = isset($_POST['cardNumber']) ? preg_replace('/\D/', '', $_POST['cardNumber']) : '';
    if (!validate_card_number($cardNumber)) {
        $validation_errors[] = 'Geçersiz kart numarası';
    }
    
    // CVV kontrolü
    $cardCvv = isset($_POST['cardCvv']) ? preg_replace('/\D/', '', $_POST['cardCvv']) : '';
    if (strlen($cardCvv) < 3 || strlen($cardCvv) > 4) {
        $validation_errors[] = 'Geçersiz CVV';
    }
    
    // Son kullanma tarihi kontrolü
    $cardExpiry = isset($_POST['cardExpiry']) ? $_POST['cardExpiry'] : '';
    if (!preg_match('/^(0[1-9]|1[0-2])\/([0-9]{2})$/', $cardExpiry)) {
        $validation_errors[] = 'Geçersiz son kullanma tarihi';
    }
    
    // Kart sahibi adı kontrolü
    $cardHolderName = isset($_POST['cardHolderName']) ? sanitize_input($_POST['cardHolderName']) : '';
    if (empty($cardHolderName) || strlen($cardHolderName) < 2) {
        $validation_errors[] = 'Kart sahibi adı gereklidir';
    }
    
    // reCAPTCHA doğrulama - DEBUG_MODE'da atla
    if (!defined('DEBUG_MODE') || !DEBUG_MODE) {
        $recaptcha_response = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';
        if (!verify_recaptcha($recaptcha_response)) {
            $validation_errors[] = 'reCAPTCHA doğrulaması başarısız';
        }
    } else {
        error_log('DEBUG_MODE: reCAPTCHA doğrulaması atlandı');
    }
    
    // Validation hatası varsa durdur
    if (!empty($validation_errors)) {
        log_payment_security_event('validation_failed', [
            'errors' => $validation_errors,
            'donor_email' => $donationData['donor_email']
        ]);
        $error_message = implode(', ', $validation_errors);
        echo "<script>window.location.href = '" . BASE_URL . "/fail?error=validation_failed&ErrorMessage=" . urlencode($error_message) . "';</script>";
        exit;
    }
    
    // Debug: Bağış verilerini logla
    if (PAYMENT_DEBUG) {
        error_log("Bağış verileri: " . print_r($donationData, true));
    }
    
    // Veritabanı bağlantısını test et
    try {
        $testDb = db_connect();
        error_log("PAYMENT DEBUG: Veritabanı bağlantısı başarılı");
    } catch (Exception $e) {
        error_log("PAYMENT DEBUG: Veritabanı bağlantı hatası: " . $e->getMessage());
        echo "<script>window.location.href = '" . BASE_URL . "/fail?error=database_connection&ErrorMessage=" . urlencode('Veritabanı bağlantısı kurulamadı') . "';</script>";
        exit;
    }
    
    // Bağış verisini veritabanına kaydet
    $donationId = save_donation($donationData);
    
    // Debug: Kayıt sonucunu logla
    if (PAYMENT_DEBUG) {
        error_log("Bağış kayıt sonucu: " . ($donationId ? "Başarılı (ID: $donationId)" : "Başarısız"));
    }
    
    // Kayıt başarısızsa hata sayfasına yönlendir
    if (!$donationId) {
        error_log("Bağış kaydetme hatası oluştu!");
        // Daha detaylı hata mesajı için error log'u kontrol et
        if (PAYMENT_DEBUG) {
            echo "<script>alert('Bağış kaydetme hatası! Daha fazla bilgi için browser console ve server error log dosyasını kontrol edin.'); window.location.href = '" . BASE_URL . "/fail?error=database';</script>";
        } else {
            echo "<script>window.location.href = '" . BASE_URL . "/fail?error=database';</script>";
        }
        exit;
    }
    
    // Bağış ID'sini oturuma kaydet (ödeme sonucunu güncellemek için)
    $_SESSION['donation_made_id'] = $donationId;
    
    // Debug modu ayarlarını uygula
    ini_set('display_errors', PAYMENT_DEBUG ? 1 : 0);
    error_reporting(PAYMENT_DEBUG ? E_ALL : 0);
    
    // Ödeme API bilgilerini kontrol et
    $merchantId = PAYMENT_MERCHANT_ID;
    $apiKey = PAYMENT_API_KEY;
    
    // API bilgileri eksikse hata ver
    if (empty($merchantId) || empty($apiKey) || $merchantId === 'your_merchant_id_here') {
        error_log("Ödeme API bilgileri eksik veya yapılandırılmamış!");
        echo "<script>window.location.href = '" . BASE_URL . "/fail?error=configuration&ErrorMessage=" . urlencode('Ödeme sistemi yapılandırması tamamlanmamış. Lütfen site yöneticisiyle iletişime geçin.') . "';</script>";
        exit;
    }
    
    // Form verilerini doğrula
    $amount = isset($_POST['amount']) ? (int)$_POST['amount'] : 0;
    $orderNo = isset($_POST['order_no']) ? sanitize_input($_POST['order_no']) : '';
    
    // Tutar kontrolü
    if ($amount <= 0) {
        echo "<script>window.location.href = '" . BASE_URL . "/fail?error=invalid_amount&ErrorMessage=" . urlencode('Geçersiz bağış tutarı. Lütfen tutarı kontrol ediniz.') . "';</script>";
        exit;
    }
    
    // Kart bilgileri kontrolü
    $cardNumber = isset($_POST['cardNumber']) ? preg_replace('/\D/', '', $_POST['cardNumber']) : '';
    if (strlen($cardNumber) < 13 || strlen($cardNumber) > 19) {
        echo "<script>window.location.href = '" . BASE_URL . "/fail?error=invalid_card&ErrorMessage=" . urlencode('Geçersiz kart numarası. Lütfen kart bilgilerinizi kontrol ediniz.') . "';</script>";
        exit;
    }
    
    // URL'ler için tam yol kullan
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    $domain = $protocol . '://' . $_SERVER['HTTP_HOST'];
    
    // Tam yolu direkt php dosyalarına yönlendir (index.php üzerinden değil)
    $successUrl = $domain . dirname($_SERVER['SCRIPT_NAME']) . "/success.php";
    $errorUrl = $domain . dirname($_SERVER['SCRIPT_NAME']) . "/fail.php";
    
    try {
            // Geliştirme modunda test için JavaScript ile yönlendir
    if (PAYMENT_DEBUG) {
        echo "<script>window.location.href = '" . $successUrl . "?OrderId=TEST_" . $donationId . "&AuthCode=TEST_AUTH_" . rand(100000, 999999) . "';</script>";
        exit;
    }

        // Kart ve ödeme bilgilerini al
        $cardNumber = isset($_POST['cardNumber']) ? preg_replace('/\D/', '', $_POST['cardNumber']) : '';
        $identityTaxNumber = isset($_POST['identityTaxNumber']) ? preg_replace('/\D/', '', $_POST['identityTaxNumber']) : '';
        
        // API için gerekli parametreleri hazırla
        $userName = "ApiUser"; // Kuveyt Türk tarafından verilen kullanıcı adı
        
        // Hash hesapla
        $hashString = $merchantId . $userName . $amount . $orderNo . $successUrl . $errorUrl . $apiKey;
        $hashData = base64_encode(hash('sha256', $hashString, true));
        
        // API isteği için veri hazırla
        $apiData = [
            "APIPaymentTransactionContract" => [
                "merchantId" => $merchantId,
                "customerId" => $donationId,
                "userName" => $userName,
                "amount" => (string)$amount,
                "merchantOrderId" => $orderNo,
                "cardNumber" => $cardNumber,
                "currencyCode" => PAYMENT_CURRENCY_CODE,
                "transactionType" => PAYMENT_TRANSACTION_TYPE,
                "identityTaxNumber" => $identityTaxNumber,
                "hashData" => $hashData,
                "installmentCount" => "0",
                "description" => $donationData['donation_option'],
                "insuranceDeferringCount" => "0"
            ]
        ];
        
        // API endpoint
        $apiEndpoint = PAYMENT_API_URL;
        
        // cURL ile API isteği gönder
        $ch = curl_init($apiEndpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($apiData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 30 saniye timeout
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // 10 saniye bağlantı timeout
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);
        
        // API yanıtını al
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        // cURL hatası kontrolü
        if ($curlError) {
            error_log("cURL hatası: " . $curlError);
            echo "<script>window.location.href = '" . BASE_URL . "/fail?error=network_error&ErrorMessage=" . urlencode('Ağ bağlantısı sorunu. Lütfen internet bağlantınızı kontrol edip tekrar deneyiniz.') . "';</script>";
            exit;
        }
        
        if ($httpCode == 200) {
            $responseData = json_decode($response, true);
            
            // İşlem başarılı mı kontrol et
            if (isset($responseData['success']) && $responseData['success'] === true) {
                $firstResult = $responseData['value'][0];
                $responseCode = $firstResult['responseCode'] ?? '';
                
                if ($responseCode === "00") {
                    // İşlem başarılı, başarı sayfasına yönlendir
                    $provisionNumber = $firstResult['provisionNumber'] ?? '';
                    $orderId = $firstResult['orderId'] ?? '';
                    
                    // Önce payment sayfasına başarılı durum ile yönlendir
                    echo "<script>window.location.href = '" . $currentUrl . "?status=success&OrderId=" . $orderId . "&AuthCode=" . $provisionNumber . "';</script>";
                    exit;
                } else {
                    // İşlem başarısız, hata mesajı ile payment sayfasına yönlendir
                    $responseMessage = $firstResult['responseMessage'] ?? 'İşlem başarısız';
                    echo "<script>window.location.href = '" . $currentUrl . "?status=failed&ErrorCode=" . $responseCode . "&ErrorMessage=" . urlencode($responseMessage) . "';</script>";
                    exit;
                }
            } else {
                // API yanıtı başarısız
                $errorMessage = isset($responseData['errors']) ? implode(", ", $responseData['errors']) : 'API yanıtı başarısız';
                echo "<script>window.location.href = '" . $currentUrl . "?status=failed&error=payment_gateway&ErrorMessage=" . urlencode($errorMessage) . "';</script>";
                exit;
            }
        } else {
            // HTTP hatası
            echo "<script>window.location.href = '" . $currentUrl . "?status=failed&error=payment_gateway&ErrorMessage=" . urlencode("HTTP Hata Kodu: " . $httpCode) . "';</script>";
            exit;
        }
        
    } catch (Exception $e) {
        // Hata durumunda kullanıcıyı payment sayfasına yönlendir
        error_log("Ödeme işleme hatası: " . $e->getMessage());
        echo "<script>window.location.href = '" . $currentUrl . "?status=failed&error=payment_gateway&ErrorMessage=" . urlencode($e->getMessage()) . "';</script>";
        exit;
    }
}
?>

<style>
.payment-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 30px 20px 60px;
    margin-top: 90px;
    position: relative;
}

/* Yükleme ekranı stili */
.loading-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.9);
    z-index: 9999;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

/* Ödeme Durumu Göstergesi */
.payment-status-indicator {
    background-color: #e3f2fd;
    border: 1px solid #2196F3;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
}

.status-content {
    display: flex;
    align-items: center;
    gap: 12px;
    width: 100%;
}

.status-icon {
    font-size: 24px;
    color: #2196F3;
    flex-shrink: 0;
}

.status-message {
    flex: 1;
}

.status-title {
    font-weight: 600;
    color: #1976D2;
    margin-bottom: 4px;
}

.status-description {
    color: #424242;
    font-size: 14px;
}

/* Bildirim stili */
.payment-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: #4CAF50;
    color: white;
    padding: 15px 20px;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    z-index: 10000;
    display: flex;
    align-items: center;
    gap: 12px;
    max-width: 400px;
    transform: translateX(100%);
    transition: transform 0.3s ease;
    border-left: 4px solid #45a049;
}

.payment-notification.show {
    transform: translateX(0);
}

.notification-icon {
    font-size: 20px;
    flex-shrink: 0;
}

.notification-message {
    flex: 1;
    font-size: 14px;
    line-height: 1.4;
}

.notification-close {
    background: none;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
    padding: 0;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0.8;
    transition: opacity 0.2s;
}

.notification-close:hover {
    opacity: 1;
}

.notification-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.notification-success-btn {
    background-color: rgba(255, 255, 255, 0.2);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.3);
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s;
    align-self: flex-start;
}

.notification-success-btn:hover {
    background-color: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.5);
}

.loading-spinner {
    width: 50px;
    height: 50px;
    border: 5px solid #f3f3f3;
    border-top: 5px solid #4CAF50;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 20px;
}

.loading-text {
    font-size: 18px;
    color: #333;
    font-weight: 500;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

.payment-header {
    margin-bottom: 30px;
}

.payment-steps {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.step-link {
    color: #4CAF50;
    font-weight: 500;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
}

.step-link:hover {
    text-decoration: underline;
}

.payment-step-title {
    font-size: 18px;
    font-weight: 500;
}

.payment-amount {
    font-weight: 700;
    color: #4CAF50;
}

.payment-content {
    display: flex;
    flex-direction: column;
    gap: 40px;
}

.payment-section {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    padding: 30px;
}

.section-title {
    font-size: 20px;
    font-weight: 600;
    color: #333;
    margin-bottom: 20px;
    text-align: center;
}

.payment-methods {
    display: flex;
    gap: 20px;
    justify-content: center;
}

.payment-method {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 150px;
}

.payment-method.active {
    border-color: #4CAF50;
    box-shadow: 0 5px 15px rgba(76, 175, 80, 0.1);
}

.method-icon {
    font-size: 30px;
    margin-bottom: 15px;
    color: #4CAF50;
}

.method-text {
    font-size: 16px;
    margin-bottom: 10px;
    text-align: center;
}

.method-check {
    color: #4CAF50;
    font-size: 20px;
}

.card-info {
    max-width: 500px;
    margin: 0 auto;
}

.card-label {
    background-color: #4CAF50;
    color: white;
    display: inline-block;
    padding: 8px 16px;
    font-weight: 600;
    font-size: 16px;
    margin-bottom: 20px;
    border-radius: 4px;
}

.card-number-container {
    position: relative;
    margin-bottom: 25px;
    border: 1px solid #ced4da;
    border-radius: 5px;
    overflow: hidden;
}

.card-number-input {
    width: 100%;
    padding: 15px;
    font-size: 22px;
    border: none;
    letter-spacing: 1px;
    font-family: monospace;
    color: #495057;
    background-color: #fff;
}

.card-number-input:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.25);
}

/* Kart tipi gösterme alanı */
.card-type-display {
    position: absolute;
    right: 50px;
    top: 50%;
    transform: translateY(-50%);
    width: 32px;
    height: 20px;
    background-position: center;
    background-repeat: no-repeat;
    background-size: contain;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.card-type-display.show {
    opacity: 1;
}

/* Kart tipi ikonları */
.card-type-display.visa {
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 32" fill="none"><rect width="48" height="32" rx="4" fill="%231a1f71"/><path d="M18.5 11h-3.2l-2 10h2l.4-2h1.6c1.3 0 2.4-1.1 2.4-2.4v-3.2c0-1.3-1.1-2.4-2.4-2.4h-1.6z" fill="white"/><path d="M22.8 21l1.6-10h2l-1.6 10h-2z" fill="white"/><path d="M27.2 15.6c0-1.3 1.1-2.4 2.4-2.4h1.6c1.3 0 2.4 1.1 2.4 2.4v1.6h-4v1.6c0 .4.4.8.8.8h3.2l-.4 2h-3.2c-1.3 0-2.4-1.1-2.4-2.4v-3.2z" fill="white"/><path d="M36.8 11h2l-2 10h-2l2-10z" fill="white"/></svg>');
}

.card-type-display.mastercard {
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 32" fill="none"><rect width="48" height="32" rx="4" fill="%23eb001b"/><circle cx="18" cy="16" r="8" fill="%23ff5f00"/><circle cx="30" cy="16" r="8" fill="%23f79e1b"/><path d="M24 10.4c1.2 1.2 2 2.8 2 4.6s-.8 3.4-2 4.6c-1.2-1.2-2-2.8-2-4.6s.8-3.4 2-4.6z" fill="%23ff5f00"/></svg>');
}

.card-type-display.amex {
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 32" fill="none"><rect width="48" height="32" rx="4" fill="%23006fcf"/><path d="M12 11h8l-2 2h-4l-1 2h3l-1 2h-3l-1 2h4l2 2h-8l3-10z" fill="white"/><path d="M21 11h2l3 10h-2l-1-2h-2l-1 2h-2l3-10zm1 6h1l-1-3-1 3z" fill="white"/><path d="M28 11h6l-1 2h-2v8h-2v-8h-2l1-2z" fill="white"/><path d="M36 11h2l3 4v-4h2v10h-2l-3-4v4h-2v-10z" fill="white"/></svg>');
}

.card-type-display.troy {
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 32" fill="none"><rect width="48" height="32" rx="4" fill="%230066cc"/><text x="24" y="20" text-anchor="middle" fill="white" font-family="Arial, sans-serif" font-size="8" font-weight="bold">TROY</text></svg>');
}

.card-type-display.discover {
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 32" fill="none"><rect width="48" height="32" rx="4" fill="%23ff6000"/><text x="24" y="20" text-anchor="middle" fill="white" font-family="Arial, sans-serif" font-size="6" font-weight="bold">DISCOVER</text></svg>');
}

.card-type-display.unionpay {
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 32" fill="none"><rect width="48" height="32" rx="4" fill="%23e21836"/><circle cx="16" cy="16" r="6" fill="%23006fcf"/><circle cx="32" cy="16" r="6" fill="%2300aa44"/><path d="M24 10c3.3 0 6 2.7 6 6s-2.7 6-6 6-6-2.7-6-6 2.7-6 6-6z" fill="%23ff6000"/></svg>');
}

/* Türk bankaları için özel ikonlar */
.card-type-display.ziraat {
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 32" fill="none"><rect width="48" height="32" rx="4" fill="%23c8102e"/><text x="24" y="20" text-anchor="middle" fill="white" font-family="Arial, sans-serif" font-size="6" font-weight="bold">ZİRAAT</text></svg>');
}

.card-type-display.garanti {
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 32" fill="none"><rect width="48" height="32" rx="4" fill="%23004225"/><text x="24" y="20" text-anchor="middle" fill="white" font-family="Arial, sans-serif" font-size="5" font-weight="bold">GARANTİ</text></svg>');
}

.card-type-display.isbank {
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 32" fill="none"><rect width="48" height="32" rx="4" fill="%23005aa0"/><text x="24" y="20" text-anchor="middle" fill="white" font-family="Arial, sans-serif" font-size="5" font-weight="bold">İŞ BANK</text></svg>');
}

.card-type-display.akbank {
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 32" fill="none"><rect width="48" height="32" rx="4" fill="%23e30613"/><text x="24" y="20" text-anchor="middle" fill="white" font-family="Arial, sans-serif" font-size="6" font-weight="bold">AKBANK</text></svg>');
}

.card-type-display.yapıkredi {
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 32" fill="none"><rect width="48" height="32" rx="4" fill="%23003a70"/><text x="24" y="18" text-anchor="middle" fill="white" font-family="Arial, sans-serif" font-size="4" font-weight="bold">YAPI</text><text x="24" y="24" text-anchor="middle" fill="white" font-family="Arial, sans-serif" font-size="4" font-weight="bold">KREDİ</text></svg>');
}

.card-type-display.teb {
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 32" fill="none"><rect width="48" height="32" rx="4" fill="%23ffd100"/><text x="24" y="20" text-anchor="middle" fill="%23000" font-family="Arial, sans-serif" font-size="8" font-weight="bold">TEB</text></svg>');
}

.card-type-display.enpara {
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 32" fill="none"><rect width="48" height="32" rx="4" fill="%23ff6900"/><text x="24" y="20" text-anchor="middle" fill="white" font-family="Arial, sans-serif" font-size="5" font-weight="bold">ENPARA</text></svg>');
}

/* Doğrulama ikonu */
.card-validation-icon {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    width: 20px;
    height: 20px;
    background-position: center;
    background-repeat: no-repeat;
    background-size: contain;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.card-validation-icon.show {
    opacity: 1;
}

.card-validation-icon.valid {
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%234CAF50" width="20px" height="20px"><path d="M0 0h24v24H0z" fill="none"/><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>');
}

.card-validation-icon.invalid {
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23F44336" width="20px" height="20px"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>');
}

/* Kart numarası input'u için ekstra stil */
.card-number-input.valid {
    border-color: #4CAF50;
    box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.25);
}

.card-number-input.invalid {
    border-color: #F44336;
    box-shadow: 0 0 0 2px rgba(244, 67, 54, 0.25);
}

.form-group {
    margin-bottom: 20px;
}

.form-row {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-size: 14px;
    color: #6c757d;
    margin-bottom: 8px;
    font-weight: 500;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ced4da;
    border-radius: 5px;
    font-size: 16px;
    transition: border-color 0.15s ease-in-out;
}

.form-control:focus {
    border-color: #4CAF50;
    outline: none;
}

textarea.form-control {
    resize: none;
}

.form-check {
    display: flex;
    align-items: center;
    margin-bottom: 25px;
}

.form-check-input {
    margin-right: 8px;
}

.payment-note {
    font-size: 14px;
    color: #6c757d;
    line-height: 1.5;
    margin: 15px 0 25px;
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 4px;
}

.regular-donation-details {
    padding: 15px;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    margin-bottom: 25px;
    background-color: #fff;
}

.regular-donation-details label {
    font-weight: normal;
    color: #6c757d;
}

.recaptcha-container {
    margin-bottom: 25px;
    display: flex;
    justify-content: center;
}

.g-recaptcha {
    transform-origin: left top;
}

.btn-pay {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 15px;
    border-radius: 5px;
    font-size: 16px;
    font-weight: 600;
    width: 100%;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn-pay:hover {
    background-color: #3d9140;
}

@media (max-width: 768px) {
    .payment-methods {
        flex-direction: column;
        align-items: center;
    }

    .payment-method {
        width: 100%;
        max-width: 250px;
    }

    .form-row {
        flex-direction: column;
        gap: 10px;
    }
}

.message-group {
    margin-bottom: 15px;
}

.small-label {
    font-size: 12px;
    color: #6c757d;
    margin-bottom: 5px;
    font-weight: 500;
}

.message-input {
    font-size: 14px;
    min-height: 50px;
    resize: none;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // URL parametrelerini kontrol et - ödeme sonucu durumu için
    const urlParams = new URLSearchParams(window.location.search);
    const paymentStatus = urlParams.get('status');
    const orderId = urlParams.get('OrderId');
    const errorCode = urlParams.get('ErrorCode');
    const errorMessage = urlParams.get('ErrorMessage');

    // Ödeme sonucu durumunu kontrol et ve bildirim göster
    if (paymentStatus || orderId || errorCode) {
        showPaymentResult(paymentStatus, orderId, errorCode, errorMessage);
        updatePaymentStatusIndicator(paymentStatus, orderId, errorCode, errorMessage);
    }

    // Sepet tutarını localStorage'dan al ve göster
    const storedAmount = localStorage.getItem('cartTotalAmount');
    if (storedAmount) {
        const amountText = storedAmount.split(' ')[0]; // Sadece fiyat kısmını al (₺12,00)
        document.querySelector('.payment-amount').textContent = amountText;

        // Hidden input'a kuruş cinsinden değeri aktar
        try {
            // ₺12,00 -> 1200 kuruş formatına çevir
            let numericAmount = amountText.replace('₺', '').replace(',', '.');
            let amountInKurus = Math.round(parseFloat(numericAmount) * 100);
            document.getElementById('amount').value = amountInKurus;
        } catch (e) {
            console.error('Sepet tutarı dönüştürülemedi:', e);
        }
    }

    // Şehir bilgisini session'dan al ve seç
    <?php if (!empty($donorCity)): ?>
    const citySelect = document.getElementById('city');
    if (citySelect) {
        citySelect.value = '<?= htmlspecialchars($donorCity) ?>';
    }
    <?php endif; ?>

    // Formu işleme
    const paymentForm = document.getElementById('paymentForm');
    const loadingOverlay = document.getElementById('loadingOverlay');

    if (paymentForm) {
        // Form gönderilirken yükleme ekranını göster
        paymentForm.addEventListener('submit', function(e) {
            // Form doğrulama
            if (!validatePaymentForm()) {
                e.preventDefault();
                return false;
            }

            // Yükleme ekranını göster
            if (loadingOverlay) {
                loadingOverlay.style.display = 'flex';
            }
        });



        // Kredi kartı numarası formatı ve doğrulama
        const cardInput = document.getElementById('cardNumber');
        const cardTypeDisplay = document.getElementById('cardTypeDisplay');
        const cardValidationIcon = document.getElementById('cardValidationIcon');

        if (cardInput) {
            cardInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');

                // Kart tipine göre maksimum uzunluk belirle
                const cardType = detectCardType(value);
                const maxLength = getCardMaxLength(cardType);

                // Maksimum uzunluktan fazla girilmesin
                if (value.length > maxLength) {
                    value = value.slice(0, maxLength);
                }

                // Formatlama - kart tipine göre
                let formattedValue = formatCardNumber(value, cardType);
                e.target.value = formattedValue;

                // Kart tipi ve doğrulama durumunu güncelle
                updateCardDisplay(value, cardType);
            });

            // Focus lost olduğunda son bir kez doğrulama yap
            cardInput.addEventListener('blur', function(e) {
                const value = e.target.value.replace(/\D/g, '');
                const cardType = detectCardType(value);
                updateCardDisplay(value, cardType, true);
            });
        }

        // Kart tipini tespit etme fonksiyonu
        function detectCardType(number) {
            // Kart tipi regex tanımları (BIN kodlarına göre)
            const cardTypes = {
                visa: /^4[0-9]/,
                mastercard: /^(5[1-5][0-9]|2(2[2-9][0-9]|[3-6][0-9][0-9]|7[0-1][0-9]|720))/,
                amex: /^3[47]/,
                discover: /^(6011|622(1(2[6-9]|[3-9][0-9])|[2-8][0-9]{2}|9([01][0-9]|2[0-5]))|64[4-9]|65)/,
                troy: /^(9792|9794)/,
                unionpay: /^(62|81)/,
                // Türk bankaları için özel BIN kodları
                ziraat: /^(4546|4576|5528|5279)/,
                garanti: /^(4282|4355|5571|5504)/,
                isbank: /^(4508|4253|5456|5552)/,
                akbank: /^(4671|4090|5549|5274)/,
                yapıkredi: /^(4511|4603|5401|5440)/,
                teb: /^(4029|4543|5296|5183)/,
                enpara: /^(5311|5312)/
            };

            for (const [type, regex] of Object.entries(cardTypes)) {
                if (regex.test(number)) {
                    return type;
                }
            }

            return 'unknown';
        }

        // Kart tipi adını Türkçe olarak döndür
        function getCardTypeName(cardType) {
            const cardNames = {
                visa: 'Visa',
                mastercard: 'Mastercard',
                amex: 'American Express',
                discover: 'Discover',
                troy: 'Troy',
                unionpay: 'UnionPay',
                ziraat: 'Ziraat Bankası',
                garanti: 'Garanti BBVA',
                isbank: 'Türkiye İş Bankası',
                akbank: 'Akbank',
                yapıkredi: 'Yapı Kredi',
                teb: 'TEB',
                enpara: 'Enpara.com',
                unknown: 'Bilinmeyen Kart Tipi'
            };

            return cardNames[cardType] || cardNames.unknown;
        }

        // Kart tipine göre maksimum uzunluk
        function getCardMaxLength(cardType) {
            const lengths = {
                visa: 16,
                mastercard: 16,
                amex: 15,
                discover: 16,
                troy: 16,
                unionpay: 19,
                // Türk bankaları (genellikle Visa/Mastercard altyapısı)
                ziraat: 16,
                garanti: 16,
                isbank: 16,
                akbank: 16,
                yapıkredi: 16,
                teb: 16,
                enpara: 16
            };
            return lengths[cardType] || 16;
        }

        // Kart numarasını formatlama
        function formatCardNumber(value, cardType) {
            let formattedValue = '';

            if (cardType === 'amex') {
                // American Express: 4-6-5 formatı
                for (let i = 0; i < value.length; i++) {
                    if (i === 4 || i === 10) {
                        formattedValue += ' ';
                    }
                    formattedValue += value[i];
                }
            } else {
                // Diğer kartlar: 4-4-4-4 formatı
                for (let i = 0; i < value.length; i++) {
                    if (i > 0 && i % 4 === 0) {
                        formattedValue += ' ';
                    }
                    formattedValue += value[i];
                }
            }

            return formattedValue;
        }

        // Luhn algoritması ile kart numarası doğrulama
        function validateCardNumber(number) {
            if (!number || number.length < 13) return false;

            let sum = 0;
            let isEven = false;

            // Sağdan sola doğru işle
            for (let i = number.length - 1; i >= 0; i--) {
                let digit = parseInt(number[i]);

                if (isEven) {
                    digit *= 2;
                    if (digit > 9) {
                        digit -= 9;
                    }
                }

                sum += digit;
                isEven = !isEven;
            }

            return sum % 10 === 0;
        }

        // Kart görüntüleme ve doğrulama durumunu güncelle
        function updateCardDisplay(number, cardType, finalValidation = false) {
            // Kart tipi gösterimi
            if (cardTypeDisplay) {
                cardTypeDisplay.className = 'card-type-display';
                if (cardType !== 'unknown' && number.length >= 4) {
                    cardTypeDisplay.classList.add('show', cardType);
                }
            }

            // Doğrulama ikonu
            if (cardValidationIcon && cardInput) {
                cardValidationIcon.className = 'card-validation-icon';
                cardInput.className = 'card-number-input';

                if (number.length >= 13) {
                    const isValid = validateCardNumber(number);
                    const expectedLength = getCardMaxLength(cardType);
                    const isCompleteLength = number.length >= expectedLength;

                    if (finalValidation) {
                        // Son doğrulama - tam uzunluk ve Luhn kontrolü
                        if (isValid && isCompleteLength) {
                            cardValidationIcon.classList.add('show', 'valid');
                            cardInput.classList.add('valid');
                        } else {
                            cardValidationIcon.classList.add('show', 'invalid');
                            cardInput.classList.add('invalid');
                        }
                    } else {
                        // Gerçek zamanlı doğrulama - daha yumuşak
                        if (isValid) {
                            cardValidationIcon.classList.add('show', 'valid');
                            cardInput.classList.add('valid');
                        } else if (number.length >= expectedLength) {
                            cardValidationIcon.classList.add('show', 'invalid');
                            cardInput.classList.add('invalid');
                        }
                    }
                }
            }
        }

        // Form doğrulama fonksiyonunu güncelle
        function validatePaymentForm() {
            const cardNumber = document.getElementById('cardNumber').value.replace(/\s/g, '');
            const cardExpiry = document.getElementById('cardExpiry').value;
            const cardCvv = document.getElementById('cardCvv').value;
            const cardHolderName = document.getElementById('cardHolderName').value;
            const city = document.getElementById('city').value;

            // reCAPTCHA kontrolü - DEBUG_MODE'da atla
            <?php if (!defined('DEBUG_MODE') || !DEBUG_MODE): ?>
            const recaptchaResponse = grecaptcha.getResponse();
            if (!recaptchaResponse) {
                showNotification('Lütfen reCAPTCHA doğrulamasını tamamlayınız.', 'error');
                return false;
            }
            
            // Hidden input'a reCAPTCHA response'unu ekle
            const recaptchaInput = document.getElementById('g-recaptcha-response');
            if (recaptchaInput) {
                recaptchaInput.value = recaptchaResponse;
            }
            <?php else: ?>
            console.log('DEBUG_MODE: reCAPTCHA kontrolü atlandı');
            <?php endif; ?>

            // Kart numarası kontrolü - Luhn algoritması ile
            if (!validateCardNumber(cardNumber)) {
                showNotification('Geçersiz kart numarası. Lütfen kart bilgilerinizi kontrol ediniz.', 'error');
                return false;
            }

            // Kart tipi kontrolü
            const cardType = detectCardType(cardNumber);
            const expectedLength = getCardMaxLength(cardType);
            if (cardNumber.length < expectedLength) {
                const cardTypeName = getCardTypeName(cardType);
                showNotification(
                    `Kart numarası eksik. ${cardTypeName} kartları ${expectedLength} haneli olmalıdır.`,
                    'error');
                return false;
            }

            // Son kullanma tarihi kontrolü
            if (!cardExpiry || cardExpiry.length !== 5) {
                showNotification('Geçersiz son kullanma tarihi. Lütfen MM/YY formatında giriniz.', 'error');
                return false;
            }

            // Son kullanma tarihinin geçmişte olup olmadığını kontrol et
            const [month, year] = cardExpiry.split('/');
            const expiryDate = new Date(2000 + parseInt(year), parseInt(month) - 1);
            const currentDate = new Date();
            if (expiryDate < currentDate) {
                showNotification('Kartınızın son kullanma tarihi geçmiş. Lütfen geçerli bir kart kullanınız.',
                    'error');
                return false;
            }

            // CVV kontrolü - kart tipine göre
            const expectedCvvLength = cardType === 'amex' ? 4 : 3;
            if (cardCvv.length !== expectedCvvLength) {
                const cardTypeName = getCardTypeName(cardType);
                showNotification(
                    `Geçersiz CVV. ${cardTypeName} kartları için ${expectedCvvLength} haneli kod giriniz.`,
                    'error');
                return false;
            }

            // Kart sahibi adı kontrolü
            if (!cardHolderName.trim()) {
                showNotification('Lütfen kart sahibinin adını giriniz.', 'error');
                return false;
            }

            // Şehir kontrolü
            if (!city) {
                showNotification('Lütfen şehir seçiniz.', 'error');
                return false;
            }

            return true;
        }

        // Son kullanma tarihi formatı
        const expiryInput = document.getElementById('cardExpiry');
        if (expiryInput) {
            expiryInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');

                // 4 haneden fazla girilmesin
                if (value.length > 4) {
                    value = value.slice(0, 4);
                }

                // MM/YY formatı
                if (value.length > 2) {
                    value = value.slice(0, 2) + '/' + value.slice(2);
                }

                e.target.value = value;
            });
        }

        // CVV formatı - kart tipine göre dinamik
        const cvvInput = document.getElementById('cardCvv');
        if (cvvInput) {
            cvvInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');

                // Kart tipini al
                const cardNumber = document.getElementById('cardNumber').value.replace(/\D/g, '');
                const cardType = detectCardType(cardNumber);

                // CVV uzunluğunu kart tipine göre belirle
                const maxCvvLength = cardType === 'amex' ? 4 : 3;

                // Maksimum uzunluktan fazla girilmesin
                if (value.length > maxCvvLength) {
                    value = value.slice(0, maxCvvLength);
                }

                e.target.value = value;

                // Placeholder'ı güncelle
                if (cardType === 'amex') {
                    e.target.placeholder = 'CVVV (4 hane)';
                } else {
                    e.target.placeholder = 'CVV (3 hane)';
                }
            });
        }

        // Düzenli bağış seçeneği kontrolü
        const regularDonationCheck = document.getElementById('regularDonation');
        const regularDonationOptions = document.getElementById('regularDonationOptions');

        if (regularDonationCheck && regularDonationOptions) {
            regularDonationCheck.addEventListener('change', function() {
                regularDonationOptions.style.display = this.checked ? 'block' : 'none';
            });
        }
    }

    // Ödeme sonucu durumunu gösteren fonksiyon
    function showPaymentResult(status, orderId, errorCode, errorMessage) {
        let message = '';
        let type = 'info';
        let showSuccessButton = false;

        if (status === 'success' || orderId) {
            message = 'Ödeme işleminiz başarıyla tamamlandı! Sipariş numaranız: ' + (orderId || 'N/A');
            type = 'success';
            showSuccessButton = true;

            // Başarılı ödeme sonrası sepeti temizle
            setTimeout(() => {
                localStorage.removeItem('donationCart');
                localStorage.removeItem('cartTotalAmount');
                document.dispatchEvent(new Event('cartUpdated'));
            }, 2000);

        } else if (status === 'failed' || errorCode) {
            message = 'Ödeme işleminiz başarısız oldu. Hata kodu: ' + (errorCode || 'N/A');
            if (errorMessage) {
                message += ' - ' + errorMessage;
            }
            type = 'error';
        }

        if (message) {
            showNotification(message, type, showSuccessButton, orderId);
        }
    }

    // Bildirim gösterme fonksiyonu
    function showNotification(message, type = 'info', showSuccessButton = false, orderId = '') {
        // Mevcut bildirimleri temizle
        const existingNotifications = document.querySelectorAll('.payment-notification');
        existingNotifications.forEach(notification => notification.remove());

        // Bildirim elementi oluştur
        const notification = document.createElement('div');
        notification.className = 'payment-notification';

        // Tip'e göre stil uygula
        if (type === 'success') {
            notification.style.backgroundColor = '#4CAF50';
            notification.style.borderColor = '#45a049';
        } else if (type === 'error') {
            notification.style.backgroundColor = '#F44336';
            notification.style.borderColor = '#d32f2f';
        } else {
            notification.style.backgroundColor = '#2196F3';
            notification.style.borderColor = '#1976D2';
        }

        // Başarılı ödeme için buton ekle
        let successButton = '';
        if (showSuccessButton && orderId) {
            successButton =
                `<button class="notification-success-btn" onclick="window.location.href='<?= BASE_URL ?>/success?OrderId=${orderId}'">Detayları Gör</button>`;
        }

        // Bildirim içeriği
        notification.innerHTML = `
            <div class="notification-icon">${type === 'success' ? '✓' : type === 'error' ? '⚠️' : 'ℹ️'}</div>
            <div class="notification-content">
                <div class="notification-message">${message}</div>
                ${successButton}
            </div>
            <button class="notification-close" onclick="this.parentElement.remove()">×</button>
        `;

        // Sayfaya ekle
        document.body.appendChild(notification);

        // Animasyon için gecikme
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);

        // Belirli bir süre sonra otomatik kaldır (sadece info tipi için)
        if (type === 'info') {
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 500);
            }, 5000);
        }
    }

    // Ödeme durumu göstergesini güncelleyen fonksiyon
    function updatePaymentStatusIndicator(status, orderId, errorCode, errorMessage) {
        const indicator = document.getElementById('paymentStatusIndicator');
        if (!indicator) return;

        const statusIcon = indicator.querySelector('.status-icon i');
        const statusTitle = indicator.querySelector('.status-title');
        const statusDescription = indicator.querySelector('.status-description');

        if (status === 'success' || orderId) {
            // Başarılı durum
            indicator.style.backgroundColor = '#e8f5e8';
            indicator.style.borderColor = '#4CAF50';
            statusIcon.className = 'fas fa-check-circle';
            statusIcon.style.color = '#4CAF50';
            statusTitle.textContent = 'Ödeme Başarılı';
            statusTitle.style.color = '#2E7D32';
            statusDescription.textContent =
                `İşleminiz başarıyla tamamlandı. Sipariş numaranız: ${orderId || 'N/A'}`;
            statusDescription.style.color = '#424242';
        } else if (status === 'failed' || errorCode) {
            // Başarısız durum
            indicator.style.backgroundColor = '#ffebee';
            indicator.style.borderColor = '#F44336';
            statusIcon.className = 'fas fa-times-circle';
            statusIcon.style.color = '#F44336';
            statusTitle.textContent = 'Ödeme Başarısız';
            statusTitle.style.color = '#C62828';
            statusDescription.textContent = `İşleminiz başarısız oldu. Hata kodu: ${errorCode || 'N/A'}`;
            if (errorMessage) {
                statusDescription.textContent += ` - ${errorMessage}`;
            }
            statusDescription.style.color = '#424242';
        }

        // Göstergiyi göster
        indicator.style.display = 'flex';
    }
});
</script>

<!-- Google reCAPTCHA API -->
<script src="https://www.google.com/recaptcha/api.js?onload=onRecaptchaLoad&render=explicit" async defer></script>
<script>
function onRecaptchaLoad() {
    grecaptcha.render('recaptcha', {
        'sitekey': '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI',
        'callback': 'onRecaptchaSuccess',
        'expired-callback': 'onRecaptchaExpired',
        'theme': 'light',
        'type': 'image',
        'size': 'normal',
        'tabindex': 0
    });
}

function onRecaptchaSuccess(token) {
    // reCAPTCHA başarılı olduğunda yapılacak işlemler
    console.log('reCAPTCHA başarılı:', token);
    
    // Hidden input'a token'ı ekle
    const recaptchaInput = document.getElementById('g-recaptcha-response');
    if (recaptchaInput) {
        recaptchaInput.value = token;
    }
}

function onRecaptchaExpired() {
    // reCAPTCHA süresi dolduğunda yapılacak işlemler
    console.log('reCAPTCHA süresi doldu');
    grecaptcha.reset();
}
</script>

<?php
// Footer'ı include et
require_once __DIR__ . '/../includes/footer.php';
?>