<!-- Ödeme Sayfası -->
<?php
// Ödeme sayfası için ek güvenlik kontrolü
if (!defined('FORCE_HTTPS') || !FORCE_HTTPS) {
    die("Güvenli olmayan bağlantı. Lütfen site yöneticisiyle iletişime geçin.");
}

// HTTPS kontrolü
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}
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
                <span class="payment-amount">₺12,00</span>
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
                <div class="card-label">KART BİLGİSİ</div>

                <form id="paymentForm" method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
                    <input type="hidden" id="amount" name="amount" value="10000">
                    <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                    
                    <div class="card-number-container">
                        <input type="text" id="cardNumber" class="card-number-input" placeholder="Kart Numarası"
                            maxlength="19" autocomplete="cc-number">
                        <div class="card-validation-icon"></div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Son Kul. Tarihi</label>
                            <input type="text" class="form-control" placeholder="MM/YY" autocomplete="cc-exp">
                        </div>
                        <div class="form-group col-md-6">
                            <label>CVV</label>
                            <input type="text" class="form-control" placeholder="CVV" autocomplete="cc-csc">
                        </div>
                    </div>

                    <div class="form-group message-group">
                        <label class="small-label">Çınaraltına Mesajınız</label>
                        <textarea class="form-control message-input" rows="2" placeholder="İsteğe bağlı"></textarea>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="regularDonation">
                        <label class="form-check-label" for="regularDonation">Düzenli bağış yapmak istiyorum</label>
                    </div>

                    <div id="regularDonationOptions" style="display: none;">
                        <div class="regular-donation-details">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tekrar süresi</label>
                                        <select class="form-control">
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
                                        <select class="form-control">
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

                    <button type="submit" class="btn-pay" id="payButton" disabled>ÖDEME YAP</button>
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
    
    // Debug modu ayarlarını uygula
    ini_set('display_errors', PAYMENT_DEBUG ? 1 : 0);
    error_reporting(PAYMENT_DEBUG ? E_ALL : 0);
    
    // Ödeme API bilgilerini güvenli bir şekilde al
    $merchantId = defined('PAYMENT_MERCHANT_ID') ? PAYMENT_MERCHANT_ID : '';
    $apiKey = defined('PAYMENT_API_KEY') ? PAYMENT_API_KEY : '';
    
    // API bilgileri yoksa hata ver
    if (empty($merchantId) || empty($apiKey)) {
        error_log("Ödeme API bilgileri eksik!");
        header("Location: " . BASE_URL . "/fail?error=configuration");
        exit;
    }
    
    // JavaScript'ten alınan tutar bilgisini kullan (hidden input'tan gelecek)
    $amount = isset($_POST['amount']) ? (int)$_POST['amount'] : 10000; // Kuruş cinsinden
    
    // URL'ler için tam yol kullan (HTTPS zorunlu)
    $domain = 'https://' . $_SERVER['HTTP_HOST'];
    
    $successUrl = $domain . BASE_URL . "/success";
    $errorUrl = $domain . BASE_URL . "/fail";
    $transactionType = "Sale";

    // Güvenlik için HASH (SHA256 ile imzalanmış)
    $hashStr = $merchantId . $amount . $successUrl . $errorUrl . $transactionType . $apiKey;
    $hash = base64_encode(hash('sha256', $hashStr, true));

    // Kuveyt Türk Kolay Kurulum URL'si
    $url = "https://sanalpos.kuveytturk.com.tr/PaymentGateway/KolayKurulum.aspx";
    $url .= "?MerchantId=" . urlencode($merchantId);
    $url .= "&Amount=" . urlencode($amount);
    $url .= "&SuccessUrl=" . urlencode($successUrl);
    $url .= "&ErrorUrl=" . urlencode($errorUrl);
    $url .= "&TransactionType=" . urlencode($transactionType);
    $url .= "&Hash=" . urlencode($hash);

    // Hata ayıklama için günlük kaydı
    error_log("Ödeme işlemi başlatıldı: " . sanitize_input(substr($url, 0, 100)) . "...");
    
    // Debug modunda ise yönlendirme bilgilerini göster
    if (PAYMENT_DEBUG) {
        echo "<div style='background: #f8f9fa; padding: 20px; margin: 20px; border-radius: 5px; border: 1px solid #ddd;'>";
        echo "<h3>Ödeme Yönlendirme URL'si (Test Modu)</h3>";
        echo "<p>Yönlendirme bekleyin...</p>";
        echo "<code>" . htmlspecialchars(substr($url, 0, 100)) . "...</code>";
        echo "</div>";
        
        // 5 saniye bekledikten sonra yönlendirme
        echo "<script>setTimeout(function() { window.location.href = '" . $url . "'; }, 5000);</script>";
    } else {
        // Üretim modunda doğrudan yönlendir
        header("Location: " . $url);
    }
    exit;
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
        paymentForm.addEventListener('submit', function(e) {
            // Formun varsayılan davranışını engelle
            e.preventDefault();

            // Yükleme ekranını göster
            if (loadingOverlay) {
                loadingOverlay.style.display = 'flex';
            }

            // Form gönderimini geciktir (tarayıcının yükleme göstergesini göstermesi için)
            setTimeout(() => {
                // Formu manuel olarak gönder
                this.submit();
            }, 500);
        });
    }

    // Düzenli bağış seçeneği
    const regularDonationCheck = document.getElementById('regularDonation');
    const regularDonationOptions = document.getElementById('regularDonationOptions');

    if (regularDonationCheck) {
        regularDonationCheck.addEventListener('change', function() {
            regularDonationOptions.style.display = this.checked ? 'block' : 'none';
        });
    }

    // Kredi kartı numarası formatı ve doğrulama
    const cardInput = document.getElementById('cardNumber');
    const validationIcon = document.querySelector('.card-validation-icon');
    const payButton = document.getElementById('payButton');

    // Sayfa yüklendiğinde ödeme butonunu aktif et (recaptcha kontrolünü kaldır)
    payButton.disabled = false;

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

            // Luhn Algoritması ile kart numarası doğrulama
            if (value.length === 16) {
                if (isValidCreditCard(value)) {
                    validationIcon.classList.add('valid');
                    validationIcon.classList.remove('invalid');
                    payButton.disabled = false; // Kart geçerliyse butonu aktif et
                } else {
                    validationIcon.classList.add('invalid');
                    validationIcon.classList.remove('valid');
                    payButton.disabled = true; // Kart geçersizse butonu devre dışı bırak
                }
            } else {
                validationIcon.classList.remove('valid');
                validationIcon.classList.remove('invalid');
                payButton.disabled = false; // Kart doğrulaması yapılmadığında buton aktif olsun
            }
        });

        // Odaklandığında tüm metni seç
        cardInput.addEventListener('focus', function() {
            setTimeout(() => this.select(), 100);
        });
    }

    // Son kullanma tarihi formatı
    const expiryInput = document.querySelector('input[placeholder="MM/YY"]');
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

    // Kredi kartı numarası doğrulama (Luhn algoritması)
    function isValidCreditCard(number) {
        // Sayıların toplamını hesapla
        let sum = 0;
        let shouldDouble = false;

        // Sağdan sola doğru sayıları işle
        for (let i = number.length - 1; i >= 0; i--) {
            let digit = parseInt(number.charAt(i));

            if (shouldDouble) {
                digit *= 2;
                if (digit > 9) {
                    digit -= 9;
                }
            }

            sum += digit;
            shouldDouble = !shouldDouble;
        }

        // Toplam 10'a tam bölünüyorsa kart numarası geçerlidir
        return (sum % 10) === 0;
    }

    // CVV formatı
    const cvvInput = document.querySelector('input[placeholder="CVV"]');
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