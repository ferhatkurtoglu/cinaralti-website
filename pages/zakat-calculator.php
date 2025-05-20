<!-- Zekat Hesaplama -->

<style>
.zakat-calculator-section {
    background-color: #f8f9fa;
    padding: 80px 0;
}

.zakat-calculator-wrapper {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    padding: 40px;
    position: relative;
    overflow: hidden;
}

.zakat-calculator-wrapper:before {
    content: '';
    position: absolute;
    top: -50px;
    right: -50px;
    width: 200px;
    height: 200px;
    background: rgba(76, 175, 80, 0.05);
    border-radius: 50%;
    z-index: 0;
}

.zakat-calculator-wrapper:after {
    content: '';
    position: absolute;
    bottom: -80px;
    left: -80px;
    width: 300px;
    height: 300px;
    background: rgba(76, 175, 80, 0.03);
    border-radius: 50%;
    z-index: 0;
}

.heading-sm {
    color: #2e7d32;
    font-weight: 700;
    position: relative;
    display: inline-block;
    margin-bottom: 15px;
}

.heading-sm:after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 0;
    width: 60px;
    height: 3px;
    background: #4CAF50;
    border-radius: 3px;
}

.zakat-calculator-form {
    position: relative;
    z-index: 1;
    margin-top: 30px;
}

.form-group {
    margin-bottom: 25px;
}

.form-control {
    height: 55px;
    border-radius: 10px;
    border: 1px solid #e0e0e0;
    padding: 10px 15px;
    font-size: 16px;
    transition: all 0.3s;
    background-color: #f9f9f9;
}

.form-control:focus {
    box-shadow: none;
    border-color: #4CAF50;
    background-color: #fff;
}

label {
    font-weight: 500;
    margin-bottom: 8px;
    color: #333;
    display: flex;
    align-items: center;
}

.label-icon {
    margin-right: 8px;
    color: #4CAF50;
}

#calculateZakat {
    height: 55px;
    font-weight: 600;
    font-size: 16px;
    background: linear-gradient(45deg, #2e7d32, #4CAF50);
    border: none;
    box-shadow: 0 5px 15px rgba(76, 175, 80, 0.2);
    transition: all 0.3s ease;
}

#calculateZakat:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(76, 175, 80, 0.3);
}

.zakat-result {
    position: relative;
    z-index: 1;
    margin-top: 40px;
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    border-radius: 15px;
    border: none;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.card-body {
    padding: 30px;
}

.card-title {
    color: #2e7d32;
    font-weight: 700;
    margin-bottom: 20px;
    position: relative;
    padding-bottom: 15px;
}

.card-title:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 50px;
    height: 3px;
    background: #4CAF50;
    border-radius: 3px;
}

.zakat-result-content {
    padding: 15px 0;
}

.zakat-result-content p {
    display: flex;
    justify-content: space-between;
    font-size: 16px;
    color: #555;
}

.zakat-result-content h4 {
    display: flex;
    justify-content: space-between;
    color: #2e7d32;
    font-weight: 700;
    padding-top: 15px;
    border-top: 1px dashed #e0e0e0;
}

.zakat-result-content span {
    font-weight: 600;
}

.zakat-info {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 30px;
    margin-top: 40px;
}

.zakat-info h3 {
    color: #2e7d32;
    font-weight: 600;
    margin-bottom: 20px;
}

.zakat-info ul {
    list-style-type: none;
    padding-left: 0;
}

.zakat-info ul li {
    position: relative;
    padding-left: 24px;
    margin-bottom: 12px;
    line-height: 1.5;
}

.zakat-info ul li:before {
    content: '✓';
    position: absolute;
    left: 0;
    color: #4CAF50;
    font-weight: bold;
}

.nisab-info {
    background: #e8f5e9;
    border-radius: 10px;
    padding: 20px;
    margin: 20px 0;
    border-left: 4px solid #4CAF50;
}

.nisab-info h4 {
    color: #2e7d32;
    font-size: 18px;
    margin-bottom: 10px;
}

.nisab-info p {
    margin-bottom: 5px;
}

/* Mobil uyumluluk */
@media (max-width: 768px) {
    .zakat-calculator-wrapper {
        padding: 25px;
    }

    .form-control {
        height: 50px;
    }

    .card-body {
        padding: 20px;
    }

    .zakat-info {
        padding: 20px;
    }
}

.tooltip-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 18px;
    height: 18px;
    background-color: #4CAF50;
    color: white;
    border-radius: 50%;
    font-size: 12px;
    margin-left: 6px;
    cursor: pointer;
}

.input-group-text {
    background-color: #f0f0f0;
    border-color: #e0e0e0;
    color: #555;
}

.exchange-rate-info {
    background: #e3f2fd;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 20px;
    border-left: 4px solid #1976d2;
    font-size: 14px;
}

.loading-spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid rgba(76, 175, 80, 0.3);
    border-radius: 50%;
    border-top-color: #4CAF50;
    animation: spin 1s ease-in-out infinite;
    margin-right: 10px;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

/* TDV Benzeri Kategori Sekmeler Stili */
.zakat-categories {
    border-bottom: none;
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    margin-bottom: 20px !important;
}

.zakat-category-tab {
    padding: 12px 15px;
    border-radius: 5px !important;
    margin-right: 0;
    font-weight: 600;
    color: #333;
    display: flex;
    align-items: center;
    font-size: 14px;
    border: 1px solid #e0e0e0;
    transition: all 0.3s;
}

.zakat-category-tab i {
    margin-right: 8px;
    font-size: 16px;
}

.currency-icon {
    margin-right: 8px;
    font-size: 18px;
    font-weight: bold;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.zakat-category-tab.active {
    background: #4CAF50;
    color: white;
    border-color: #4CAF50;
}

.zakat-category-content {
    padding: 30px;
    background: #f9f9f9;
    border-radius: 15px;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

/* Sepet Öğeleri Stili */
.cart-item {
    margin-bottom: 10px;
    transition: all 0.3s;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    overflow: hidden;
}

.cart-item:hover {
    background-color: #f8f9f8;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.cart-item>div {
    padding: 15px !important;
}

.cart-item strong {
    font-size: 18px;
    color: #2e7d32;
    display: block;
    margin-bottom: 5px;
}

.cart-item p.small {
    font-size: 14px;
    color: #666;
}

.remove-item {
    opacity: 0.7;
    transition: all 0.3s;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
}

.remove-item:hover {
    opacity: 1;
    background-color: #ff5252;
    color: white;
}

.cart-summary {
    background-color: #f8f9fa;
    border-radius: 15px;
    padding: 5px;
}

.cart-summary .p-3 {
    border-radius: 10px;
    background-color: #e8f5e9;
}

.cart-summary p {
    font-size: 16px;
    margin-bottom: 12px;
}

.cart-summary p.fw-bold {
    font-size: 20px;
    color: #2e7d32;
}

/* Bildirim Stilleri */
.notification-toast {
    position: fixed;
    bottom: 20px;
    right: 20px;
    min-width: 250px;
    padding: 15px;
    border-radius: 5px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    z-index: 9999;
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.3s ease;
}

.notification-toast.show {
    opacity: 1;
    transform: translateY(0);
}

.notification-toast .toast-body {
    display: flex;
    align-items: center;
    font-weight: 500;
}

/* Malın Adı Input Alanı Stilleri */
input[id$="Name"] {
    font-size: 20px;
    font-weight: 500;
    color: #2e7d32;
    height: 70px;
    background-color: #f8fff8;
    border: 2px solid #e0e0e0;
    border-left: 6px solid #4CAF50;
    padding-left: 20px;
    border-radius: 15px;
    width: 100%;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

input[id$="Name"]:focus {
    background-color: #f0fff0;
    border-color: #4CAF50;
    box-shadow: 0 0 0 0.25rem rgba(76, 175, 80, 0.25);
    transform: translateY(-2px);
}

label[for$="Name"] {
    font-size: 20px;
    font-weight: 600;
    color: #2e7d32;
    margin-bottom: 12px;
    display: block;
}

/* Form Grupları İçin Stillemeler */
.form-group {
    margin-bottom: 25px;
}

/* Diğer Form Elemanları İçin Düzenlemeler */
input[id$="Amount"] {
    height: 60px;
    font-size: 18px;
    border-radius: 10px;
    transition: all 0.3s ease;
}

input[id$="Amount"]:focus {
    transform: translateY(-2px);
    box-shadow: 0 0 0 0.25rem rgba(76, 175, 80, 0.25);
    border-color: #4CAF50;
}

.input-group-text {
    font-weight: 500;
    min-width: 60px;
    display: flex;
    justify-content: center;
    font-size: 16px;
    border-radius: 0 10px 10px 0;
    background-color: #e8f5e9;
    border-color: #e0e0e0;
}

select.form-select {
    height: 60px;
    font-size: 18px;
    border-color: #e0e0e0;
    border-radius: 10px;
    padding-left: 15px;
    transition: all 0.3s ease;
}

select.form-select:focus {
    border-color: #4CAF50;
    box-shadow: 0 0 0 0.25rem rgba(76, 175, 80, 0.25);
    transform: translateY(-2px);
}

.add-to-cart-btn {
    height: 60px;
    font-weight: 600;
    font-size: 18px;
    transition: all 0.3s ease;
}

.add-to-cart-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 15px rgba(76, 175, 80, 0.3);
}

/* Responsive Sekme Stilleri */
@media (max-width: 768px) {
    .zakat-categories {
        flex-direction: column;
    }

    .zakat-category-tab {
        width: 100%;
        margin-bottom: 5px;
    }
}

/* Kategori Geçiş Animasyonları */
.tab-pane.fade {
    transition: opacity 0.3s ease, transform 0.4s ease;
    transform: translateY(20px);
}

.tab-pane.fade.show.active {
    transform: translateY(0);
}
</style>

<div class="inner_banner-section">
    <h3 class="inner_banner-title">Zekat Hesaplama</h3>
</div>
<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Zekat Hesaplama Bölümü
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
<div class="zakat-calculator-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12 col-12">
                <div class="zakat-calculator-wrapper">
                    <h2 class="heading-sm">Zekat Hesaplama Aracı</h2>
                    <p class="mb-30">Zekat, İslam'ın beş temel şartından biridir ve mali durumu uygun olan her
                        Müslüman'ın yıllık birikiminin %2,5'ini ihtiyaç sahiplerine vermesi gerektiğini ifade eder.
                        Aşağıdaki hesaplama aracını kullanarak zekat miktarınızı hesaplayabilirsiniz.</p>

                    <div class="nisab-info">
                        <h4>Nisab Bilgisi</h4>
                        <p>Nisab, zekat vermek için gereken asgari varlık miktarıdır. Nisab miktarı 80,18 gram altın
                            değerindedir.</p>
                        <p>Güncel Nisab Miktarı: <strong id="currentNisabValue">Hesaplanıyor...</strong></p>
                    </div>

                    <div class="exchange-rate-info">
                        <h5>Güncel Kur Bilgileri <span id="lastUpdateTime"></span></h5>
                        <div class="row">
                            <div class="col-md-3">
                                <p>1 Gram Altın: <span id="goldRate">Yükleniyor...</span> TL</p>
                            </div>
                            <div class="col-md-3">
                                <p>1 Gram Gümüş: <span id="silverRate">Yükleniyor...</span> TL</p>
                            </div>
                            <div class="col-md-3">
                                <p>1 USD: <span id="usdRate">Yükleniyor...</span> TL</p>
                            </div>
                            <div class="col-md-3">
                                <p>1 EUR: <span id="eurRate">Yükleniyor...</span> TL</p>
                            </div>
                        </div>
                    </div>

                    <!-- Kategori sekmeleri -->
                    <ul class="nav nav-tabs zakat-categories mb-4" id="zakatCategories" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link zakat-category-tab active" data-category="tl" id="tl-tab"
                                data-bs-toggle="tab" data-bs-target="#tl-content" type="button" role="tab"
                                aria-controls="tl-content" aria-selected="true">
                                <span class="currency-icon">₺</span> TÜRK LİRASI
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link zakat-category-tab" data-category="foreign" id="foreign-tab"
                                data-bs-toggle="tab" data-bs-target="#foreign-content" type="button" role="tab"
                                aria-controls="foreign-content" aria-selected="false">
                                <i class="fas fa-dollar-sign"></i> DÖVİZ
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link zakat-category-tab" data-category="gold" id="gold-tab"
                                data-bs-toggle="tab" data-bs-target="#gold-content" type="button" role="tab"
                                aria-controls="gold-content" aria-selected="false">
                                <i class="fas fa-coins"></i> ALTIN
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link zakat-category-tab" data-category="silver" id="silver-tab"
                                data-bs-toggle="tab" data-bs-target="#silver-content" type="button" role="tab"
                                aria-controls="silver-content" aria-selected="false">
                                <i class="fas fa-coins"></i> GÜMÜŞ
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link zakat-category-tab" data-category="commercial" id="commercial-tab"
                                data-bs-toggle="tab" data-bs-target="#commercial-content" type="button" role="tab"
                                aria-controls="commercial-content" aria-selected="false">
                                <i class="fas fa-store"></i> TİCARİ MALLAR
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link zakat-category-tab" data-category="other" id="other-tab"
                                data-bs-toggle="tab" data-bs-target="#other-content" type="button" role="tab"
                                aria-controls="other-content" aria-selected="false">
                                <i class="fas fa-box"></i> DİĞER
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link zakat-category-tab" data-category="debt" id="debt-tab"
                                data-bs-toggle="tab" data-bs-target="#debt-content" type="button" role="tab"
                                aria-controls="debt-content" aria-selected="false">
                                <i class="fas fa-hand-holding-usd"></i> BORÇLAR
                            </button>
                        </li>
                    </ul>

                    <!-- Kategori içerikleri -->
                    <div class="tab-content" id="zakatCategoriesContent">
                        <!-- TÜRK LİRASI -->
                        <div class="tab-pane zakat-category-content fade show active" data-category="tl" id="tl-content"
                            role="tabpanel" aria-labelledby="tl-tab">
                            <p class="mb-3">Asli ihtiyaçlarınız ve gelecek bir yıl içerisinde vadesi dolacak borçlarınız
                                dışında kenarda nakit olarak bulunan Türk Lirası cinsinden paralarınızı giriniz.</p>

                            <div class="form-group">
                                <label for="tlName">Malınızın Adı</label>
                                <input type="text" class="form-control" id="tlName" value="Nakit TL"
                                    placeholder="Örn: Nakit, Bankadaki Para, Vadeli Mevduat">
                            </div>
                            <div class="form-group">
                                <label for="tlAmount">Tutarı</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="tlAmount" placeholder="0">
                                    <span class="input-group-text">TL</span>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <button type="button" class="btn-masco btn-fill--up rounded-pill add-to-cart-btn"
                                    data-category="tl">
                                    <span>Sepete Ekle</span>
                                </button>
                            </div>
                        </div>

                        <!-- DÖVİZ -->
                        <div class="tab-pane zakat-category-content fade" data-category="foreign" id="foreign-content"
                            role="tabpanel" aria-labelledby="foreign-tab">
                            <p class="mb-3">Asli ihtiyaçlarınız ve gelecek bir yıl içerisinde vadesi dolacak borçlarınız
                                dışında kenarda nakit olarak bulunan döviz cinsinden paralarınızı giriniz. Her döviz
                                türünü ayrı ayrı girerek sepete eklemeniz gerekmektedir.</p>

                            <div class="form-group">
                                <label for="foreignName">Malınızın Adı</label>
                                <input type="text" class="form-control" id="foreignName" value="Döviz Hesabı"
                                    placeholder="Örn: Dolar Hesabım, Euro Nakit">
                            </div>
                            <div class="form-group">
                                <label for="foreignType">Türü</label>
                                <select class="form-select" id="foreignType">
                                    <option value="USD">Amerikan Doları</option>
                                    <option value="EUR">Euro</option>
                                    <option value="GBP">İngiliz Sterlini</option>
                                    <option value="CHF">İsviçre Frangı</option>
                                    <option value="JPY">Japon Yeni</option>
                                    <option value="SAR">S. Arabistan Riyali</option>
                                    <option value="AED">B.A.E. Dirhemi</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="foreignAmount">Tutarı</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="foreignAmount" placeholder="0">
                                    <span class="input-group-text" id="foreignCurrencySymbol">USD</span>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <button type="button" class="btn-masco btn-fill--up rounded-pill add-to-cart-btn"
                                    data-category="foreign">
                                    <span>Sepete Ekle</span>
                                </button>
                            </div>
                        </div>

                        <!-- ALTIN -->
                        <div class="tab-pane zakat-category-content fade" data-category="gold" id="gold-content"
                            role="tabpanel" aria-labelledby="gold-tab">
                            <p class="mb-3">Asli ihtiyaçlarınız ve gelecek bir yıl içerisinde vadesi dolacak borçlarınız
                                dışında kenarda bulunan altınlarınızı türlerine göre ayrı ayrı girerek sepete ekleyiniz.
                            </p>
                            <p class="mb-3"><strong>Not:</strong> Hanefi mezhebinden olanlar ziynet eşyalarını da zekat
                                hesaplamasına dahil ederler. Şafii mezhebinden olanlar ise küpe, yüzük, gerdanlık, kolye
                                vb. altından mamul ziynet eşyalarını zekat hesaplamasına dahil etmezler.</p>

                            <div class="form-group">
                                <label for="goldName">Malınızın Adı</label>
                                <input type="text" class="form-control" id="goldName" value="Altın"
                                    placeholder="Örn: Bilezik, Altın Hesabı, Çeyrek">
                            </div>
                            <div class="form-group">
                                <label for="goldType">Türü</label>
                                <select class="form-select" id="goldType">
                                    <option value="24 Ayar">24 Ayar Gram Altın</option>
                                    <option value="22 Ayar">22 Ayar Gram Altın</option>
                                    <option value="18 Ayar">18 Ayar Gram Altın</option>
                                    <option value="14 Ayar">14 Ayar Gram Altın</option>
                                    <option value="Çeyrek">Çeyrek Altın</option>
                                    <option value="Yarım">Yarım Altın</option>
                                    <option value="Tam">Tam Altın</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="goldAmount">Gram/Adet</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="goldAmount" placeholder="0">
                                    <span class="input-group-text">Gram/Adet</span>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <button type="button" class="btn-masco btn-fill--up rounded-pill add-to-cart-btn"
                                    data-category="gold">
                                    <span>Sepete Ekle</span>
                                </button>
                            </div>
                        </div>

                        <!-- GÜMÜŞ -->
                        <div class="tab-pane zakat-category-content fade" data-category="silver" id="silver-content"
                            role="tabpanel" aria-labelledby="silver-tab">
                            <p class="mb-3">Asli ihtiyaçlarınız ve gelecek bir yıl içerisinde vadesi dolacak borçlarınız
                                dışında kenarda bulunan gümüşlerinizi sepete ekleyiniz.</p>

                            <div class="form-group">
                                <label for="silverName">Malınızın Adı</label>
                                <input type="text" class="form-control" id="silverName" value="Gümüş"
                                    placeholder="Örn: Gümüş Takılar, Külçe Gümüş">
                            </div>
                            <div class="form-group">
                                <label for="silverAmount">Gram</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="silverAmount" placeholder="0">
                                    <span class="input-group-text">Gram</span>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <button type="button" class="btn-masco btn-fill--up rounded-pill add-to-cart-btn"
                                    data-category="silver">
                                    <span>Sepete Ekle</span>
                                </button>
                            </div>
                        </div>

                        <!-- TİCARİ MALLAR -->
                        <div class="tab-pane zakat-category-content fade" data-category="commercial"
                            id="commercial-content" role="tabpanel" aria-labelledby="commercial-tab">
                            <p class="mb-3">Asli ihtiyaçlarınız ve gelecek bir yıl içerisinde vadesi dolacak borçlarınız
                                dışında depolarınızda veya raflarınızda bulunan mamul veya yarı mamul ticari mallarınızı
                                Türk lirası veya döviz cinsinden olmak üzere ayrı ayrı sepete ekleyiniz.</p>

                            <div class="form-group">
                                <label for="commercialName">Malınızın Adı</label>
                                <input type="text" class="form-control" id="commercialName" value="Ticari Mallar"
                                    placeholder="Örn: Stok Ürünler, Ticari Mal">
                            </div>
                            <div class="form-group">
                                <label for="commercialType">Para Birimi</label>
                                <select class="form-select" id="commercialType">
                                    <option value="TL">Türk Lirası</option>
                                    <option value="USD">Amerikan Doları</option>
                                    <option value="EUR">Euro</option>
                                    <option value="GBP">İngiliz Sterlini</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="commercialAmount">Tutarı</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="commercialAmount" placeholder="0">
                                    <span class="input-group-text" id="commercialCurrencySymbol">TL</span>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <button type="button" class="btn-masco btn-fill--up rounded-pill add-to-cart-btn"
                                    data-category="commercial">
                                    <span>Sepete Ekle</span>
                                </button>
                            </div>
                        </div>

                        <!-- DİĞER -->
                        <div class="tab-pane zakat-category-content fade" data-category="other" id="other-content"
                            role="tabpanel" aria-labelledby="other-tab">
                            <p class="mb-3">Asli ihtiyaçlarınız ve gelecek bir yıl içerisinde vadesi dolacak borçlarınız
                                dışında hisse senedi, çek, senet, sukuk, kira sertifikası vb. diğer mallarınızı Türk
                                lirası veya döviz cinsinden olmak üzere ayrı ayrı sepete ekleyiniz.</p>

                            <div class="form-group">
                                <label for="otherName">Malınızın Adı</label>
                                <input type="text" class="form-control" id="otherName" value="Diğer Varlıklar"
                                    placeholder="Örn: Hisse Senetleri, Sukuk">
                            </div>
                            <div class="form-group">
                                <label for="otherCategory">Kategori</label>
                                <select class="form-select" id="otherCategory">
                                    <option value="Hisse Senedi">Hisse Senedi</option>
                                    <option value="Sukuk">Sukuk (Kira Sertifikası)</option>
                                    <option value="Çek">Çek</option>
                                    <option value="Senet">Senet</option>
                                    <option value="Kira Geliri">Kira Geliri</option>
                                    <option value="Diğer">Diğer</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="otherType">Para Birimi</label>
                                <select class="form-select" id="otherType">
                                    <option value="TL">Türk Lirası</option>
                                    <option value="USD">Amerikan Doları</option>
                                    <option value="EUR">Euro</option>
                                    <option value="GBP">İngiliz Sterlini</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="otherAmount">Tutarı</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="otherAmount" placeholder="0">
                                    <span class="input-group-text" id="otherCurrencySymbol">TL</span>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <button type="button" class="btn-masco btn-fill--up rounded-pill add-to-cart-btn"
                                    data-category="other">
                                    <span>Sepete Ekle</span>
                                </button>
                            </div>
                        </div>

                        <!-- BORÇLAR -->
                        <div class="tab-pane zakat-category-content fade" data-category="debt" id="debt-content"
                            role="tabpanel" aria-labelledby="debt-tab">
                            <p class="mb-3">Sadece gelecek bir yıl içerisinde vadesi dolacak borçlarınızı Türk lirası
                                veya döviz cinsinden ayrı ayrı giriniz.</p>
                            <p class="mb-3"><strong>Örneğin</strong> 10 yıl vadeli 180.000 TL ev borcu olan kimse
                                gelecek bir yıl içerisinde 18.000 TL borç ödeyecekse borç olarak 180.000 TL değil 18.000
                                TL'yi ilgili alana girer.</p>

                            <div class="form-group">
                                <label for="debtName">Borç Tanımı</label>
                                <input type="text" class="form-control" id="debtName" value="Borçlar"
                                    placeholder="Örn: Kredi Borcum, Kredi Kartı Borcu">
                            </div>
                            <div class="form-group">
                                <label for="debtType">Para Birimi</label>
                                <select class="form-select" id="debtType">
                                    <option value="TL">Türk Lirası</option>
                                    <option value="USD">Amerikan Doları</option>
                                    <option value="EUR">Euro</option>
                                    <option value="GBP">İngiliz Sterlini</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="debtAmount">Tutarı</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="debtAmount" placeholder="0">
                                    <span class="input-group-text" id="debtCurrencySymbol">TL</span>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <button type="button" class="btn-masco btn-fill--up rounded-pill add-to-cart-btn"
                                    data-category="debt">
                                    <span>Sepete Ekle</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Sepet ve Sonuç -->
                    <div class="card mt-4">
                        <div class="card-body">
                            <h3 class="card-title">Zekat Hesaplama Sepeti</h3>
                            <div id="cartItems" class="mb-4">
                                <p class="text-center">Henüz sepete eklenen varlık bulunmamaktadır.</p>
                            </div>
                            <div class="text-center mt-4">
                                <a href="<?= BASE_URL ?>/donate?category=zekat"
                                    class="btn-masco btn-fill--up rounded-pill"><span>Zekat Bağışı Yap</span></a>
                            </div>
                        </div>
                    </div>

                    <div class="zakat-info mt-4">
                        <h3>Zekat Hakkında Bilgiler</h3>
                        <p>Zekat, mali bir ibadettir ve aşağıdaki şartlar sağlandığında zekat vermek farzdır:</p>
                        <ul class="mb-20">
                            <li>Müslüman olmak</li>
                            <li>Hür olmak</li>
                            <li>Nisab miktarına sahip olmak (Nisab, temel ihtiyaçlar dışında 80,18 gram altın
                                değerinde mala sahip olmaktır)</li>
                            <li>Malın üzerinden bir kameri yıl (Hicri takvime göre 1 yıl) geçmesi</li>
                            <li>Malın artıcı özellikte olması (yani üretim veya ticaret amacıyla kullanılması)</li>
                        </ul>
                        <p>Zekat verilecek gruplar Kuran-ı Kerim'de belirtilmiştir:</p>
                        <ul>
                            <li>Fakirler</li>
                            <li>Miskinler (düşkünler)</li>
                            <li>Zekat toplama görevlileri</li>
                            <li>Kalpleri İslam'a ısındırılacak olanlar</li>
                            <li>Köleler (özgürlüğüne kavuşturmak için)</li>
                            <li>Borçlular</li>
                            <li>Allah yolunda çalışanlar</li>
                            <li>Yolda kalmış yolcular</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="section-button mt-40 text-center">
            <a href="<?= BASE_URL ?>/contact" class="btn-masco btn-fill--up rounded-pill"><span>Daha fazla bilgi için
                    bizimle iletişime geçin</span></a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calculateButton = document.getElementById('calculateZakat');
    const resultDiv = document.querySelector('.zakat-result');
    const nisabWarning = document.getElementById('nisabWarning');
    const tooltipIcons = document.querySelectorAll('.tooltip-icon');

    // Kur değerleri
    let goldRatePerGram = 0;
    let silverRatePerGram = 0;
    let usdRate = 0;
    let eurRate = 0;
    let nisabAmount = 0;

    // Tooltip fonksiyonalitesi (Bootstrap tooltip kullanıyor olduğunu varsayarak)
    if (typeof $ !== 'undefined' && typeof $.fn.tooltip !== 'undefined') {
        $(tooltipIcons).tooltip();
    }

    // Döviz tipini sembolüne göre ayarla
    document.getElementById('foreignType').addEventListener('change', function() {
        const selectedCurrency = this.value;
        document.getElementById('foreignCurrencySymbol').textContent = selectedCurrency;

        // Malın adını otomatik güncelle
        const nameField = document.getElementById('foreignName');
        if (nameField.value === 'Döviz Hesabı' || nameField.value.startsWith('Amerikan Doları') ||
            nameField.value.startsWith('Euro') || nameField.value.startsWith('İngiliz Sterlini') ||
            nameField.value.startsWith('İsviçre Frangı') || nameField.value.startsWith('Japon Yeni') ||
            nameField.value.startsWith('S. Arabistan Riyali') || nameField.value.startsWith(
                'B.A.E. Dirhemi')) {

            const option = this.options[this.selectedIndex];
            nameField.value = option.text;
        }
    });

    // Ticari mallar para birimini güncelle
    document.getElementById('commercialType').addEventListener('change', function() {
        const selectedCurrency = this.value;
        document.getElementById('commercialCurrencySymbol').textContent = selectedCurrency;
    });

    // Diğer varlıklar para birimini güncelle
    document.getElementById('otherType').addEventListener('change', function() {
        const selectedCurrency = this.value;
        document.getElementById('otherCurrencySymbol').textContent = selectedCurrency;
    });

    // Borç para birimini güncelle
    document.getElementById('debtType').addEventListener('change', function() {
        const selectedCurrency = this.value;
        document.getElementById('debtCurrencySymbol').textContent = selectedCurrency;
    });

    // Diğer varlıklar kategorisini güncelle
    document.getElementById('otherCategory').addEventListener('change', function() {
        const selectedCategory = this.options[this.selectedIndex].text;
        document.getElementById('otherName').value = selectedCategory;
    });

    // Altın türü değişince isim alanını güncelle
    document.getElementById('goldType').addEventListener('change', function() {
        const selectedGoldType = this.options[this.selectedIndex].text;
        document.getElementById('goldName').value = selectedGoldType;
    });

    // Kur bilgilerini almak için API çağrısı
    async function fetchExchangeRates() {
        try {
            // API'ler ile CORS sorununu önlemek için bir proxy kullanabiliriz veya doğrudan Collectapi kullanabiliriz
            // CORS sorununu önlemek için basit bir yaklaşım olarak alternatif API kullanıyoruz

            // TruncGil Finans API'den veri çekmeyi dene
            const response = await fetch('https://finans.truncgil.com/v4/today.json', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Cache-Control': 'no-cache'
                }
            });

            if (!response.ok) {
                throw new Error(`API yanıt vermedi: ${response.status}`);
            }

            const data = await response.json();

            // API'den dönen verileri konsola yazdıralım (hata ayıklama için)
            console.log("API Yanıtı:", data);

            // Altın fiyatını gram olarak ayarla
            // Not: API'de virgül kullanılıyor olabilir, bu yüzden önce virgülü noktaya çeviriyoruz
            if (data['Gram Altın'] && data['Gram Altın']['Selling']) {
                goldRatePerGram = parseFloat(data['Gram Altın']['Selling'].replace('.', '').replace(',',
                    '.'));
            } else if (data['Çeyrek Altın'] && data['Çeyrek Altın']['Selling']) {
                // Çeyrek altın yaklaşık 1.75 gramdır
                const ceyrekPrice = parseFloat(data['Çeyrek Altın']['Selling'].replace('.', '').replace(',',
                    '.'));
                goldRatePerGram = ceyrekPrice / 1.75;
            } else {
                // Yedek plan: Eğer altın fiyatı alınamazsa yerel bir değer kullan
                goldRatePerGram = 3800; // Ortalama bir değer
                console.warn("Altın fiyatı API'den alınamadı, varsayılan değer kullanılıyor");
            }

            // Gümüş fiyatını gram olarak ayarla
            if (data['Gümüş'] && data['Gümüş']['Selling']) {
                silverRatePerGram = parseFloat(data['Gümüş']['Selling'].replace('.', '').replace(',', '.'));
            } else {
                // Yedek plan: Eğer gümüş fiyatı alınamazsa yerel bir değer kullan
                silverRatePerGram = 40; // Ortalama bir değer
                console.warn("Gümüş fiyatı API'den alınamadı, varsayılan değer kullanılıyor");
            }

            // Döviz kurlarını al
            if (data['USD'] && data['USD']['Selling']) {
                usdRate = parseFloat(data['USD']['Selling'].replace('.', '').replace(',', '.'));
            } else {
                usdRate = 38.58; // Yedek değer
                console.warn("USD kuru API'den alınamadı, varsayılan değer kullanılıyor");
            }

            if (data['EUR'] && data['EUR']['Selling']) {
                eurRate = parseFloat(data['EUR']['Selling'].replace('.', '').replace(',', '.'));
            } else {
                eurRate = 43.70; // Yedek değer
                console.warn("EUR kuru API'den alınamadı, varsayılan değer kullanılıyor");
            }

            // Nisab değeri (80.18 gram altın değeri)
            nisabAmount = goldRatePerGram * 80.18;

            // Değerleri ekrana yazdır
            document.getElementById('goldRate').textContent = goldRatePerGram.toLocaleString('tr-TR');
            document.getElementById('silverRate').textContent = silverRatePerGram.toLocaleString('tr-TR');
            document.getElementById('usdRate').textContent = usdRate.toLocaleString('tr-TR');
            document.getElementById('eurRate').textContent = eurRate.toLocaleString('tr-TR');
            document.getElementById('currentNisabValue').textContent = nisabAmount.toLocaleString('tr-TR') +
                ' TL (80,18 gram altın)';

            // Son güncelleme zamanını göster
            const updateTime = data['Update_Date'] ? data['Update_Date'] : new Date().toLocaleString(
                'tr-TR');
            document.getElementById('lastUpdateTime').textContent = `(${updateTime})`;

            // Input değişikliklerini dinlemeye başla
            setupInputListeners();
        } catch (error) {
            console.error('Kur bilgileri alınamadı:', error);

            // Hata durumunda varsayılan değerleri kullan
            goldRatePerGram = 3800; // Yaklaşık değer
            silverRatePerGram = 40; // Yaklaşık değer
            usdRate = 38.58;
            eurRate = 43.70;
            nisabAmount = goldRatePerGram * 80.18;

            // Varsayılan değerleri ekrana yazdır
            document.getElementById('goldRate').textContent = goldRatePerGram.toLocaleString('tr-TR');
            document.getElementById('silverRate').textContent = silverRatePerGram.toLocaleString('tr-TR');
            document.getElementById('usdRate').textContent = usdRate.toLocaleString('tr-TR');
            document.getElementById('eurRate').textContent = eurRate.toLocaleString('tr-TR');
            document.getElementById('currentNisabValue').textContent = nisabAmount.toLocaleString('tr-TR') +
                ' TL (80,18 gram altın)';
            document.getElementById('lastUpdateTime').textContent =
                `(${new Date().toLocaleString('tr-TR')})`;

            // Kullanıcıya bilgi ver (opsiyonel)
            console.warn(
                "Varsayılan değerler kullanılıyor. Lütfen dikkat: Bu değerler güncel olmayabilir!");

            // Input değişikliklerini dinlemeye başla
            setupInputListeners();
        }
    }

    // Input değişikliklerini dinleyerek TL karşılıklarını göster
    function setupInputListeners() {
        const goldInput = document.getElementById('goldValue');
        const silverInput = document.getElementById('silverValue');
        const usdInput = document.getElementById('usdValue');
        const eurInput = document.getElementById('eurValue');

        goldInput.addEventListener('input', updateGoldValue);
        silverInput.addEventListener('input', updateSilverValue);
        usdInput.addEventListener('input', updateUsdValue);
        eurInput.addEventListener('input', updateEurValue);
    }

    function updateGoldValue() {
        const goldValue = parseFloat(document.getElementById('goldValue').value) || 0;
        const goldTotalValue = goldValue * goldRatePerGram;
        document.getElementById('goldTotalValue').textContent = goldTotalValue.toLocaleString('tr-TR');
    }

    function updateSilverValue() {
        const silverValue = parseFloat(document.getElementById('silverValue').value) || 0;
        const silverTotalValue = silverValue * silverRatePerGram;
        document.getElementById('silverTotalValue').textContent = silverTotalValue.toLocaleString('tr-TR');
    }

    function updateUsdValue() {
        const usdValue = parseFloat(document.getElementById('usdValue').value) || 0;
        const usdTotalValue = usdValue * usdRate;
        document.getElementById('usdTotalValue').textContent = usdTotalValue.toLocaleString('tr-TR');
    }

    function updateEurValue() {
        const eurValue = parseFloat(document.getElementById('eurValue').value) || 0;
        const eurTotalValue = eurValue * eurRate;
        document.getElementById('eurTotalValue').textContent = eurTotalValue.toLocaleString('tr-TR');
    }

    // Sayfa yüklendiğinde kur bilgilerini al
    fetchExchangeRates();

    // Kategori sekmelerini yönet
    const categoryTabs = document.querySelectorAll('.zakat-category-tab');
    const categoryContents = document.querySelectorAll('.zakat-category-content');

    categoryTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Aktif sekmeyi değiştir
            categoryTabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            // İlgili içeriği göster
            const targetCategory = tab.getAttribute('data-category');
            categoryContents.forEach(content => {
                if (content.getAttribute('data-category') === targetCategory) {
                    content.style.display = 'block';
                } else {
                    content.style.display = 'none';
                }
            });
        });
    });

    // Sepete ekleme işlemleri
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    const cartItemsContainer = document.getElementById('cartItems');
    const cartItems = [];

    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const categoryId = this.getAttribute('data-category');
            const name = document.querySelector(`#${categoryId}Name`).value;
            const type = document.querySelector(`#${categoryId}Type`) ? document.querySelector(
                `#${categoryId}Type`).value : '';
            const amount = parseFloat(document.querySelector(`#${categoryId}Amount`).value) ||
                0;

            if (name && amount > 0) {
                // Varlık türüne göre TL değerini hesapla
                let tlValue = amount;
                if (categoryId === 'gold') {
                    tlValue = amount * goldRatePerGram;
                } else if (categoryId === 'silver') {
                    tlValue = amount * silverRatePerGram;
                } else if (categoryId === 'foreign') {
                    // Döviz türüne göre hesapla
                    const currencyType = document.getElementById('foreignType').value;
                    if (currencyType === 'USD') {
                        tlValue = amount * usdRate;
                    } else if (currencyType === 'EUR') {
                        tlValue = amount * eurRate;
                    } else {
                        // Diğer dövizler için şimdilik USD kullan (geliştirilebilir)
                        tlValue = amount * usdRate;
                    }
                } else if (categoryId === 'commercial' || categoryId === 'other' ||
                    categoryId === 'debt') {
                    // Para birimi seçeneğine göre hesapla
                    const currencyType = document.getElementById(`${categoryId}Type`).value;
                    if (currencyType === 'USD') {
                        tlValue = amount * usdRate;
                    } else if (currencyType === 'EUR') {
                        tlValue = amount * eurRate;
                    }
                    // TL ise zaten amount değerini kullanıyoruz
                }

                // Sepete ekle
                const item = {
                    id: Date.now(),
                    name: name,
                    type: type,
                    amount: amount,
                    tlValue: tlValue,
                    category: categoryId
                };

                cartItems.push(item);
                updateCart();

                // Bildirim göster
                showNotification(`"${name}" sepete eklendi.`, 'success');

                // Formu sıfırla
                document.querySelector(`#${categoryId}Amount`).value = '';
            } else {
                showNotification('Lütfen tüm alanları doldurun.', 'danger');
            }
        });
    });

    function updateCart() {
        cartItemsContainer.innerHTML = '';

        if (cartItems.length === 0) {
            cartItemsContainer.innerHTML =
                '<p class="text-center">Henüz sepete eklenen varlık bulunmamaktadır.</p>';
            return;
        }

        let totalAssets = 0;
        let totalDebts = 0;

        cartItems.forEach(item => {
            const itemElement = document.createElement('div');
            itemElement.className = 'cart-item';

            let description = `${item.name}`;
            if (item.type) {
                description += ` (${item.type})`;
            }

            if (item.category === 'debt') {
                totalDebts += item.tlValue;
            } else {
                totalAssets += item.tlValue;
            }

            itemElement.innerHTML = `
                <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                    <div>
                        <strong>${description}</strong>
                        <p class="mb-0 small">${item.amount} ${getUnitByCategory(item.category)} - ${item.tlValue.toLocaleString('tr-TR')} TL</p>
                    </div>
                    <button class="btn btn-sm btn-danger remove-item" data-id="${item.id}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;

            cartItemsContainer.appendChild(itemElement);
        });

        // Sepet özetini ekle
        const zakatableAmount = totalAssets - totalDebts;
        const zakatAmount = zakatableAmount > nisabAmount ? zakatableAmount * 0.025 : 0;

        const summaryElement = document.createElement('div');
        summaryElement.className = 'cart-summary mt-3';
        summaryElement.innerHTML = `
            <div class="p-3 bg-light rounded">
                <p class="d-flex justify-content-between"><strong>Toplam Varlıklar:</strong> <span>${totalAssets.toLocaleString('tr-TR')} TL</span></p>
                <p class="d-flex justify-content-between"><strong>Toplam Borçlar:</strong> <span>${totalDebts.toLocaleString('tr-TR')} TL</span></p>
                <p class="d-flex justify-content-between"><strong>Zekata Tabi Miktar:</strong> <span>${zakatableAmount.toLocaleString('tr-TR')} TL</span></p>
                <hr>
                <p class="d-flex justify-content-between fw-bold"><strong>Ödenecek Zekat:</strong> <span>${zakatAmount.toLocaleString('tr-TR')} TL</span></p>
                
                ${zakatableAmount < nisabAmount ? 
                    `<div class="alert alert-warning mt-2 mb-0">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Varlıklarınız Nisab miktarının (${nisabAmount.toLocaleString('tr-TR')} TL) altında olduğu için zekat vermekle yükümlü değilsiniz.
                    </div>` : ''}
            </div>
        `;

        cartItemsContainer.appendChild(summaryElement);

        // Sepetten kaldırma düğmelerini aktif et
        document.querySelectorAll('.remove-item').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = parseInt(this.getAttribute('data-id'));
                removeItemFromCart(itemId);
            });
        });
    }

    function getUnitByCategory(category) {
        switch (category) {
            case 'tl':
                return 'TL';
            case 'gold':
                return 'Gram';
            case 'silver':
                return 'Gram';
            case 'usd':
                return 'USD';
            case 'eur':
                return 'EUR';
            case 'commercial':
                return 'Birim';
            case 'other':
                return 'Birim';
            case 'debt':
                return 'TL';
            default:
                return '';
        }
    }

    function removeItemFromCart(itemId) {
        const index = cartItems.findIndex(item => item.id === itemId);
        if (index !== -1) {
            const removedItem = cartItems[index];
            cartItems.splice(index, 1);
            updateCart();

            // Bildirim göster
            showNotification(`"${removedItem.name}" sepetten çıkarıldı.`, 'warning');
        }
    }

    // Bildirim gösterme fonksiyonu
    function showNotification(message, type = 'success') {
        // Mevcut bildirimleri temizle
        const existingNotifications = document.querySelectorAll('.notification-toast');
        existingNotifications.forEach(notification => {
            notification.remove();
        });

        // Yeni bildirimi oluştur
        const notification = document.createElement('div');
        notification.className =
            `notification-toast bg-${type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'danger'}`;
        notification.innerHTML = `
            <div class="toast-body text-white">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-circle' : 'times-circle'} me-2"></i>
                ${message}
            </div>
        `;

        // Bildirimi sayfaya ekle
        document.body.appendChild(notification);

        // Bildirimi göster ve 3 saniye sonra kaldır
        setTimeout(() => {
            notification.classList.add('show');

            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    notification.remove();
                }, 500);
            }, 3000);
        }, 100);
    }

    // Hesaplama butonu (eski hesaplama fonksiyonu)
    calculateButton.addEventListener('click', function() {
        // Değerleri al
        const goldValue = parseFloat(document.getElementById('goldValue').value) || 0;
        const silverValue = parseFloat(document.getElementById('silverValue').value) || 0;
        const usdValue = parseFloat(document.getElementById('usdValue').value) || 0;
        const eurValue = parseFloat(document.getElementById('eurValue').value) || 0;
        const cashValue = parseFloat(document.getElementById('cashValue').value) || 0;
        const investmentValue = parseFloat(document.getElementById('investmentValue').value) || 0;
        const otherAssets = parseFloat(document.getElementById('otherAssets').value) || 0;
        const debts = parseFloat(document.getElementById('debts').value) || 0;

        // Döviz ve değerli madenleri TL'ye çevir
        const goldValueInTL = goldValue * goldRatePerGram;
        const silverValueInTL = silverValue * silverRatePerGram;
        const usdValueInTL = usdValue * usdRate;
        const eurValueInTL = eurValue * eurRate;

        // Hesaplama
        const totalAssets = goldValueInTL + silverValueInTL + usdValueInTL + eurValueInTL + cashValue +
            investmentValue + otherAssets;
        const zakatableAmount = totalAssets - debts;

        // Nisab kontrolü
        let zakatAmount = 0;
        if (zakatableAmount >= nisabAmount) {
            zakatAmount = zakatableAmount * 0.025; // %2.5
        }

        // Sonuçları göster
        document.getElementById('totalAssets').textContent = totalAssets.toLocaleString('tr-TR') +
            ' TL';
        document.getElementById('totalDebts').textContent = debts.toLocaleString('tr-TR') + ' TL';
        document.getElementById('zakatableAmount').textContent = zakatableAmount.toLocaleString(
            'tr-TR') + ' TL';
        document.getElementById('zakatAmount').textContent = zakatAmount.toLocaleString('tr-TR') +
            ' TL';

        // Nisab uyarısını göster/gizle
        if (zakatableAmount < nisabAmount) {
            nisabWarning.style.display = 'block';
        } else {
            nisabWarning.style.display = 'none';
        }

        // Sonuç bölümünü göster
        resultDiv.style.display = 'block';

        // Sonuç bölümüne kaydır
        resultDiv.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    });

    // Input alanlarında Enter tuşu ile hesaplama
    const inputFields = document.querySelectorAll('#zakatCalculatorForm input');
    inputFields.forEach(input => {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                calculateButton.click();
            }
        });
    });
});
</script>