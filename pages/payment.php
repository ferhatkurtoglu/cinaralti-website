<!-- Ödeme Sayfası -->
<?php
// Ödeme sayfası için ek güvenlik kontrolü - Geliştirme ortamında bu kontrolü devre dışı bırakıyoruz
/*
if (!defined('FORCE_HTTPS') || !FORCE_HTTPS) {
    die("Güvenli olmayan bağlantı. Lütfen site yöneticisiyle iletişime geçin.");
}

// HTTPS kontrolü
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}
*/

// Process-donation dosyasını dahil et
require_once __DIR__ . '/../includes/actions/process-donation.php';

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

                    <!-- TC Kimlik / Vergi No -->
                    <div class="form-group">
                        <label>TC Kimlik / Vergi No</label>
                        <input type="text" id="identityTaxNumber" name="identityTaxNumber" class="form-control"
                            placeholder="TC Kimlik veya Vergi No" maxlength="11">
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
        header("Location: " . BASE_URL . "/fail?error=security");
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
        'donation_type' => isset($_POST['donation_type']) ? sanitize_input($_POST['donation_type']) : 'Genel Bağış',
        'donor_type' => isset($_POST['donor_type']) ? sanitize_input($_POST['donor_type']) : 'individual',
        'payment_status' => 'pending', // Başlangıç durumu: beklemede
        'order_number' => $orderNo // Sipariş numarasını ekle
    ];
    
    // Bağış verisini veritabanına kaydet
    $donationId = save_donation($donationData);
    
    // Kayıt başarısızsa hata sayfasına yönlendir
    if (!$donationId) {
        error_log("Bağış kaydetme hatası oluştu!");
        header("Location: " . BASE_URL . "/fail?error=database");
        exit;
    }
    
    // Bağış ID'sini oturuma kaydet (ödeme sonucunu güncellemek için)
    $_SESSION['donation_made_id'] = $donationId;
    
    // Debug modu ayarlarını uygula
    ini_set('display_errors', PAYMENT_DEBUG ? 1 : 0);
    error_reporting(PAYMENT_DEBUG ? E_ALL : 0);
    
    // Ödeme API bilgilerini güvenli bir şekilde al
    $merchantId = defined('PAYMENT_MERCHANT_ID') ? PAYMENT_MERCHANT_ID : '';
    $apiKey = defined('PAYMENT_API_KEY') ? PAYMENT_API_KEY : '';
    
    // Geliştirme ortamında, eğer API bilgileri yoksa test bilgilerini kullan
    if (empty($merchantId) || empty($apiKey)) {
        if (PAYMENT_DEBUG) {
            error_log("Ödeme API bilgileri eksik. Geliştirme için test değerleri kullanılıyor.");
            // Test değerleri (Gerçek değerler değildir, sadece geliştirme amaçlı)
            $merchantId = '123456789'; 
            $apiKey = 'TEST_API_KEY';
        } else {
            error_log("Ödeme API bilgileri eksik!");
            header("Location: " . BASE_URL . "/fail?error=configuration&ErrorMessage=" . urlencode('Ödeme sistemi yapılandırması tamamlanmamış. Lütfen site yöneticisiyle iletişime geçin.'));
            exit;
        }
    }
    
    // JavaScript'ten alınan tutar bilgisini kullan (hidden input'tan gelecek)
    $amount = isset($_POST['amount']) ? (int)$_POST['amount'] : 10000; // Kuruş cinsinden
    $orderNo = isset($_POST['order_no']) ? sanitize_input($_POST['order_no']) : '';
    
    // URL'ler için tam yol kullan
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    $domain = $protocol . '://' . $_SERVER['HTTP_HOST'];
    
    // Tam yolu direkt php dosyalarına yönlendir (index.php üzerinden değil)
    $successUrl = $domain . dirname($_SERVER['SCRIPT_NAME']) . "/success.php";
    $errorUrl = $domain . dirname($_SERVER['SCRIPT_NAME']) . "/fail.php";
    
    try {
        // Geliştirme modunda test için doğrudan success sayfasına yönlendir
        if (PAYMENT_DEBUG) {
            header("Location: " . $successUrl . "?OrderId=TEST_" . $donationId . "&AuthCode=TEST_AUTH_" . rand(100000, 999999));
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
                "customerId" => $donationId,  // Müşteri numarası olarak bağış ID'sini kullanabiliriz
                "userName" => $userName,
                "amount" => (string)$amount,
                "merchantOrderId" => $orderNo,
                "cardNumber" => $cardNumber,
                "currencyCode" => "0949", // TRY için
                "transactionType" => "1",  // Satış
                "identityTaxNumber" => $identityTaxNumber,
                "hashData" => $hashData,
                "installmentCount" => "0",  // Taksitsiz
                "description" => $donationData['donation_type'],
                "insuranceDeferringCount" => "0"
            ]
        ];
        
        // API endpoint
        $apiEndpoint = "https://sanalpos.kuveytturk.com.tr/v1/vpos/nonThreeDPayment";
        
        // cURL ile API isteği gönder
        $ch = curl_init($apiEndpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($apiData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);
        
        // API yanıtını al
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
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
                    
                    header("Location: " . $successUrl . "?OrderId=" . $orderId . "&AuthCode=" . $provisionNumber);
                    exit;
                } else {
                    // İşlem başarısız, hata mesajı ile fail sayfasına yönlendir
                    $responseMessage = $firstResult['responseMessage'] ?? 'İşlem başarısız';
                    header("Location: " . $errorUrl . "?ErrorCode=" . $responseCode . "&ErrorMessage=" . urlencode($responseMessage));
                    exit;
                }
            } else {
                // API yanıtı başarısız
                $errorMessage = isset($responseData['errors']) ? implode(", ", $responseData['errors']) : 'API yanıtı başarısız';
                header("Location: " . $errorUrl . "?error=payment_gateway&ErrorMessage=" . urlencode($errorMessage));
                exit;
            }
        } else {
            // HTTP hatası
            header("Location: " . $errorUrl . "?error=payment_gateway&ErrorMessage=" . urlencode("HTTP Hata Kodu: " . $httpCode));
            exit;
        }
        
    } catch (Exception $e) {
        // Hata durumunda kullanıcıyı fail sayfasına yönlendir
        error_log("Ödeme işleme hatası: " . $e->getMessage());
        header("Location: " . $errorUrl . "?error=payment_gateway&ErrorMessage=" . urlencode($e->getMessage()));
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
            // Form alanlarını kontrol et
            const cardNumber = document.getElementById('cardNumber').value.replace(/\s/g, '');
            const cardExpiry = document.getElementById('cardExpiry').value;
            const cardCvv = document.getElementById('cardCvv').value;
            const identityTaxNumber = document.getElementById('identityTaxNumber').value;

            if (cardNumber.length < 16) {
                e.preventDefault();
                alert('Lütfen geçerli bir kart numarası girin');
                return;
            }

            if (cardExpiry.length < 5) {
                e.preventDefault();
                alert('Lütfen geçerli bir son kullanma tarihi girin');
                return;
            }

            if (cardCvv.length < 3) {
                e.preventDefault();
                alert('Lütfen geçerli bir CVV kodu girin');
                return;
            }

            if (identityTaxNumber.length !== 11) {
                e.preventDefault();
                alert('Lütfen geçerli bir TC Kimlik/Vergi numarası girin');
                return;
            }

            // Yükleme ekranını göster
            if (loadingOverlay) {
                loadingOverlay.style.display = 'flex';
            }
        });

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

        // TC Kimlik/Vergi No formatı
        const identityInput = document.getElementById('identityTaxNumber');
        if (identityInput) {
            identityInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');

                // 11 haneden fazla girilmesin
                if (value.length > 11) {
                    value = value.slice(0, 11);
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