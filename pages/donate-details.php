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
            <!-- Bağış Bilgileri ve Form -->
            <div class="col-lg-6">
                <div class="donate-details-info">
                    <div class="donate-details-header">
                        <h2 class="donate-details-title" id="donation-type">Bağış Detayı</h2>
                        <p class="donate-details-category" id="donation-category"></p>
                    </div>
                    
                    <div class="donate-form">
                        <label class="donate-form__label">Bağış Tutarı</label>
                        <div class="donate-form__input-wrapper">
                            <input type="text" class="donate-form__input" placeholder="₺0" />
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
                            <button type="button" class="btn-donate">
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
            
            <!-- Bağış Görseli -->
            <div class="col-lg-6">
                <div class="donate-details-image">
                    <img src="../public/assets/image/donate/filistin.jpg" alt="Bağış Görseli" id="donation-main-image">
                </div>
            </div>
        </div>
        
        <!-- Açıklama Bölümü -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="donate-details-description">
                    <h3 class="details-section-title">Bağış Hakkında</h3>
                    <div class="details-description-content" id="donation-description">
                        <p>3.385.902 kişiye ekmek, 39.233 battaniye, 56.748 hijyen paketi, 54.469 bebek bezi, 501 kişilik tıbbi malzeme ve 1.300 kişilik ilk yardım çantası ulaştırdık.</p>
                        <p>Gelin, Filistinli ihtiyaç sahiplerine destek olalım. Yapacağınız küçük bir yardım bir kişinin hayatını kolaylaştırabilir. Bize ihtiyaçları var.</p>
                        <p><em>Bu projeye bankadan bağış yapmak isterseniz açıklama kısmına 19163 yazmanız yeterli.</em></p>
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
                                    <img src="../public/assets/image/donate/slider1.jpg" class="d-block w-100" alt="Yardım Faaliyeti">
                                </div>
                                <div class="carousel-item">
                                    <img src="../public/assets/image/donate/slider2.jpg" class="d-block w-100" alt="Yardım Faaliyeti">
                                </div>
                                <div class="carousel-item">
                                    <img src="../public/assets/image/donate/slider3.jpg" class="d-block w-100" alt="Yardım Faaliyeti">
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#donationImagesCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Önceki</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#donationImagesCarousel" data-bs-slide="next">
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
        padding: 60px 0;
    }
    
    .donate-details-header {
        margin-bottom: 30px;
    }
    
    .donate-details-title {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 5px;
        color: #333;
    }
    
    .donate-details-category {
        color: #dc3545;
        font-size: 18px;
        font-weight: 500;
    }
    
    .donate-details-image {
        height: 100%;
        overflow: hidden;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .donate-details-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
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
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    
    .carousel-item img {
        height: 400px;
        object-fit: cover;
    }
    
    /* Form elementleri için CSS burada zaten doante.php'den alınabilir */
    
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
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
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
    
    @media (max-width: 768px) {
        .donation-notification {
            left: 20px;
            right: 20px;
            bottom: 20px;
            text-align: center;
            justify-content: space-between;
            flex-wrap: wrap;
            padding: 12px 16px;
        }
        
        .notification-message {
            margin-right: 0;
            margin-bottom: 8px;
            width: 100%;
            text-align: center;
        }
        
        .notification-icon {
            margin-right: 0;
        }
        
        .notification-btn {
            margin-left: 0;
            margin-top: 8px;
            width: 100%;
            text-align: center;
            padding: 8px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // URL'den bağış türünü al
        const urlParams = new URLSearchParams(window.location.search);
        const donationType = urlParams.get('type') || 'Genel Bağış';
        
        // Sayfa başlığını ve diğer yerleri güncelle
        document.getElementById('donation-title').textContent = donationType;
        document.getElementById('donation-type').textContent = donationType;
        
        // Bağış türüne göre kategori belirle (örnek)
        let category = "Acil Yardım";
        if (donationType === "Zekat") {
            category = "Zekat Bağışı";
        } else if (donationType === "Bina Satın Alma") {
            category = "Altyapı Projesi";
        }
        document.getElementById('donation-category').textContent = category;
        
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
        document.querySelector('.btn-donate').addEventListener('click', function() {
            // Bağış bilgilerini al
            const donationTitle = document.getElementById('donation-type').textContent;
            const donationAmount = document.querySelector('.donate-form__input').value;
            const donationType = document.querySelector('input[name="donateType"]:checked').nextElementSibling.nextElementSibling.textContent;
            const donorName = document.querySelector('.donate-form__field input[placeholder="Ad Soyad"]').value;
            const donorPhone = document.querySelector('.donate-form__field input[placeholder="+90"]').value;
            const donorEmail = document.querySelector('.donate-form__field input[placeholder="E-Posta"]').value;
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
            
            // Sepet sayfasına git
            window.location.href = '<?= BASE_URL ?>/cart';
        });

        // Sepete Ekle butonuna tıklama
        document.querySelector('.btn-cart').addEventListener('click', function() {
            // Bağış bilgilerini al
            const donationTitle = document.getElementById('donation-type').textContent;
            const donationAmount = document.querySelector('.donate-form__input').value;
            const donationType = document.querySelector('input[name="donateType"]:checked').nextElementSibling.nextElementSibling.textContent;
            const donorName = document.querySelector('.donate-form__field input[placeholder="Ad Soyad"]').value;
            const donorPhone = document.querySelector('.donate-form__field input[placeholder="+90"]').value;
            const donorEmail = document.querySelector('.donate-form__field input[placeholder="E-Posta"]').value;
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
        function showNotification(message) {
            // Bildirim elementi oluştur
            const notification = document.createElement('div');
            notification.className = 'donation-notification';
            notification.innerHTML = `
                <div class="notification-icon">✓</div>
                <div class="notification-message">${message}</div>
                <a href="<?= BASE_URL ?>/cart" class="notification-btn">Sepete Git</a>
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
            }, 5000); // Bildirim 5 saniye boyunca görünecek
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
    });
</script>