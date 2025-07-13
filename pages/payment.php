<!-- Ödeme Sayfası -->
<?php
// Ödeme sayfası için güvenlik kontrolü
require_once __DIR__ . '/../config/payment_config.php';

// HTTPS kontrolü (geliştirme ortamında devre dışı)
if (PAYMENT_FORCE_HTTPS && (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off')) {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}

// Process-donation dosyasını dahil et
require_once __DIR__ . '/../includes/actions/process-donation.php';

// Header'ı include et
require_once __DIR__ . '/../includes/header.php';

// Sepet ve bağış verilerini oturumdan al
$donationAmount = isset($_SESSION['cart_total']) ? $_SESSION['cart_total'] : 0;
$donationType = isset($_SESSION['donation_type']) ? $_SESSION['donation_type'] : 'Genel Bağış';
$donationId = isset($_SESSION['donation_id']) ? $_SESSION['donation_id'] : 0;

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
                        <div class="card-validation-icon"></div>
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
    // CSRF token kontrolü
    if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            // CSRF token geçersizse işlemi reddet
    error_log("Ödeme işlemi CSRF token hatası!");
    echo "<script>window.location.href = '" . BASE_URL . "/fail?error=security';</script>";
    exit;
    }
    
    // Bağış verilerini al
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
    
    // Debug: Bağış verilerini logla
    if (PAYMENT_DEBUG) {
        error_log("Bağış verileri: " . print_r($donationData, true));
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
        echo "<script>window.location.href = '" . BASE_URL . "/fail?error=database';</script>";
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

.card-validation-icon {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    width: 24px;
    height: 24px;
    background-position: center;
    background-repeat: no-repeat;
    background-size: contain;
}

.card-validation-icon.valid {
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%234CAF50" width="24px" height="24px"><path d="M0 0h24v24H0z" fill="none"/><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>');
}

.card-validation-icon.invalid {
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23F44336" width="24px" height="24px"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>');
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

        // Form doğrulama fonksiyonu
        function validatePaymentForm() {
            const cardNumber = document.getElementById('cardNumber').value.replace(/\s/g, '');
            const cardExpiry = document.getElementById('cardExpiry').value;
            const cardCvv = document.getElementById('cardCvv').value;
            const cardHolderName = document.getElementById('cardHolderName').value;
            const city = document.getElementById('city').value;

            // Kart numarası kontrolü
            if (cardNumber.length < 13 || cardNumber.length > 19) {
                showNotification('Geçersiz kart numarası. Lütfen kart bilgilerinizi kontrol ediniz.', 'error');
                return false;
            }

            // Son kullanma tarihi kontrolü
            if (!cardExpiry || cardExpiry.length !== 5) {
                showNotification('Geçersiz son kullanma tarihi. Lütfen MM/YY formatında giriniz.', 'error');
                return false;
            }

            // CVV kontrolü
            if (cardCvv.length < 3 || cardCvv.length > 4) {
                showNotification('Geçersiz CVV. Lütfen kart arkasındaki 3 haneli kodu giriniz.', 'error');
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

        // Kredi kartı numarası formatı
        const cardInput = document.getElementById('cardNumber');
        if (cardInput) {
            cardInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');

                // 16 haneden fazla girilmesin
                if (value.length > 16) {
                    value = value.slice(0, 16);
                }

                // 4'lü gruplara ayır
                let formattedValue = '';
                for (let i = 0; i < value.length; i++) {
                    if (i > 0 && i % 4 === 0) {
                        formattedValue += ' ';
                    }
                    formattedValue += value[i];
                }

                e.target.value = formattedValue;
            });
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

        // CVV formatı
        const cvvInput = document.getElementById('cardCvv');
        if (cvvInput) {
            cvvInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');

                // 3 haneden fazla girilmesin
                if (value.length > 3) {
                    value = value.slice(0, 3);
                }

                e.target.value = value;
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
</script>

<?php
// Footer'ı include et
require_once __DIR__ . '/../includes/footer.php';
?>