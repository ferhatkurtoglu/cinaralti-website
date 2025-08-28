<!-- Cart -->
<?php
// Güvenli session başlatma
require_once __DIR__ . '/../includes/functions.php';
secure_session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/payment_config.php';

// DEBUG_MODE'da rate limit verilerini temizle
if (defined('DEBUG_MODE') && DEBUG_MODE) {
    clear_rate_limits();
}

// Rate limiting kontrolü (DEBUG_MODE'da devre dışı)
if (PAYMENT_RATE_LIMIT_ENABLED && !check_rate_limit('cart_access', 10, 300)) {
    log_payment_security_event('rate_limit_exceeded', ['action' => 'cart_access']);
    // DEBUG_MODE'da warning göster ama engelleme
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
        error_log('WARNING: Cart rate limit exceeded but allowing due to DEBUG_MODE');
    } else {
        header('Location: ' . BASE_URL . '/fail?error=rate_limit');
        exit();
    }
}

// Cart API endpoint handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $action = $_POST['action'] ?? '';
    
    // CSRF protection
    if (PAYMENT_CSRF_PROTECTION && (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token']))) {
        echo json_encode(['success' => false, 'error' => 'Güvenlik doğrulaması başarısız']);
        exit;
    }
    
    switch ($action) {
        case 'add_item':
            $item = $_POST['item'] ?? [];
            $result = add_to_server_cart($item);
            echo json_encode($result);
            break;
            
        case 'remove_item':
            $item_id = $_POST['item_id'] ?? '';
            $result = remove_from_server_cart($item_id);
            echo json_encode(['success' => $result]);
            break;
            
        case 'clear_cart':
            $result = clear_server_cart();
            echo json_encode(['success' => $result]);
            break;
            
        case 'get_cart':
            $cart = get_server_cart();
            $total = calculate_cart_total();
            echo json_encode(['success' => true, 'cart' => $cart, 'total' => $total]);
            break;
            
        case 'validate_cart':
            $validation = validate_cart_for_checkout();
            echo json_encode($validation);
            break;
            
        default:
            echo json_encode(['success' => false, 'error' => 'Geçersiz işlem']);
    }
    exit;
}

require_once __DIR__ . '/../includes/header.php';
?>

<!-- Cart -->
<div class="cart-container">
    <div class="cart-header">
        <div class="cart-title">
            <i class="fas fa-shopping-cart"></i>
            <h1>Bağış Sepetim</h1>
        </div>
        <button class="clear-cart">
            <i class="fas fa-trash"></i>
            Sepeti Temizle
        </button>
    </div>

    <div class="cart-items">
        <!-- Sepet öğeleri JavaScript ile doldurulacak -->
    </div>

    <div class="cart-footer">
        <div class="cart-total">
            <span>Toplam Bağış:</span>
            <span class="total-amount">₺0,00 (TRY)</span>
        </div>
        <button class="complete-donation">BAĞIŞI TAMAMLA</button>
    </div>
</div>

<!-- Bağış Düzenleme Modalı -->
<div class="modal fade" id="editDonationModal" tabindex="-1" aria-labelledby="editDonationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pt-0">
                <div class="donation-category mb-1">Acil Yardım</div>
                <h1 class="donation-title mb-4">Kriz Bölgeleri Acil Yardım</h1>

                <div class="donation-cost-label mb-2">donation-cost</div>
                <div class="donation-amount mb-4">
                    <input type="text" class="donation-price-input" id="donationPriceInput" value="₺120">
                </div>

                <div class="donation-type-options mb-4">
                    <label class="donation-type-option me-5">
                        <input type="radio" name="donationType" value="standard" class="donation-type-input" checked>
                        <span class="donation-type-circle"></span>
                        <span class="donation-type-text">Standart Bağış</span>
                    </label>
                    <label class="donation-type-option">
                        <input type="radio" name="donationType" value="zekat" class="donation-type-input">
                        <span class="donation-type-circle"></span>
                        <span class="donation-type-text">Zekat Bağışı</span>
                    </label>
                </div>

                <div class="donation-type-buttons mb-4">
                    <div class="donation-button-group">
                        <button type="button" class="donation-button active">Bireysel</button>
                        <button type="button" class="donation-button">Grup</button>
                        <button type="button" class="donation-button">Kurumsal</button>
                    </div>
                </div>

                <div class="donation-input-fields mb-4">
                    <div class="donation-input-field mb-3">
                        <span class="input-icon">
                            <i class="far fa-user text-secondary"></i>
                        </span>
                        <input type="text" id="donorName" class="form-control" value="Ferhat Kurtoğlu"
                            placeholder="Ad Soyad">
                    </div>

                    <div class="donation-input-field mb-3">
                        <div class="phone-input-container">
                            <div class="phone-prefix country-select">
                                <div class="country-select-toggle">
                                    <span class="flag-icon">🇹🇷</span>
                                    <span class="prefix-code">+90</span>
                                    <span class="prefix-arrow">▼</span>
                                </div>
                                <div class="country-select-dropdown">
                                    <div class="country-search">
                                        <input type="text" placeholder="Ara" class="country-search-input">
                                    </div>
                                    <div class="country-list">
                                        <!-- Ülke listesi JavaScript ile doldurulacak -->
                                    </div>
                                </div>
                            </div>
                            <input type="tel" id="donorPhone" class="form-control" value="555 123 4567"
                                placeholder="Telefon Numarası">
                        </div>
                    </div>

                    <div class="donation-input-field">
                        <span class="input-icon">
                            <i class="far fa-envelope text-secondary"></i>
                        </span>
                        <input type="email" id="donorEmail" class="form-control" value="ferhatkurtoglu571@gmail.com"
                            placeholder="E-posta Adresi">
                    </div>
                </div>

                <button type="button" class="btn-update" id="updateDonationBtn">Güncelle</button>
            </div>
        </div>
    </div>
</div>

<style>
.cart-container {
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
    padding-top: 120px;
}

.cart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 20px;
}

.cart-title {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #28a745;
}

.cart-title h1 {
    font-size: 24px;
    margin: 0;
    font-weight: 600;
}

.clear-cart {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #6c757d;
    background: none;
    border: none;
    cursor: pointer;
    font-size: 16px;
    padding: 8px 16px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.clear-cart:hover {
    background: #f8f9fa;
}

.cart-items {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.cart-item {
    display: flex;
    padding: 24px;
    border-bottom: 1px solid #e9ecef;
    align-items: center;
}

.item-image {
    width: 120px;
    height: 90px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
    margin-right: 20px;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.item-details {
    display: flex;
    flex: 1;
    align-items: center;
    justify-content: space-between;
}

.item-name {
    flex: 1;
}

.item-name h3 {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin: 0 0 5px 0;
}

.item-name p {
    font-size: 14px;
    color: #777;
    margin: 0;
}

.donor-info {
    flex: 1;
    padding: 0 20px;
}

.donor-name {
    font-weight: 500;
    margin-bottom: 5px;
}

.donor-email {
    font-size: 14px;
    color: #777;
}

.quantity-price {
    display: flex;
    align-items: center;
    gap: 15px;
}

.quantity-controls {
    display: flex;
    align-items: center;
    border: 1px solid #dee2e6;
    border-radius: 4px;
}

.quantity-btn {
    background: none;
    border: none;
    font-size: 20px;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #333;
}

.quantity-input {
    width: 40px;
    text-align: center;
    border: none;
    font-size: 16px;
    color: #333;
    appearance: textfield;
    -moz-appearance: textfield;
    background-color: #fff;
}

.quantity-input::-webkit-inner-spin-button,
.quantity-input::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.item-price {
    font-size: 20px;
    font-weight: 600;
    color: #333;
    min-width: 100px;
    text-align: right;
}

.item-actions {
    position: relative;
}

.action-more {
    background: none;
    border: none;
    color: #999;
    cursor: pointer;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.action-more:hover {
    background-color: #f8f9fa;
}

.action-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    background-color: #fff;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    z-index: 100;
    min-width: 180px;
    display: none;
}

.action-dropdown.show {
    display: block;
}

.dropdown-item {
    padding: 12px 15px;
    cursor: pointer;
    display: flex;
    align-items: center;
    color: #333;
    transition: background-color 0.2s;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

.dropdown-item i {
    margin-right: 10px;
    width: 20px;
    color: #777;
}

.cart-footer {
    margin-top: 30px;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 30px;
    flex-wrap: wrap;
}

.cart-total {
    font-size: 18px;
    color: #212529;
}

.total-amount {
    font-weight: 600;
    margin-left: 8px;
}

.complete-donation {
    background: #28a745;
    color: white;
    border: none;
    padding: 16px 32px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s;
}

.complete-donation:hover {
    background: #218838;
}

/* Boş sepet durumu için stil */
.empty-cart-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px 20px;
    text-align: center;
}

.empty-cart-icon {
    width: 180px;
    height: 180px;
    border-radius: 50%;
    background-color: #e8f5ec;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 30px;
}

.empty-cart-icon i {
    font-size: 70px;
    color: #4CAF50;
}

.empty-cart-title {
    font-size: 24px;
    font-weight: 700;
    color: #212529;
    margin-bottom: 15px;
}

.empty-cart-text {
    font-size: 16px;
    color: #6c757d;
    max-width: 500px;
    margin-bottom: 30px;
    line-height: 1.5;
}

.empty-cart-button {
    background-color: #4CAF50;
    color: white;
    font-size: 18px;
    font-weight: 600;
    padding: 14px 32px;
    border-radius: 8px;
    text-decoration: none;
    transition: background-color 0.3s;
}

.empty-cart-button:hover {
    background-color: #3d9140;
    text-decoration: none;
    color: white;
}

@media (max-width: 768px) {
    .cart-container {
        padding: 16px;
        padding-top: 120px;
        margin-top: 30px;
    }

    .cart-header {
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }

    .cart-title {
        font-size: 14px;
    }

    .cart-title h1 {
        font-size: 18px;
    }

    .clear-cart {
        font-size: 14px;
        padding: 6px 12px;
    }

    .item-details {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .item-name,
    .donor-info,
    .quantity-price {
        width: 100%;
    }

    .donor-info {
        padding: 0;
    }

    .quantity-price {
        justify-content: space-between;
    }

    .cart-item {
        flex-direction: column;
        align-items: flex-start;
    }

    .item-image {
        width: 100%;
        height: 150px;
        margin-right: 0;
        margin-bottom: 15px;
    }
}

/* Düzenleme Modalı Yeni Stiller */
.modal-content {
    border-radius: 20px;
    border: none;
    overflow: hidden;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.modal-header {
    padding: 20px;
}

.btn-close {
    opacity: 0.7;
}

.donation-category {
    font-size: 1.5rem;
    font-weight: 400;
    color: #212529;
}

.donation-title {
    font-size: 2rem;
    font-weight: 700;
    color: #212529;
    line-height: 1.1;
}

.donation-cost-label {
    font-size: 1rem;
    color: #666;
}

.donation-price-input {
    font-size: 3.5rem;
    font-weight: 700;
    color: #212529;
    width: 100%;
    border: none;
    padding: 0;
    margin: 0;
    outline: none;
    background: transparent;
}

.donation-price-input:focus {
    outline: none;
    border-bottom: 2px solid #43a047;
}

.phone-input-container {
    display: flex;
    align-items: center;
    position: relative;
    width: 100%;
    border-radius: 8px;
    overflow: hidden;
    background-color: #fff;
}

.phone-prefix {
    position: relative;
    display: flex;
    align-items: center;
    padding: 0 12px;
    height: 100%;
    border-right: 1px solid #e0e0e0;
    background-color: #f9f9f9;
    z-index: 2;
    cursor: pointer;
    min-width: 90px;
}

.country-select-toggle {
    display: flex;
    align-items: center;
    gap: 5px;
    height: 40px;
}

.flag-icon {
    margin-right: 4px;
}

.prefix-code {
    font-weight: 500;
    margin-right: 4px;
}

.prefix-arrow {
    font-size: 10px;
    color: #757575;
}

.donation-input-field .phone-input-container .form-control {
    border: none;
    padding: 15px 15px;
    height: 50px;
    width: 100%;
    font-size: 1rem;
    box-sizing: border-box;
}

.country-select-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    width: 300px;
    max-height: 300px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    display: none;
    overflow: hidden;
    border: 1px solid #dee2e6;
}

.country-select.active .country-select-dropdown {
    display: block;
}

.country-search {
    padding: 12px;
    border-bottom: 1px solid #dee2e6;
}

.country-search-input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    font-size: 14px;
}

.country-list {
    max-height: 235px;
    overflow-y: auto;
}

.country-item {
    display: flex;
    align-items: center;
    padding: 10px 12px;
    cursor: pointer;
    transition: background-color 0.2s;
}

.country-item:hover,
.country-item.active {
    background-color: #f5f5f5;
}

.country-flag {
    margin-right: 8px;
    font-size: 18px;
}

.country-name {
    flex: 1;
    font-size: 14px;
}

.country-code {
    color: #6c757d;
    font-size: 14px;
}

.donation-input-field input[type="tel"] {
    padding-left: 100px;
    width: 100%;
}

.donation-type-options {
    display: flex;
}

.donation-type-option {
    display: flex;
    align-items: center;
    cursor: pointer;
}

.donation-type-input {
    display: none;
}

.donation-type-circle {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    border: 2px solid #43a047;
    margin-right: 10px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.donation-type-input:checked+.donation-type-circle::after {
    content: '';
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: #43a047;
    position: absolute;
}

.donation-type-text {
    font-size: 1.1rem;
    color: #212529;
}

.donation-type-buttons {
    margin-top: 20px;
}

.donation-button-group {
    display: flex;
    background-color: #f5f5f5;
    border-radius: 50px;
    padding: 4px;
}

.donation-button {
    flex: 1;
    border: none;
    background: transparent;
    padding: 10px 20px;
    border-radius: 50px;
    font-size: 1rem;
    color: #212529;
    transition: all 0.3s ease;
}

.donation-button.active {
    background-color: white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.donation-input-fields {
    margin-top: 20px;
}

.donation-input-field {
    position: relative;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
}

.input-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #757575;
    font-size: 1.2rem;
}

.donation-input-field .form-control {
    border: none;
    padding: 15px 15px 15px 45px;
    font-size: 1rem;
    height: auto;
    width: 100%;
    box-sizing: border-box;
}

.donation-input-field .form-control:focus {
    box-shadow: none;
    border-color: #43a047;
}

.btn-update {
    width: 100%;
    background-color: #43a047;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 15px;
    font-size: 1.1rem;
    font-weight: 500;
    margin-top: 20px;
    transition: background-color 0.3s;
}

.btn-update:hover {
    background-color: #2e7d32;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sepetteki bağışları göster
    loadCartItems();

    // Sepeti temizle
    document.querySelector('.clear-cart').addEventListener('click', function() {
        if (confirm('Sepetinizi temizlemek istediğinize emin misiniz?')) {
            // LocalStorage'dan sepeti temizle
            localStorage.removeItem('donationCart');
            localStorage.removeItem('cartTotalAmount');

            // Sepet badge'ini güncellemek için olay tetikle
            document.dispatchEvent(new Event('cartUpdated'));

            // Sepeti güncelle
            loadCartItems();
        }
    });

    // Bağışı tamamla butonuna tıklandığında ödeme sayfasına yönlendir
    document.querySelector('.complete-donation').addEventListener('click', function() {
        // Sepet boşsa işlem yapma
        if (document.querySelectorAll('.cart-item').length === 0) {
            alert('Sepetiniz boş. Lütfen önce bağış ekleyin.');
            return;
        }

        // Sepet bilgilerini session'a kaydet
        const cart = JSON.parse(localStorage.getItem('donationCart')) || [];
        if (cart.length === 0) {
            alert('Sepetiniz boş. Lütfen önce bağış ekleyin.');
            return;
        }

        // İlk bağıştan donor bilgilerini al (tüm bağışlar aynı kişiden olmalı)
        const firstDonation = cart[0];

        // Total amount'ı localStorage'dan al ve düzelt
        const storedTotal = localStorage.getItem('cartTotalAmount') || '₺0,00';
        console.log('Stored total from localStorage:', storedTotal);

        // ₺12.345,00 formatından 12345.00 formatına çevir
        let totalAmount = 0;
        try {
            // Önce ₺ sembolünü ve boşlukları kaldır
            let cleanAmount = storedTotal.replace(/[₺\s]/g, '');
            console.log('After removing ₺ and spaces:', cleanAmount);

            // Türk formatındaki noktaları kaldır (1.000 -> 1000) ve virgülü noktaya çevir (,00 -> .00)
            if (cleanAmount.includes(',')) {
                // Son virgülden önceki noktaları kaldır
                const parts = cleanAmount.split(',');
                const integerPart = parts[0].replace(/\./g, ''); // Binlik ayırıcı noktaları kaldır
                const decimalPart = parts[1] || '00';
                cleanAmount = integerPart + '.' + decimalPart;
            }

            console.log('Final clean amount:', cleanAmount);
            totalAmount = parseFloat(cleanAmount) || 0;
        } catch (e) {
            console.error('Error parsing total amount:', e);
            totalAmount = 0;
        }

        console.log('Calculated total amount:', totalAmount);

        /*    // Donor bilgilerini kontrol et
           if (!firstDonation.donorName || !firstDonation.donorEmail || !firstDonation.donorPhone) {
               alert('Bağış yapabilmek için tüm bilgilerinizi girmelisiniz. Lütfen bağışlarınızı düzenleyerek eksik bilgileri tamamlayın.');
               return;
           } */

        // Session'a kaydetmek için fetch ile backend'e gönder
        // XAMPP için path düzeltmesi
        const requestUrl = '/cinaralti-website/includes/actions/prepare-payment.php';
        console.log('Fetch URL:', requestUrl);
        console.log('Cart data:', cart);
        console.log('Total amount:', totalAmount);

        fetch(requestUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    cart: cart,
                    totalAmount: totalAmount,
                    timestamp: Date.now()
                })
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);

                if (!response.ok) {
                    throw new Error(`HTTP Error: ${response.status} ${response.statusText}`);
                }

                return response.text(); // Önce text olarak al
            })
            .then(responseText => {
                console.log('Response text:', responseText);

                try {
                    const data = JSON.parse(responseText);
                    console.log('Parsed data:', data);

                    if (data.success) {
                        // Başarı mesajı göster
                        console.log('Ödeme hazırlığı tamamlandı, yönlendiriliyor...');
                        window.location.href = '<?= BASE_URL ?>/payment';
                    } else {
                        // Hata mesajını daha kullanıcı dostu şekilde göster
                        const errorMsg = data.error || 'Bilinmeyen hata';
                        console.error('Ödeme hazırlığı hatası:', errorMsg);

                        // Kullanıcıya daha detaylı mesaj ver
                        if (errorMsg.includes('cart') || errorMsg.includes('sepet')) {
                            alert(
                                'Sepetinizde sorun var. Lütfen sepetinizi kontrol edip tekrar deneyin.');
                        } else if (errorMsg.includes('totalAmount') || errorMsg.includes('tutar')) {
                            alert(
                                'Bağış tutarında sorun var. Lütfen tutarları kontrol edip tekrar deneyin.');
                        } else {
                            alert('Ödeme hazırlığı sırasında hata oluştu: ' + errorMsg +
                                '\n\nLütfen sayfayı yenileyip tekrar deneyin.');
                        }
                    }
                } catch (parseError) {
                    console.error('JSON Parse Error:', parseError);
                    console.error('Raw response:', responseText);
                    alert('Sunucu yanıtı beklenmedik formatta. Lütfen sayfayı yenileyip tekrar deneyin.\n\nHata detayı: ' +
                        parseError.message);
                }
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                console.error('Request details:', {
                    url: requestUrl,
                    cart: cart,
                    totalAmount: totalAmount
                });

                // Ağ hatası mı yoksa server hatası mı daha iyi belirle
                if (error.message.includes('Failed to fetch') || error.message.includes(
                    'Network')) {
                    alert(
                        'İnternet bağlantı problemi. Lütfen internet bağlantınızı kontrol edip tekrar deneyin.');
                } else if (error.message.includes('HTTP Error: 404')) {
                    alert(
                        'Ödeme sistemine ulaşılamıyor. Lütfen site yöneticisiyle iletişime geçin.');
                } else if (error.message.includes('HTTP Error: 500')) {
                    alert('Sunucu hatası oluştu. Lütfen birkaç dakika sonra tekrar deneyin.');
                } else {
                    alert('Bağlantı hatası oluştu: ' + error.message +
                        '\n\nLütfen sayfayı yenileyip tekrar deneyin.');
                }
            });
    });

    // Sepet öğelerini localStorage'dan yükle
    function loadCartItems() {
        const cartItemsContainer = document.querySelector('.cart-items');
        const cart = JSON.parse(localStorage.getItem('donationCart')) || [];

        if (cart.length === 0) {
            showEmptyCart();
            return;
        }

        let cartItemsHTML = '';

        cart.forEach((item, index) => {
            cartItemsHTML += `
                    <div class="cart-item" data-index="${index}">
                        <div class="item-image">
                            <img src="${item.image}" alt="Bağış Görseli">
                        </div>
                        <div class="item-details">
                            <div class="item-name">
                                <h3>${item.title}</h3>
                                <p>${item.donationType}, ${item.donorType}</p>
                            </div>
                            <div class="donor-info">
                                <div class="donor-name">${item.donorName || 'İsimsiz Bağışçı'}</div>
                                <div class="donor-email">${item.donorEmail || ''}</div>
                            </div>
                            <div class="quantity-price">
                                <div class="quantity-controls">
                                    <button class="quantity-btn minus">−</button>
                                    <input type="number" value="1" min="1" class="quantity-input">
                                    <button class="quantity-btn plus">+</button>
                                </div>
                                <div class="item-price">${item.amount}</div>
                                <div class="item-actions">
                                    <button class="action-more">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div class="action-dropdown">
                                        <div class="dropdown-item" data-action="duplicate">
                                            <i class="fas fa-plus"></i> Çoğalt
                                        </div>
                                        <div class="dropdown-item" data-action="edit">
                                            <i class="fas fa-pencil-alt"></i> Düzenle
                                        </div>
                                        <div class="dropdown-item" data-action="remove">
                                            <i class="fas fa-trash"></i> Kaldır
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
        });

        cartItemsContainer.innerHTML = cartItemsHTML;

        // Toplam tutarı göster
        updateCartSummary();

        // Sepet öğelerine event listener'ları ekle
        attachCartEventListeners();
    }

    // Event listener'ları ekle
    function attachCartEventListeners() {
        // Action butonları
        const actionButtons = document.querySelectorAll('.action-more');
        actionButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                const dropdown = this.nextElementSibling;

                // Diğer açık menüleri kapat
                document.querySelectorAll('.action-dropdown').forEach(d => {
                    if (d !== dropdown) {
                        d.classList.remove('show');
                    }
                });

                // Menüyü aç/kapat
                dropdown.classList.toggle('show');
            });
        });

        // Dropdown işlemleri
        const dropdownItems = document.querySelectorAll('.dropdown-item');
        dropdownItems.forEach(item => {
            item.addEventListener('click', function() {
                const action = this.getAttribute('data-action');
                const cartItem = this.closest('.cart-item');
                const itemIndex = cartItem.getAttribute('data-index');

                switch (action) {
                    case 'duplicate':
                        duplicateCartItem(itemIndex);
                        break;

                    case 'edit':
                        openEditModal(cartItem);
                        break;

                    case 'remove':
                        removeCartItem(itemIndex);
                        break;
                }

                // Menüyü kapat
                this.closest('.action-dropdown').classList.remove('show');
            });
        });

        // Miktar kontrolleri
        const quantityControls = document.querySelectorAll('.quantity-controls');
        quantityControls.forEach(control => {
            const input = control.querySelector('.quantity-input');
            const minusBtn = control.querySelector('.minus');
            const plusBtn = control.querySelector('.plus');

            minusBtn.addEventListener('click', () => {
                let value = parseInt(input.value);
                if (value > 1) {
                    input.value = value - 1;
                    updateItemQuantity(input.closest('.cart-item'));
                }
            });

            plusBtn.addEventListener('click', () => {
                let value = parseInt(input.value);
                input.value = value + 1;
                updateItemQuantity(input.closest('.cart-item'));
            });

            input.addEventListener('change', () => {
                if (input.value < 1) input.value = 1;
                updateItemQuantity(input.closest('.cart-item'));
            });
        });

        // Dışarı tıklanınca menüyü kapat
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.item-actions')) {
                document.querySelectorAll('.action-dropdown').forEach(dropdown => {
                    dropdown.classList.remove('show');
                });
            }
        });
    }

    // Sepet öğesini çoğalt
    function duplicateCartItem(index) {
        const cart = JSON.parse(localStorage.getItem('donationCart')) || [];
        if (index >= 0 && index < cart.length) {
            const itemToDuplicate = cart[index];
            cart.push({
                ...itemToDuplicate
            });
            localStorage.setItem('donationCart', JSON.stringify(cart));

            // Sepet badge'ini güncellemek için olay tetikle
            document.dispatchEvent(new Event('cartUpdated'));

            loadCartItems();
        }
    }

    // Sepet öğesini kaldır
    function removeCartItem(index) {
        const cart = JSON.parse(localStorage.getItem('donationCart')) || [];
        if (index >= 0 && index < cart.length) {
            cart.splice(index, 1);
            localStorage.setItem('donationCart', JSON.stringify(cart));

            // Sepet badge'ini güncellemek için olay tetikle
            document.dispatchEvent(new Event('cartUpdated'));

            loadCartItems();
        }
    }

    // Miktar değişince öğe fiyatını güncelle
    function updateItemQuantity(cartItem) {
        const index = cartItem.getAttribute('data-index');
        const quantity = parseInt(cartItem.querySelector('.quantity-input').value);
        const cart = JSON.parse(localStorage.getItem('donationCart')) || [];

        if (index >= 0 && index < cart.length) {
            // Mevcut tutardan rakamsal değeri al
            const currentAmount = cart[index].amount;
            const baseAmount = parseInt(currentAmount.replace(/[^\d]/g, ''));

            // Yeni tutarı hesapla
            const newAmount = baseAmount * quantity;

            // Fiyatı güncelle (gösterimi)
            cartItem.querySelector('.item-price').textContent = `₺${newAmount.toLocaleString('tr-TR')},00`;

            // Sepet özeti güncelle
            updateCartSummary();
        }
    }

    // Toplam tutarı güncelle
    function updateCartSummary() {
        let total = 0;

        document.querySelectorAll('.cart-item').forEach(item => {
            const priceText = item.querySelector('.item-price').textContent;
            const quantity = parseInt(item.querySelector('.quantity-input').value);
            const priceValue = parseFloat(priceText.replace(/[^\d,]/g, '').replace(',', '.'));
            total += priceValue;
        });

        // Toplam tutarı göster
        const formattedTotal = `₺${total.toLocaleString('tr-TR')},00`;
        document.querySelector('.total-amount').textContent = `${formattedTotal} (TRY)`;

        // LocalStorage'a kaydet
        localStorage.setItem('cartTotalAmount', formattedTotal);
    }

    // Boş sepet göster
    function showEmptyCart() {
        document.querySelector('.cart-items').innerHTML = `
                <div class="empty-cart-container">
                    <div class="empty-cart-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h2 class="empty-cart-title">Sepetiniz boş</h2>
                    <p class="empty-cart-text">Sepetinize bir bağış ekleyerek, proje ve hizmetlerimize katkı sağlayabilirsiniz.</p>
                    <a href="<?= BASE_URL ?>/donate" class="empty-cart-button">Bağış Yapmaya Başla</a>
                </div>
            `;
        document.querySelector('.cart-footer').style.display = 'none';
    }

    // Düzenleme modalını açma fonksiyonu
    function openEditModal(cartItem) {
        // Bağış bilgilerini al
        const index = cartItem.getAttribute('data-index');
        const cart = JSON.parse(localStorage.getItem('donationCart')) || [];
        const item = cart[index];

        if (!item) return;

        // Modal içeriğini doldur
        document.querySelector('.donation-title').innerText = item.title;
        document.querySelector('.donation-category').innerText = item.donationType.split(',')[0];

        // Fiyatı sadece tam sayı olarak göster
        const priceValue = parseInt(item.amount.replace(/[^\d]/g, ''));
        document.getElementById('donationPriceInput').value = '₺' + priceValue;

        document.getElementById('donorName').value = item.donorName || '';
        document.getElementById('donorEmail').value = item.donorEmail || '';
        document.getElementById('donorPhone').value = item.donorPhone || '';

        // Bağış tipini seç
        if (item.donationType.toLowerCase().includes('zekat')) {
            document.querySelector('input[name="donationType"][value="zekat"]').checked = true;
        } else {
            document.querySelector('input[name="donationType"][value="standard"]').checked = true;
        }

        // Modalı aç
        const editModal = new bootstrap.Modal(document.getElementById('editDonationModal'));
        editModal.show();

        // Güncelle butonuna tıklama
        document.getElementById('updateDonationBtn').addEventListener('click', function() {
            // Yeni değerleri al
            const newTitle = document.querySelector('.donation-title').innerText;
            const newType = document.querySelector('input[name="donationType"]:checked').value ===
                'zekat' ? 'Zekat Bağışı' : 'Standart Bağış';
            const newDonorName = document.getElementById('donorName').value;
            const newDonorEmail = document.getElementById('donorEmail').value;
            const newDonorPhone = document.getElementById('donorPhone').value;

            // Fiyatı al
            const donationPrice = document.getElementById('donationPriceInput').value;

            // Telefon numarası
            const countryCode = document.querySelector('.prefix-code')?.textContent || '+90';

            // Sepetteki öğeyi güncelle
            cart[index] = {
                ...item,
                title: newTitle,
                donationType: newType,
                donorName: newDonorName,
                donorEmail: newDonorEmail,
                donorPhone: newDonorPhone,
                amount: donationPrice
            };

            // LocalStorage'a kaydet
            localStorage.setItem('donationCart', JSON.stringify(cart));

            // Sepet badge'ini güncellemek için olay tetikle
            document.dispatchEvent(new Event('cartUpdated'));

            // Sepeti yeniden yükle
            loadCartItems();

            // Modalı kapat
            editModal.hide();
        });
    }
});
</script>