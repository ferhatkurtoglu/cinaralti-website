<!-- Bağış Detayı -->

<div class="inner_banner-section">
    <h3 class="inner_banner-title" id="donation-title">Bağış Detayı</h3>
</div>

<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Bağış Detayı : Ana Bölüm
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
<div class="donate-details-section">
    <div class="container">
        <div class="row">
            <!-- Bağış Görseli -->
            <div class="col-lg-8">
                <div class="donate-details-image">
                    <img src="../public/assets/image/donate/filistin.jpg" alt="Bağış Görseli" id="donation-main-image">
                </div>
            </div>

            <!-- Bağış Bilgileri ve Form -->
            <div class="col-lg-4">
                <div class="donate-details-info">
                    <div class="donate-details-header">
                        <h2 class="donate-details-title" id="donation-type">Filistin/Gazze</h2>
                        <p class="donate-details-category" id="donation-category">Yükleniyor...</p>
                    </div>

                    <div class="donate-form">
                        <label class="donate-form__label">Bağış Tutarı</label>

                        <!-- Preset Amount Buttons -->
                        <div class="donate-form__preset-amounts">
                            <button type="button" class="preset-amount-btn" data-amount="50">₺50</button>
                            <button type="button" class="preset-amount-btn" data-amount="100">₺100</button>
                            <button type="button" class="preset-amount-btn" data-amount="250">₺250</button>
                            <button type="button" class="preset-amount-btn" data-amount="500">₺500</button>
                        </div>

                        <div class="donate-form__input-wrapper">
                            <input type="text" class="donate-form__input" placeholder="₺0" value="₺0" />
                        </div>

                        <div class="donate-form__options">
                            <label class="donate-radio">
                                <input type="radio" name="donateType" checked>
                                <span class="donate-radio__mark"></span>
                                <span class="donate-radio__text">Standart Bağış</span>
                            </label>
                            <label class="donate-radio">
                                <input type="radio" name="donateType">
                                <span class="donate-radio__mark"></span>
                                <span class="donate-radio__text">Zekat Bağışı</span>
                            </label>
                        </div>

                        <div class="donate-form__type">
                            <button class="donate-type-btn active">Bireysel</button>
                            <button class="donate-type-btn">Grup</button>
                            <button class="donate-type-btn">Kurumsal</button>
                        </div>

                        <div class="donate-form__fields">
                            <div class="donate-form__field">
                                <span class="field-icon">👤</span>
                                <input type="text" placeholder="Ad Soyad" />
                            </div>
                            <div class="donate-form__field">
                                <span class="field-icon">🇹🇷</span>
                                <input type="tel" placeholder="+90" />
                            </div>
                            <div class="donate-form__field">
                                <span class="field-icon">✉️</span>
                                <input type="email" placeholder="E-Posta" />
                            </div>
                        </div>

                        <div class="donate-form__buttons">
                            <button type="button" class="btn-donate-form">
                                <span class="btn-icon">❤️</span>
                                Bağış Yap
                            </button>
                            <button type="button" class="btn-cart">
                                <span class="btn-icon">🛒</span>
                                Sepete Ekle
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Açıklama Bölümü -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="donate-details-description">
                    <h3 class="details-section-title">Bağış Hakkında</h3>
                    <div class="details-description-content" id="donation-description">
                        <!-- Test/örnek yazılar kaldırıldı. Gerçek içerik buraya eklenecek. -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Resim Slider Bölümü -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="donate-details-slider">
                    <h3 class="details-section-title">Yardım Faaliyetleri</h3>
                    <div class="details-slider-container">
                        <div id="donationImagesCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="../public/assets/image/donate/slider1.jpg" class="d-block w-100"
                                        alt="Yardım Faaliyeti">
                                </div>
                                <div class="carousel-item">
                                    <img src="../public/assets/image/donate/slider2.jpg" class="d-block w-100"
                                        alt="Yardım Faaliyeti">
                                </div>
                                <div class="carousel-item">
                                    <img src="../public/assets/image/donate/slider3.jpg" class="d-block w-100"
                                        alt="Yardım Faaliyeti">
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#donationImagesCarousel"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Önceki</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#donationImagesCarousel"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Sonraki</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Bağış Detayı Sayfası Stilleri */
.donate-details-section {
    padding: 40px 0;
    background-color: #f8f9fa;
}

.row {
    display: flex;
    align-items: stretch;
}

/* Görsel Alanı */
.donate-details-image {
    height: 100%;
    min-height: 700px;
    overflow: hidden;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.donate-details-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Form Alanı */
.donate-details-info {
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    height: 100%;
}

.donate-details-header {
    margin-bottom: 20px;
}

.donate-details-title {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 8px;
    color: #1a1a1a;
}

.donate-details-category {
    color: #28a745;
    font-size: 16px;
    font-weight: 500;
    text-transform: uppercase;
}

.details-section-title {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 20px;
    color: #333;
    position: relative;
    padding-bottom: 10px;
}

.details-section-title:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 3px;
    background-color: #28a745;
}

.details-description-content {
    font-size: 16px;
    line-height: 1.8;
    color: #555;
}

.details-description-content p {
    margin-bottom: 15px;
}

.details-slider-container {
    margin-top: 20px;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
}

.carousel-item img {
    height: 400px;
    object-fit: cover;
}

/* Bağış Formu Stilleri */
.donate-form {
    padding: 5px;
    margin-bottom: 20px;
}

.donate-form__label {
    color: #666;
    font-size: 14px;
    margin-bottom: 8px;
    display: block;
}

.donate-form__input-wrapper {
    margin-bottom: 20px;
}

.donate-form__input {
    width: 100%;
    padding: 12px 15px;
    font-size: 24px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-weight: 600;
    color: #1a1a1a;
    background-color: transparent;
    transition: all 0.3s ease;
}

.donate-form__input::placeholder {
    color: #adb5bd;
}

.donate-form__input:focus {
    outline: none;
    border-color: #28a745;
    box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
}

/* Preset Amount Buttons */
.donate-form__preset-amounts {
    display: flex;
    gap: 8px;
    margin-bottom: 15px;
    flex-wrap: wrap;
}

.preset-amount-btn {
    padding: 8px 16px;
    border: 2px solid #e9ecef;
    background: #fff;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    color: #666;
    cursor: pointer;
    transition: all 0.3s ease;
    flex: 1;
    min-width: 70px;
}

.preset-amount-btn:hover {
    border-color: #28a745;
    color: #28a745;
    background: rgba(40, 167, 69, 0.05);
}

.preset-amount-btn.selected {
    background: #28a745;
    border-color: #28a745;
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.2);
}

.donate-form__options {
    display: flex;
    gap: 20px;
    margin-bottom: 25px;
}

.donate-radio {
    display: flex;
    align-items: center;
    cursor: pointer;
    user-select: none;
}

.donate-radio input {
    display: none;
}

.donate-radio__mark {
    width: 22px;
    height: 22px;
    border: 2px solid #28a745;
    border-radius: 50%;
    margin-right: 10px;
    position: relative;
    transition: all 0.2s ease;
}

.donate-radio input:checked+.donate-radio__mark {
    background: #28a745;
    border-width: 7px;
}

.donate-radio__text {
    font-size: 15px;
    color: #444;
    font-weight: 500;
}

.donate-form__type {
    display: flex;
    background: #f8f9fa;
    border-radius: 10px;
    padding: 4px;
    margin-bottom: 25px;
}

.donate-type-btn {
    flex: 1;
    padding: 10px;
    border: none;
    background: none;
    border-radius: 8px;
    font-size: 14px;
    color: #666;
    cursor: pointer;
    transition: all 0.3s ease;
}

.donate-type-btn.active {
    background: #fff;
    color: #28a745;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.donate-form__fields {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 25px;
}

.donate-form__field {
    display: flex;
    align-items: center;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 10px 15px;
    transition: all 0.3s ease;
}

.donate-form__field:focus-within {
    border-color: #28a745;
    box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
}

.field-icon {
    margin-right: 10px;
    font-size: 18px;
}

.donate-form__field input {
    border: none;
    width: 100%;
    font-size: 15px;
    color: #444;
    background: transparent;
}

.donate-form__field input:focus {
    outline: none;
}

.donate-form__buttons {
    display: flex;
    gap: 12px;
}

.btn-donate-form,
.btn-cart {
    flex: 1;
    padding: 14px;
    border: none;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-donate-form {
    background: #28a745;
    color: #fff;
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.2);
}

.btn-donate-form:hover {
    background: #218838;
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(40, 167, 69, 0.3);
}

.btn-cart {
    background: #f8f9fa;
    color: #444;
    border: 2px solid #e9ecef;
}

.btn-cart:hover {
    background: #e9ecef;
    border-color: #dde2e6;
}

/* Bildirim Stilleri */
.donation-notification {
    position: fixed;
    bottom: 30px;
    right: 30px;
    background-color: #4CAF50;
    color: white;
    padding: 16px 20px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    z-index: 9999;
    transform: translateY(100px);
    opacity: 0;
    transition: all 0.3s ease;
}

.donation-notification.show {
    transform: translateY(0);
    opacity: 1;
}

.notification-icon {
    font-size: 20px;
    margin-right: 12px;
}

.notification-message {
    font-size: 16px;
    font-weight: 500;
    margin-right: 15px;
}

.notification-btn {
    background-color: white;
    color: #4CAF50;
    padding: 5px 12px;
    border-radius: 5px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    white-space: nowrap;
    margin-left: auto;
}

.notification-btn:hover {
    background-color: #f1f1f1;
    text-decoration: none;
    color: #3d9140;
}

@media (max-width: 991px) {
    .donate-details-image {
        min-height: 500px;
        margin-bottom: 30px;
    }

    .donate-details-info {
        padding: 20px;
    }

    .col-lg-8,
    .col-lg-4 {
        width: 100%;
    }
}

@media (max-width: 576px) {
    .donate-details-image {
        min-height: 400px;
    }

    .donate-form__buttons {
        flex-direction: column;
    }

    .donate-form__options {
        flex-direction: column;
        gap: 15px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // URL'den bağış ID'sini al
    const urlParams = new URLSearchParams(window.location.search);
    const donationId = urlParams.get('id');
    const donationType = urlParams.get('type') || 'Genel Bağış';

    // Eğer ID varsa, API'den veriyi çek
    if (donationId) {
        loadDonationData(donationId);
    } else {
        // Varsayılan değerler
        document.getElementById('donation-title').textContent = donationType;
        document.getElementById('donation-type').textContent = donationType;
        document.getElementById('donation-category').textContent = 'Genel Bağış';
    }

    // Bağış verilerini API'den yükle
    async function loadDonationData(id) {
        try {
            const response = await fetch(`../admin/app/api/donation-types/${id}`);
            if (!response.ok) {
                throw new Error('Veri yüklenemedi');
            }

            const data = await response.json();

            // Sayfa verilerini güncelle
            document.getElementById('donation-title').textContent = data.name;
            document.getElementById('donation-type').textContent = data.name;

            // Kategorileri göster
            if (data.categories && data.categories.length > 0) {
                const categoryNames = data.categories.map(cat => cat.name).join(', ');
                document.getElementById('donation-category').textContent = categoryNames;
            } else {
                document.getElementById('donation-category').textContent = 'Genel Bağış';
            }

            // Açıklama
            if (data.description) {
                document.getElementById('donation-description').innerHTML = `<p>${data.description}</p>`;
            }

            // Ana görsel
            if (data.cover_image) {
                document.getElementById('donation-main-image').src = data.cover_image;
            }

            // Galeri görselleri
            if (data.gallery_images && Array.isArray(data.gallery_images)) {
                updateCarouselImages(data.gallery_images);
            }

        } catch (error) {
            console.error('Bağış verisi yüklenirken hata:', error);
            // Varsayılan değerler
            document.getElementById('donation-category').textContent = 'Genel Bağış';
        }
    }

    // Carousel görsellerini güncelle
    function updateCarouselImages(images) {
        const carouselInner = document.querySelector('#donationImagesCarousel .carousel-inner');
        if (!carouselInner || images.length === 0) return;

        carouselInner.innerHTML = '';

        images.forEach((image, index) => {
            const carouselItem = document.createElement('div');
            carouselItem.className = `carousel-item ${index === 0 ? 'active' : ''}`;
            carouselItem.innerHTML =
                `<img src="${image}" class="d-block w-100" alt="Yardım Faaliyeti">`;
            carouselInner.appendChild(carouselItem);
        });
    }

    // Preset amount buttons functionality
    document.querySelectorAll('.preset-amount-btn').forEach(button => {
        button.addEventListener('click', function() {
            // Remove selected class from all preset buttons
            document.querySelectorAll('.preset-amount-btn').forEach(btn => {
                btn.classList.remove('selected');
            });

            // Add selected class to clicked button
            this.classList.add('selected');

            // Update the donation amount input
            const amount = this.getAttribute('data-amount');
            const formattedAmount = `₺${parseInt(amount).toLocaleString('tr-TR')}`;
            document.querySelector('.donate-form__input').value = formattedAmount;
        });
    });

    // Para formatı için input işleyici
    const donateInputs = document.querySelectorAll('.donate-form__input, .donate-card-price-input');

    donateInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d]/g, '');
            if (value) {
                value = parseInt(value).toLocaleString('tr-TR');
                e.target.value = '₺' + value;
            } else {
                e.target.value = '';
            }
        });

        input.addEventListener('focus', function(e) {
            if (e.target.value === '₺0') {
                e.target.value = '₺';
            }
        });

        input.addEventListener('blur', function(e) {
            if (e.target.value === '₺') {
                e.target.value = '₺0';
            }
        });
    });

    // Bireysel/Grup/Kurumsal buton işleyicisi
    const typeButtons = document.querySelectorAll('.donate-type-btn');
    typeButtons.forEach(button => {
        button.addEventListener('click', function() {
            typeButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Bağış Yap butonuna tıklama
    const donateBtn = document.querySelector('.btn-donate-form');
    if (donateBtn) {
        donateBtn.addEventListener('click', function(e) {
            e.preventDefault();
            try {
                // Form doğrulama
                if (!validateDonationForm()) {
                    console.log("Form doğrulama başarısız");
                    return;
                }

                // Bağış bilgilerini al
                const donationTitle = document.getElementById('donation-type')?.textContent ||
                    'Genel Bağış';
                const donationAmount = document.querySelector('.donate-form__input')?.value || '₺0';

                // Bağış türü
                let donationType = "Standart Bağış";
                const donateTypeEl = document.querySelector('input[name="donateType"]:checked');
                if (donateTypeEl) {
                    const radioText = donateTypeEl.parentElement.querySelector('.donate-radio__text');
                    if (radioText) {
                        donationType = radioText.textContent.trim();
                    }
                }

                // Kişisel bilgiler
                const donorName = document.querySelector(
                    '.donate-form__field input[placeholder="Ad Soyad"]')?.value || '';
                const donorPhone = document.querySelector(
                    '.donate-form__field input[placeholder="+90"]')?.value || '';
                const donorEmail = document.querySelector(
                    '.donate-form__field input[placeholder="E-Posta"]')?.value || '';

                // Bağışçı türü
                let donorType = "Bireysel";
                const activeTypeBtn = document.querySelector('.donate-type-btn.active');
                if (activeTypeBtn) {
                    donorType = activeTypeBtn.textContent.trim();
                }

                console.log("Toplanan veriler:", {
                    title: donationTitle,
                    amount: donationAmount,
                    donationType: donationType,
                    donorName: donorName,
                    donorPhone: donorPhone,
                    donorEmail: donorEmail,
                    donorType: donorType
                });

                // Sepete ekle
                const donationData = {
                    title: donationTitle,
                    amount: donationAmount,
                    donationType: donationType,
                    donorName: donorName,
                    donorPhone: donorPhone,
                    donorEmail: donorEmail,
                    donorType: donorType,
                    image: document.getElementById('donation-main-image')?.src || ''
                };

                addToCart(donationData);
                console.log("Sepete eklendi, yönlendirme yapılıyor");

                // Sepet sayfasına git - daha güvenli yöntem
                const baseUrl = window.location.origin + window.location.pathname.replace(
                    '/donate-details', '').replace('/pages/donate-details', '');
                console.log("Base URL:", baseUrl);

                // Farklı URL yapılarını deneme
                const possibleCartUrls = [
                    baseUrl + '/cart',
                    baseUrl + '/pages/cart',
                    window.location.origin + '/cinaralti-website/pages/cart'
                ];

                // İlk denemeyi yap
                window.location.href = possibleCartUrls[0];

            } catch (error) {
                console.error("Bağış işlemi sırasında hata:", error);
                showNotification('Bağış işlemi sırasında bir hata oluştu.', 'error');
            }
        });
    } else {
        console.error('Bağış Yap butonu bulunamadı!');
    }

    // Sepete Ekle butonuna tıklama
    document.querySelector('.btn-cart').addEventListener('click', function() {
        // Form doğrulama
        if (!validateDonationForm()) {
            return;
        }

        // Bağış bilgilerini al
        const donationTitle = document.getElementById('donation-type').textContent;
        const donationAmount = document.querySelector('.donate-form__input').value;
        const donationType = document.querySelector('input[name="donateType"]:checked')
            .nextElementSibling.nextElementSibling.textContent;
        const donorName = document.querySelector('.donate-form__field input[placeholder="Ad Soyad"]')
            .value;
        const donorPhone = document.querySelector('.donate-form__field input[placeholder="+90"]').value;
        const donorEmail = document.querySelector('.donate-form__field input[placeholder="E-Posta"]')
            .value;
        const donorType = document.querySelector('.donate-type-btn.active').textContent;

        // Sepete ekle
        addToCart({
            title: donationTitle,
            amount: donationAmount,
            donationType: donationType,
            donorName: donorName,
            donorPhone: donorPhone,
            donorEmail: donorEmail,
            donorType: donorType,
            image: document.getElementById('donation-main-image').src
        });

        // Bildirimi göster
        showNotification('Bağış sepete eklendi');
    });

    // Sepete ekleme fonksiyonu
    function addToCart(donationData) {
        // LocalStorage'dan mevcut sepeti al
        let cart = JSON.parse(localStorage.getItem('donationCart')) || [];

        // Yeni bağışı sepete ekle
        cart.push(donationData);

        // Sepeti güncelle
        localStorage.setItem('donationCart', JSON.stringify(cart));

        // Toplam tutarı kaydet
        updateCartTotal();

        // Sepet badge'ini güncellemek için olay tetikle
        document.dispatchEvent(new Event('cartUpdated'));
    }

    // Bildirim gösterme fonksiyonu
    function showNotification(message, type = 'success') {
        // Bildirim elementi oluştur
        const notification = document.createElement('div');
        notification.className = 'donation-notification';

        // Hata bildirimi için farklı renk
        if (type === 'error') {
            notification.style.backgroundColor = '#dc3545';
        }

        // Bildirim içeriği
        notification.innerHTML = `
            <div class="notification-icon">${type === 'success' ? '✓' : '⚠️'}</div>
            <div class="notification-message">${message}</div>
            ${type === 'success' ? `<a href="<?= BASE_URL ?>/cart" class="notification-btn">Sepete Git</a>` : ''}
        `;

        // Sayfaya ekle
        document.body.appendChild(notification);

        // Animasyon için gecikme
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);

        // Belirli bir süre sonra kaldır
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                notification.remove();
            }, 500);
        }, type === 'error' ? 3000 : 5000); // Hata bildirimleri daha kısa görünsün
    }

    // Sepet toplam tutarını güncelleme
    function updateCartTotal() {
        const cart = JSON.parse(localStorage.getItem('donationCart')) || [];
        let total = 0;

        cart.forEach(item => {
            // Tutardan sadece sayıları al (₺120 formatından 120 olarak)
            const amount = parseInt(item.amount.replace(/[^\d]/g, '')) || 0;
            total += amount;
        });

        // Toplam tutarı formatla ve kaydet
        const formattedTotal = `₺${total.toLocaleString('tr-TR')},00`;
        localStorage.setItem('cartTotalAmount', formattedTotal);
    }

    // Form doğrulama fonksiyonu - Sadece tutar zorunlu
    function validateDonationForm() {
        const donationAmount = document.querySelector('.donate-form__input').value;

        // Bağış tutarı kontrolü (sadece bu zorunlu)
        if (!donationAmount || donationAmount === '₺0' || donationAmount === '₺') {
            showValidationError('Lütfen geçerli bir bağış tutarı giriniz.');
            return false;
        }

        // Tutardan sadece sayıları al ve kontrol et
        const amountValue = donationAmount.replace(/[₺,.\s]/g, '');
        if (!amountValue || isNaN(amountValue) || parseInt(amountValue) <= 0) {
            showValidationError('Lütfen geçerli bir bağış tutarı giriniz.');
            return false;
        }

        return true;
    }

    // E-posta doğrulama
    function isValidEmail(email) {
        const re =
            /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    // Doğrulama hatası gösterme
    function showValidationError(message) {
        showNotification(message, 'error');
    }

});
</script>