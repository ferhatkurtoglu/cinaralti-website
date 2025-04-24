<!-- Donate -->

<div class="inner_banner-section">
    <h3 class="inner_banner-title">Bağış</h3>
</div>

<!-- Bağış Kategorileri Menüsü -->
<div class="donate-categories-menu">
    <div class="container">
        <div class="donate-categories-wrapper">
            <?php
            if (DEBUG_MODE) {
                echo '<div style="padding: 10px; background-color: #f8d7da; color: #721c24; margin-bottom: 10px; border-radius: 5px;">Debug Modu Aktif - Veritabanı bağlantısı test ediliyor</div>';
            }
            
            // Aktif kategoriyi belirle
            $activeCategory = isset($_GET['category']) ? $_GET['category'] : 'acil-yardim';
            $activeCurrency = isset($_GET['currency']) ? $_GET['currency'] : '';
            
            try {
                // Veritabanı bağlantısı
                $db = db_connect();
                
                if (DEBUG_MODE) {
                    echo '<div style="padding: 10px; background-color: #d4edda; color: #155724; margin-bottom: 10px; border-radius: 5px;">Veritabanı bağlantısı başarılı</div>';
                }
                
                // Kategorileri veritabanından çek (tekrarları gruplandırarak)
                $stmt = $db->prepare("
                    SELECT id, name, slug, position 
                    FROM donation_categories 
                    WHERE active = 1
                    GROUP BY slug
                    ORDER BY position ASC
                ");
                $stmt->execute();
                $categories = $stmt->fetchAll();
                
                if (DEBUG_MODE) {
                    echo '<div style="padding: 10px; background-color: #d4edda; color: #155724; margin-bottom: 10px; border-radius: 5px;">' . count($categories) . ' kategori bulundu</div>';
                }
                
                // Kategorileri göster
                foreach ($categories as $category) {
                    $iconMap = [
                        'acil-yardim' => '🆘',
                        'zekat' => '💰',
                        'egitim' => '📚',
                        'yetim' => '👶',
                        'su-kuyusu' => '💧',
                        'genel' => '❤️',
                        'projeler' => '🌱',
                        'kurban' => '🐑'
                    ];
                    
                    $icon = isset($iconMap[$category['slug']]) ? $iconMap[$category['slug']] : '📦';
                    
                    $isActive = ($category['slug'] == $activeCategory) ? 'active' : '';
                    echo '<a href="'. BASE_URL .'/donate?category='. $category['slug'] .'" class="donate-category-item '. $isActive .'">';
                    echo '<span class="category-icon">'. $icon .'</span>';
                    echo '<span class="category-text">'. $category['name'] .'</span>';
                    echo '</a>';
                }
                
            } catch (Exception $e) {
                // Hata durumunda statik kategori listesi gösterilir
                if (DEBUG_MODE) {
                    echo '<div style="padding: 10px; background-color: #f8d7da; color: #721c24; margin-bottom: 10px; border-radius: 5px;">Veritabanı hatası: ' . $e->getMessage() . '</div>';
                }
                
                $staticCategories = [
                    'acil-yardim' => ['icon' => '🆘', 'text' => 'Acil Yardım'],
                    'yetim' => ['icon' => '👶', 'text' => 'Yetim'],
                    'genel' => ['icon' => '❤️', 'text' => 'Genel'],
                    'projeler' => ['icon' => '🌱', 'text' => 'Projeler'],
                    'egitim' => ['icon' => '📚', 'text' => 'Eğitim'],
                    'kurban' => ['icon' => '🐑', 'text' => 'Kurban']
                ];
                
                foreach ($staticCategories as $slug => $info) {
                    $isActive = ($slug == $activeCategory) ? 'active' : '';
                    echo '<a href="'. BASE_URL .'/donate?category='. $slug .'" class="donate-category-item '. $isActive .'">';
                    echo '<span class="category-icon">'. $info['icon'] .'</span>';
                    echo '<span class="category-text">'. $info['text'] .'</span>';
                    echo '</a>';
                }
                
                if (DEBUG_MODE) {
                    error_log("Kategori çekme hatası: " . $e->getMessage());
                }
            }
            
            // Para birimi seçeneği
            $currencyActive = (!empty($activeCurrency)) ? 'active' : '';
            ?>
            <a href="#" class="donate-category-item currency-item <?= $currencyActive ?>" id="currencySettingsBtn">
                <span class="category-icon">💰</span>
                <span class="category-text">₺ (Türk Lirası)</span>
            </a>
        </div>
    </div>
</div>

<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Donate : Main Section 
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
<div class="donate_main-section">
    <div class="container">
        <div class="row justify-content-center gx-4 gy-4">
            <?php
            try {
                $db = db_connect();
                $donationItems = [];
                
                if (DEBUG_MODE) {
                    echo '<div style="padding: 10px; background-color: #d4edda; color: #155724; margin-bottom: 10px; border-radius: 5px;">Bağış türleri için veritabanı bağlantısı başarılı</div>';
                }
                
                // Aktif kategoriyi veritabanındaki id'ye çevir
                $categorySlug = isset($_GET['category']) ? $_GET['category'] : 'acil-yardim';
                
                $stmt = $db->prepare("
                    SELECT id
                    FROM donation_categories 
                    WHERE slug = ? AND active = 1
                    GROUP BY slug
                    LIMIT 1
                ");
                $stmt->execute([$categorySlug]);
                $category = $stmt->fetch();
                $categoryId = $category ? $category['id'] : 0;
                
                if (DEBUG_MODE) {
                    echo '<div style="padding: 10px; background-color: #cce5ff; color: #004085; margin-bottom: 10px; border-radius: 5px;">Seçili kategori: ' . $categorySlug . ', ID: ' . $categoryId . '</div>';
                }
                
                // Donations tablosundan bağış öğelerini çek
                if ($categoryId > 0) {
                    $stmt = $db->prepare("
                        SELECT 
                            id, 
                            title, 
                            slug, 
                            description, 
                            main_image,
                            target_amount,
                            collected_amount
                        FROM 
                            donations
                        WHERE 
                            category_id = ? AND active = 1
                        GROUP BY 
                            slug
                        ORDER BY
                            position ASC
                    ");
                    $stmt->execute([$categoryId]);
                    $donationItems = $stmt->fetchAll();
                    
                    if (DEBUG_MODE) {
                        echo '<div style="padding: 10px; background-color: #cce5ff; color: #004085; margin-bottom: 10px; border-radius: 5px;">Kategoriye göre ' . count($donationItems) . ' bağış türü bulundu</div>';
                    }
                }
                
                // Eğer hiç bağış öğesi bulunamadıysa, tüm aktif bağışları getir
                if (empty($donationItems)) {
                    $stmt = $db->prepare("
                        SELECT 
                            id, 
                            title, 
                            slug, 
                            description, 
                            main_image,
                            target_amount,
                            collected_amount
                        FROM 
                            donations
                        WHERE 
                            active = 1
                        GROUP BY
                            slug
                        ORDER BY
                            position ASC
                    ");
                    $stmt->execute();
                    $donationItems = $stmt->fetchAll();
                    
                    if (DEBUG_MODE) {
                        echo '<div style="padding: 10px; background-color: #cce5ff; color: #004085; margin-bottom: 10px; border-radius: 5px;">Tüm kategorilerden ' . count($donationItems) . ' bağış türü bulundu</div>';
                    }
                }
                
                // Eğer veritabanında bağış türü yoksa, JSON dosyasından yükle
                if (empty($donationItems)) {
                    if (DEBUG_MODE) {
                        echo '<div style="padding: 10px; background-color: #fff3cd; color: #856404; margin-bottom: 10px; border-radius: 5px;">Veritabanında bağış türü bulunamadı. JSON dosyasından yükleniyor...</div>';
                    }
                    
                    $jsonFile = file_get_contents(__DIR__ . "/../public/data/donations.json");
                    $jsonData = json_decode($jsonFile, true);
                    
                    if ($jsonData) {
                        // Filtrele ve donationItems formatına dönüştür
                        $filteredData = array_filter($jsonData, function($item) use ($categorySlug) {
                            return in_array($categorySlug, $item['categories']);
                        });
                        
                        // Kategori filtrelemesinden sonuç gelmezse tüm verileri göster
                        if (empty($filteredData) && $categorySlug != 'all') {
                            $filteredData = $jsonData;
                        }
                        
                        $donationItems = array_map(function($item) {
                            return [
                                'id' => $item['id'],
                                'title' => $item['title'],
                                'slug' => $item['slug'],
                                'description' => $item['description'],
                                'main_image' => $item['main_image'],
                                'target_amount' => $item['target_amount'],
                                'collected_amount' => $item['collected_amount']
                            ];
                        }, $filteredData);
                        
                        if (DEBUG_MODE) {
                            echo '<div style="padding: 10px; background-color: #fff3cd; color: #856404; margin-bottom: 10px; border-radius: 5px;">JSON dosyasından ' . count($donationItems) . ' bağış türü yüklendi</div>';
                        }
                    }
                }
                
                // Her bir bağış kartını oluştur
                if (count($donationItems) > 0) {
                    foreach ($donationItems as $donation) {
                        // Resim yolunu düzelt
                        $imageUrl = !empty($donation['main_image']) ? $donation['main_image'] : "../public/assets/image/donate/donate1.jpg";
                        if (strpos($imageUrl, 'http') !== 0 && strpos($imageUrl, '../') !== 0) {
                            $imageUrl = "../public/" . ltrim($imageUrl, '/');
                        }
                        
                        // URL'yi oluştur
                        $url = BASE_URL . "/donate-details?type=" . urlencode($donation['slug']);
                        
                        // İlerleme yüzdesini hesapla
                        $progressPercent = 0;
                        if ($donation['target_amount'] > 0) {
                            $progressPercent = min(100, ($donation['collected_amount'] / $donation['target_amount']) * 100);
                        }
                        ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="donate-new-card">
                                <div class="donate-new-card-image">
                                    <a href="<?= $url ?>" class="donate-new-card-link">
                                        <img src="<?= $imageUrl ?>" alt="<?= $donation['title'] ?>">
                                        <?php if ($donation['target_amount'] > 0): ?>
                                        <div class="donation-progress-bar">
                                            <div class="donation-progress" style="width: <?= $progressPercent ?>%;"></div>
                                        </div>
                                        <div class="donation-stats">
                                            <span class="donation-collected"><?= number_format($donation['collected_amount'], 0, ',', '.') ?> ₺</span>
                                            <span class="donation-target"><?= number_format($donation['target_amount'], 0, ',', '.') ?> ₺</span>
                                        </div>
                                        <?php endif; ?>
                                    </a>
                                </div>
                                <div class="donate-new-card-body">
                                    <a href="<?= $url ?>" class="donate-new-card-link">
                                        <h3 class="donate-new-card-title"><?= $donation['title'] ?></h3>
                                    </a>
                                    <div class="donate-new-card-price">
                                        <input type="text" class="donate-card-price-input" placeholder="₺0">
                                    </div>
                                    <div class="donate-new-card-button-wrapper">
                                        <button class="donate-new-card-button" onclick="openDonateModal('<?= $donation['title'] ?>', this)">Bağış Yap</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<div class="col-12 text-center"><p>Bu kategoride bağış bulunamadı.</p></div>';
                }
            } catch (Exception $e) {
                echo '<div class="col-12 text-center"><p>Bağış verileri yüklenemedi: ' . $e->getMessage() . '</p></div>';
                if (DEBUG_MODE) {
                    echo '<div style="padding: 10px; background-color: #f8d7da; color: #721c24; margin-bottom: 10px; border-radius: 5px;">Hata detayı: ' . $e->getTraceAsString() . '</div>';
                    error_log("Bağış verileri hatası: " . $e->getMessage() . " - " . $e->getTraceAsString());
                }
            }
            ?>
        </div>
    </div>
</div>

<!-- Bağış Modal -->
<div class="modal fade" id="donateModal" tabindex="-1" aria-labelledby="donateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="donate-form">
                    <div class="donate-form__header">
                        <span class="donate-form__category"></span>
                        <h2 class="donate-form__title" id="donateModalLabel"></h2>
                    </div>
                    
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
                        <div class="donate-form__field phone-field">
                            <div class="country-select">
                                <div class="country-select-toggle">
                                    <span class="country-flag">🇹🇷</span>
                                    <span class="country-code">+90</span>
                                    <span class="dropdown-arrow">▼</span>
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
                            <input type="tel" placeholder="Telefon" />
                        </div>
                        <div class="donate-form__field">
                            <span class="field-icon">✉️</span>
                            <input type="email" placeholder="E-Posta" />
                        </div>
                    </div>

                    <div class="donate-form__buttons">
                        <button type="button" class="modal-btn-donate">
                            <span class="btn-icon">❤️</span>
                            Bağış Yap
                        </button>
                        <button type="button" class="modal-btn-cart">
                            <span class="btn-icon">🛒</span>
                            Sepete Ekle
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dil ve Para Birimi Tercihleri Modal -->
<div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="settingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="settingsModalLabel">Tercihlerinizi yönetin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="settings-description">
                    Dil ve Kur tercihlerini sitenin en alt bölümünden dilediğiniz zaman değiştirebilirsiniz.
                </p>
                
                <div class="settings-container">
                    <div class="settings-column">
                        <h6 class="settings-title">Dil Tercihi</h6>
                        <div class="settings-options">
                            <label class="settings-option">
                                <input type="radio" name="language" value="tr" checked>
                                <span class="settings-option-circle"></span>
                                <span class="settings-option-text">Türkçe (TR)</span>
                            </label>
                            <label class="settings-option">
                                <input type="radio" name="language" value="en">
                                <span class="settings-option-circle"></span>
                                <span class="settings-option-text">English (EN)</span>
                            </label>
                            <label class="settings-option">
                                <input type="radio" name="language" value="ar">
                                <span class="settings-option-circle"></span>
                                <span class="settings-option-text">العربية (AR)</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="settings-column">
                        <h6 class="settings-title">Para Birimi</h6>
                        <div class="settings-options">
                            <label class="settings-option">
                                <input type="radio" name="currency" value="try" checked>
                                <span class="settings-option-circle"></span>
                                <span class="settings-option-text">₺ Türk Lirası</span>
                            </label>
                            <label class="settings-option">
                                <input type="radio" name="currency" value="usd">
                                <span class="settings-option-circle"></span>
                                <span class="settings-option-text">$ Dolar</span>
                            </label>
                            <label class="settings-option">
                                <input type="radio" name="currency" value="eur">
                                <span class="settings-option-circle"></span>
                                <span class="settings-option-text">€ Euro</span>
                            </label>
                            <label class="settings-option">
                                <input type="radio" name="currency" value="gbp">
                                <span class="settings-option-circle"></span>
                                <span class="settings-option-text">£ Sterlin</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="settings-continue-btn" id="saveSettingsBtn">Siteye devam et</button>
            </div>
        </div>
    </div>
</div>

<style>
    .modal-content {
        border-radius: 20px;
        border: none;
        padding: 20px;
    }

    /* Kategori menüsü stili */
    .donate-categories-menu {
        margin: 5px 0 50px;
        background-color: #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 8px 0;
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .donate-categories-wrapper {
        display: flex;
        justify-content: space-around;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .donate-category-item {
        display: flex;
        flex-direction: row;
        align-items: center;
        text-decoration: none;
        color: #555;
        padding: 6px 12px;
        border-radius: 8px;
        transition: all 0.3s ease;
        text-align: center;
    }

    .donate-category-item:hover {
        background-color: #f5f9f5;
        color: #28a745;
        font-weight: 600;
        transform: translateY(-2px);
    }
    
    .donate-category-item.active {
        background-color: #e8f5e9;
        color: #1b5e20;
        font-weight: 600;
        transform: translateY(-2px);
        box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        border: 1px solid #4CAF50;
    }

    .category-icon {
        font-size: 20px;
        margin-right: 8px;
        margin-bottom: 0;
    }

    .category-text {
        font-size: 14px;
        white-space: nowrap;
        font-weight: 500;
    }

    .currency-item {
        border-left: 1px solid #eee;
        padding-left: 15px;
        margin-left: 5px;
    }

    /* Mobil görünüm için medya sorgusu */
    @media (max-width: 768px) {
        .donate-categories-wrapper {
            justify-content: flex-start;
            overflow-x: auto;
            padding: 10px 15px;
            flex-wrap: nowrap;
        }

        .donate-category-item {
            min-width: 110px;
            justify-content: center;
        }

        .category-icon {
            margin-right: 5px;
        }

        .category-text {
            font-size: 12px;
        }

        .currency-item {
            border-left: none;
            padding-left: 15px;
            margin-left: 0;
        }
    }

    /* Donate ana bölümü için margin ekleme */
    .donate_main-section {
        margin-bottom: 80px;
        padding: 0;
    }

    .modal-header {
        border: none;
        padding: 0;
    }

    .btn-close {
        position: absolute;
        right: 20px;
        top: 20px;
    }

    .donate-form {
        padding: 10px;
    }

    .donate-form__header {
        margin-bottom: 30px;
    }

    .donate-form__category {
        color: #dc3545;
        font-size: 16px;
        font-weight: 500;
        display: block;
        margin-bottom: 5px;
    }

    .donate-form__title {
        font-size: 32px;
        font-weight: 700;
        margin: 0;
        color: #000;
    }

    .donate-form__label {
        color: #6c757d;
        font-size: 14px;
        margin-bottom: 8px;
        display: block;
    }

    .donate-form__input {
        width: 100%;
        padding: 15px;
        font-size: 28px;
        border: none;
        border-bottom: 2px solid #28a745;
        font-weight: 500;
        margin-bottom: 30px;
        background: transparent;
        color: inherit;
    }

    .donate-form__input:focus {
        outline: none;
    }

    .donate-form__input::placeholder {
        color: #adb5bd;
        font-weight: 500;
    }

    .donate-form__options {
        display: flex;
        gap: 40px;
        margin-bottom: 30px;
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
        width: 24px;
        height: 24px;
        border: 2px solid #28a745;
        border-radius: 50%;
        margin-right: 10px;
        position: relative;
    }

    .donate-radio input:checked + .donate-radio__mark::after {
        content: '';
        position: absolute;
        width: 14px;
        height: 14px;
        background: #28a745;
        border-radius: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .donate-radio__text {
        font-size: 16px;
        color: #212529;
    }

    .donate-form__type {
        display: flex;
        background: #f8f9fa;
        border-radius: 10px;
        padding: 5px;
        margin-bottom: 30px;
    }

    .donate-type-btn {
        flex: 1;
        padding: 10px;
        border: none;
        background: none;
        border-radius: 8px;
        font-size: 15px;
        color: #495057;
        cursor: pointer;
    }

    .donate-type-btn.active {
        background: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        color: #212529;
        font-weight: 500;
    }

    .donate-form__fields {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-bottom: 30px;
    }

    .donate-form__field {
        display: flex;
        align-items: center;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 12px 15px;
    }

    .field-icon {
        margin-right: 10px;
        font-size: 20px;
    }

    .donate-form__field input {
        border: none;
        width: 100%;
        font-size: 15px;
        background: transparent;
        color: inherit;
    }

    .donate-form__field input:focus {
        outline: none;
    }

    .donate-form__buttons {
        display: flex;
        gap: 15px;
    }

    .modal-btn-donate, .modal-btn-cart {
        flex: 1;
        padding: 15px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
    }

    .modal-btn-donate {
        background: #28a745;
        color: #fff;
    }

    .modal-btn-cart {
        background: #e9ecef;
        color: #212529;
    }

    .btn-icon {
        font-size: 18px;
    }

    /* Bağış input alanı ve yan butonu için stiller düzenlendi */
    .donate-card__input-group {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
        margin-top: auto;
        margin-bottom: 20px;
    }

    .donate-card__input-group .input-group {
        max-width: 80px;
    }

    .donate-card__input-group .form-control {
        font-size: 14px;
        padding: 8px;
        height: auto;
        text-align: center;
    }

    /* Tüm bağış kartları için ortak stiller */
    .donate-card {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        padding: 0;
        margin-bottom: 0;
    }

    /* Dünya haritası içeren özel kart için stiller */
    .world-map-container {
        position: relative;
        width: 100%;
        height: 0;
        padding-bottom: 50%; /* Üst kısmın kartın %50'sini kaplaması için */
        overflow: hidden;
    }

    .world-map {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.15;
        position: absolute;
        top: 0;
        left: 0;
    }

    .map-hole {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 55%;
        height: 70%;
        overflow: hidden;
        z-index: 2;
        border-radius: 40% 45% 40% 35%;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        border: 5px solid #fff;
        background-color: #fff;
    }

    .hole-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Normal resim içeren kartlar için stiller */
    .donate-card-img-container {
        position: relative;
        width: 100%;
        height: 0;
        padding-bottom: 50%; /* Üst kısmın kartın %50'sini kaplaması için */
        overflow: hidden;
    }

    .donate-card-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        top: 0;
        left: 0;
    }

    /* Kart içeriği için stiller */
    .donate-card-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 30px 25px;
        text-align: center;
        flex-grow: 1;
    }

    .donate-card-title {
        font-size: 24px;
        font-weight: 700;
        color: #333;
        margin-bottom: 20px;
    }

    .donate-card-price-wrapper {
        width: 100%;
        margin-bottom: 25px;
    }

    .donate-card-price-input {
        font-size: 36px;
        font-weight: 600;
        color: #333;
        text-align: center;
        width: 100%;
        padding: 8px;
        border: none;
        border-bottom: 2px solid #4CAF50;
        background: transparent;
    }

    .donate-card-price-input:focus {
        outline: none;
    }

    .donate-card-button {
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 15px 30px;
        font-size: 18px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.3s;
        width: 100%;
    }

    .donate-card-button:hover {
        background-color: #3b9c40;
    }

    /* Eski stilleri gizleme */
    .donate-card__map-container,
    .donate-card__map,
    .donate-card__map-content,
    .donate-card__title-overlay,
    .donate-card__price,
    .donate-card__button-overlay,
    .donate-card__img,
    .donate-card_body,
    .donate-card__title,
    .donate-card__input-group,
    .donate-card__button {
        display: none;
    }

    /* Yeni bağış kartları için stiller */
    .donate-new-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        height: 100%;
        width: 100%;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .donate-new-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12);
    }
    
    .donate-new-card-link {
        text-decoration: none;
        color: inherit;
        display: block;
    }
    
    .donate-new-card-title {
        font-size: 24px;
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
        transition: color 0.3s ease;
    }
    
    .donate-new-card-link:hover .donate-new-card-title {
        color: #28a745;
    }
    
    .donate-new-card-image {
        width: 100%;
        height: 0;
        padding-bottom: 50%;
        position: relative;
        overflow: hidden;
        background-color: #f8f8f8;
    }

    .donate-new-card-image img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .donate-new-card-body {
        padding: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .donate-new-card-price {
        width: 100%;
        padding: 5px 0;
        margin-bottom: 25px;
        position: relative;
    }

    .donate-new-card-price::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 100%;
        height: 2px;
        background-color: #4CAF50;
    }

    .donate-card-price-input {
        font-size: 32px;
        font-weight: 600;
        color: #333;
        text-align: center;
        width: 100%;
        padding: 5px;
        border: none;
        background: transparent;
    }

    .donate-card-price-input:focus {
        outline: none;
    }

    .donate-new-card-button-wrapper {
        width: 100%;
    }

    .donate-new-card-button {
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 12px 0;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.3s;
        width: 100%;
    }

    .donate-new-card-button:hover {
        background-color: #3b9c40;
    }

    /* Ülke seçimi dropdown için stiller */
    .phone-field {
        position: relative;
    }

    .country-select {
        display: flex;
        align-items: center;
        position: relative;
    }

    .country-select-toggle {
        display: flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
        padding: 5px;
        border-right: 1px solid #dee2e6;
        margin-right: 10px;
    }

    .dropdown-arrow {
        font-size: 10px;
        color: #6c757d;
        margin-left: 3px;
    }

    .country-select-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        width: 300px;
        max-height: 300px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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

    .country-item:hover, .country-item.active {
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

    /* Tercihler Modalı Stilleri */
    #settingsModal .modal-content {
        border-radius: 20px;
        padding: 20px;
    }
    
    #settingsModal .modal-header {
        border: none;
        padding-bottom: 10px;
    }
    
    #settingsModal .modal-title {
        font-size: 28px;
        font-weight: 600;
    }
    
    .settings-description {
        font-size: 18px;
        line-height: 1.5;
        color: #444;
        margin-bottom: 30px;
    }
    
    .settings-container {
        display: flex;
        gap: 30px;
    }
    
    .settings-column {
        flex: 1;
    }
    
    .settings-title {
        font-size: 20px;
        font-weight: 500;
        margin-bottom: 20px;
        color: #333;
    }
    
    .settings-options {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .settings-option {
        display: flex;
        align-items: center;
        cursor: pointer;
        padding: 5px;
    }
    
    .settings-option input {
        display: none;
    }
    
    .settings-option-circle {
        width: 24px;
        height: 24px;
        border: 2px solid #4CAF50;
        border-radius: 50%;
        margin-right: 12px;
        position: relative;
    }
    
    .settings-option input:checked + .settings-option-circle:after {
        content: '';
        position: absolute;
        width: 14px;
        height: 14px;
        background-color: #4CAF50;
        border-radius: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    
    .settings-option-text {
        font-size: 16px;
        color: #333;
    }
    
    .settings-continue-btn {
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 12px 24px;
        font-size: 18px;
        font-weight: 500;
        width: 100%;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    
    .settings-continue-btn:hover {
        background-color: #3b9c40;
    }
    
    /* Mobil Görünüm */
    @media (max-width: 768px) {
        .settings-container {
            flex-direction: column;
            gap: 20px;
        }
        
        #settingsModal .modal-title {
            font-size: 24px;
        }
        
        .settings-description {
            font-size: 16px;
        }
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

    /* İlerleme çubuğu stilleri */
    .donation-progress-bar {
        position: absolute;
        bottom: 30px;
        left: 10px;
        right: 10px;
        height: 10px;
        background-color: rgba(255, 255, 255, 0.7);
        border-radius: 5px;
        overflow: hidden;
    }
    
    .donation-progress {
        height: 100%;
        background-color: #4CAF50;
        border-radius: 5px;
    }
    
    .donation-stats {
        position: absolute;
        bottom: 10px;
        left: 10px;
        right: 10px;
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        color: white;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8);
    }
    
    .donation-collected {
        font-weight: bold;
    }
    
    .donation-target {
        opacity: 0.8;
    }
</style>

<script>
    function openDonateModal(title, buttonElement) {
        // Stop event propagation to prevent navigating to donate-details page
        event.stopPropagation();
        
        document.getElementById('donateModalLabel').textContent = title;
        
        // Karttaki input değerini al
        const cardInput = buttonElement.closest('.donate-new-card-body').querySelector('.donate-card-price-input');
        const donateAmount = cardInput.value || '₺0';
        
        // Modalı aç
        var modal = new bootstrap.Modal(document.getElementById('donateModal'));
        modal.show();
        
        // Modal açıldıktan sonra modal içindeki input'a değeri aktar
        document.querySelector('.donate-form__input').value = donateAmount;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Para formatı için input işleyici
        const donateInputs = document.querySelectorAll('.donate-form__input, .donate-card-price-input');
        
        donateInputs.forEach(input => {
            input.addEventListener('input', function(e) {
                // Sadece sayı ve son ₺ sembolünü al
                let value = e.target.value.replace(/[^\d]/g, '');
                
                if (value) {
                    // 0 ile başlayan sayıları düzelt
                    value = parseInt(value, 10);
                    
                    // Türk Lirası formatı (1.000,00 ₺)
                    value = value.toLocaleString('tr-TR');
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
                if (e.target.value === '₺' || e.target.value === '') {
                    e.target.value = '₺0';
                }
            });
        });

        // Bağış kartındaki input alanlarının event propagation'ı engelleme
        const cardInputs = document.querySelectorAll('.donate-card-price-input');
        cardInputs.forEach(input => {
            input.addEventListener('click', function(e) {
                e.stopPropagation();
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
        document.querySelector('.modal-btn-donate').addEventListener('click', function() {
            // Bağış bilgilerini al
            const donationTitle = document.getElementById('donateModalLabel').textContent;
            const donationAmount = document.querySelector('.donate-form__input').value;
            const donationType = document.querySelector('input[name="donateType"]:checked').nextElementSibling.nextElementSibling.textContent;
            const donorName = document.querySelector('.donate-form__field input[placeholder="Ad Soyad"]').value;
            const donorPhone = document.querySelector('.donate-form__field input[placeholder="Telefon"]').value;
            const donorEmail = document.querySelector('.donate-form__field input[placeholder="E-Posta"]').value;
            const donationType2 = document.querySelector('.donate-form__type .donate-type-btn.active').textContent;
            
            // Sepete ekle
            addToCart({
                title: donationTitle,
                amount: donationAmount,
                donationType: donationType,
                donorName: donorName,
                donorPhone: donorPhone,
                donorEmail: donorEmail,
                donorType: donationType2,
                image: document.querySelector('.donate-new-card-image img[alt="' + donationTitle + '"]')?.src || '../public/assets/image/donate/donate1.jpg'
            });
            
            // Sepet sayfasına git
            window.location.href = '<?= BASE_URL ?>/cart';
        });

        // Sepete Ekle butonuna tıklama
        document.querySelector('.modal-btn-cart').addEventListener('click', function() {
            // Bağış bilgilerini al
            const donationTitle = document.getElementById('donateModalLabel').textContent;
            const donationAmount = document.querySelector('.donate-form__input').value;
            const donationType = document.querySelector('input[name="donateType"]:checked').nextElementSibling.nextElementSibling.textContent;
            const donorName = document.querySelector('.donate-form__field input[placeholder="Ad Soyad"]').value;
            const donorPhone = document.querySelector('.donate-form__field input[placeholder="Telefon"]').value;
            const donorEmail = document.querySelector('.donate-form__field input[placeholder="E-Posta"]').value;
            const donationType2 = document.querySelector('.donate-form__type .donate-type-btn.active').textContent;
            
            // Sepete ekle
            addToCart({
                title: donationTitle,
                amount: donationAmount,
                donationType: donationType,
                donorName: donorName,
                donorPhone: donorPhone,
                donorEmail: donorEmail,
                donorType: donationType2,
                image: document.querySelector('.donate-new-card-image img[alt="' + donationTitle + '"]')?.src || '../public/assets/image/donate/donate1.jpg'
            });
            
            // Bildirim göster
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
            }, 5000); // Süreyi 5 saniyeye çıkardım, kullanıcının butona tıklaması için daha fazla zaman
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
        
        // Dil ve Para Birimi Modal İşlemleri
        const currencySettingsBtn = document.getElementById('currencySettingsBtn');
        const settingsModal = new bootstrap.Modal(document.getElementById('settingsModal'));
        const saveSettingsBtn = document.getElementById('saveSettingsBtn');
        
        // Türk Lirası butonuna tıklama
        if (currencySettingsBtn) {
            currencySettingsBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Kayıtlı tercihleri yükle
                loadUserPreferences();
                
                // Modalı aç
                settingsModal.show();
            });
        }
        
        // Tercihleri kaydet ve modalı kapat
        if (saveSettingsBtn) {
            saveSettingsBtn.addEventListener('click', function() {
                saveUserPreferences();
                settingsModal.hide();
                
                // Sayfayı tekrar yükle veya tercihleri uygula
                applyUserPreferences();
            });
        }
        
        // Kullanıcı tercihlerini yükleyen fonksiyon
        function loadUserPreferences() {
            // LocalStorage'dan tercihleri al
            const savedLang = localStorage.getItem('userLanguage') || 'tr';
            const savedCurrency = localStorage.getItem('userCurrency') || 'try';
            
            // Dil seçimini ayarla
            const langRadios = document.querySelectorAll('input[name="language"]');
            langRadios.forEach(radio => {
                if (radio.value === savedLang) {
                    radio.checked = true;
                }
            });
            
            // Para birimi seçimini ayarla
            const currencyRadios = document.querySelectorAll('input[name="currency"]');
            currencyRadios.forEach(radio => {
                if (radio.value === savedCurrency) {
                    radio.checked = true;
                }
            });
        }
        
        // Kullanıcı tercihlerini kaydeden fonksiyon
        function saveUserPreferences() {
            // Seçili dili al
            const selectedLang = document.querySelector('input[name="language"]:checked').value;
            
            // Seçili para birimini al
            const selectedCurrency = document.querySelector('input[name="currency"]:checked').value;
            
            // LocalStorage'a kaydet
            localStorage.setItem('userLanguage', selectedLang);
            localStorage.setItem('userCurrency', selectedCurrency);
            
            // Cookie olarak da kaydet (opsiyonel)
            setCookie('userLanguage', selectedLang, 30);
            setCookie('userCurrency', selectedCurrency, 30);
        }
        
        // Tercihleri uygulayan fonksiyon
        function applyUserPreferences() {
            const selectedLang = localStorage.getItem('userLanguage');
            const selectedCurrency = localStorage.getItem('userCurrency');
            
            // Para birimi göstergesini güncelle
            let currencySymbol = '₺';
            let currencyName = 'Türk Lirası';
            
            switch (selectedCurrency) {
                case 'usd':
                    currencySymbol = '$';
                    currencyName = 'Dolar';
                    break;
                case 'eur':
                    currencySymbol = '€';
                    currencyName = 'Euro';
                    break;
                case 'gbp':
                    currencySymbol = '£';
                    currencyName = 'Sterlin';
                    break;
                default:
                    currencySymbol = '₺';
                    currencyName = 'Türk Lirası';
            }
            
            // Para birimi butonunu güncelle
            document.querySelector('#currencySettingsBtn .category-text').textContent = 
                `${currencySymbol} (${currencyName})`;
                
            // Bağış input alanlarını güncelle
            const priceInputs = document.querySelectorAll('.donate-card-price-input');
            priceInputs.forEach(input => {
                const currentValue = input.value.replace(/[^\d]/g, '');
                if (currentValue) {
                    const numValue = parseInt(currentValue, 10);
                    input.value = `${currencySymbol}${numValue.toLocaleString()}`;
                } else {
                    input.value = `${currencySymbol}0`;
                }
            });
            
            // Eğer dil değiştiyse ve yeniden yükleme gerekiyorsa
            if (selectedLang && selectedLang !== '<?= isset($_COOKIE["userLanguage"]) ? $_COOKIE["userLanguage"] : "tr" ?>') {
                // Dil değişimini sunucuya bildir ve sayfayı yeniden yükle
                // Bu bölüm sunucu taraflı dil değişimi için ayarlanmalıdır
                // window.location.reload();
            }
        }
        
        // Cookie oluşturan yardımcı fonksiyon
        function setCookie(name, value, days) {
            let expires = "";
            if (days) {
                const date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "")  + expires + "; path=/";
        }
        
        // Sayfa yüklendiğinde tercihleri uygula
        loadUserPreferences();
        applyUserPreferences();

        // Ülke listesini JSON'dan yükleme
        loadCountries();

        // Ülke seçme dropdown işlevselliği
        const countrySelect = document.querySelector('.country-select');
        const countryToggle = document.querySelector('.country-select-toggle');
        const countryDropdown = document.querySelector('.country-select-dropdown');
        const countrySearchInput = document.querySelector('.country-search-input');
        
        if (countryToggle && countryDropdown && countrySearchInput) {
            // Dropdown'u açma/kapama
            countryToggle.addEventListener('click', function(e) {
                e.stopPropagation(); // Bu önemli, tıklama event'inin belgeye yayılmasını durdurur
                countrySelect.classList.toggle('active');
                // Dropdown açılınca arama kutusuna odaklan
                if (countrySelect.classList.contains('active')) {
                    setTimeout(() => {
                        countrySearchInput.focus();
                    }, 100);
                }
            });

            // Dropdown içindeki tıklamaların dışarı yayılmasını engelle
            countryDropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            // Dropdown dışına tıklayınca kapatma
            document.addEventListener('click', function(e) {
                if (!countrySelect.contains(e.target)) {
                    countrySelect.classList.remove('active');
                }
            });

            // Ülke arama işlevi
            countrySearchInput.addEventListener('input', function() {
                const searchValue = this.value.toLowerCase();
                
                const countryItems = document.querySelectorAll('.country-item');
                countryItems.forEach(item => {
                    const countryName = item.getAttribute('data-country').toLowerCase();
                    
                    if (countryName.includes(searchValue)) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }
    });

    // Ülke listesini JSON'dan yükleme
    function loadCountries() {
        // Burada BASE_URL'yi düzgün bir şekilde oluşturup ekleyelim ve hata yakalama eklyelim
        let baseUrl = '';
        try {
            // BASE_URL değişkenine erişmeye çalış
            baseUrl = '<?= BASE_URL ?>';
            // Eğer değişken boşsa veya undefined ise, tahmini bir URL oluştur
            if (!baseUrl || baseUrl === '<?= BASE_URL ?>') {
                const currentUrl = window.location.href;
                const urlParts = currentUrl.split('/');
                baseUrl = urlParts[0] + '//' + urlParts[2]; // protocol + hostname
            }
        } catch (e) {
            console.error('BASE_URL alınamadı:', e);
            baseUrl = window.location.origin; // En azından origin alabilelim
        }
        
        fetch(baseUrl + '/public/data/countries.json')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Ülke listesi yüklenemedi: ' + response.status);
                }
                return response.json();
            })
            .then(countries => {
                if (!Array.isArray(countries) || countries.length === 0) {
                    throw new Error('Ülke listesi boş veya geçersiz format');
                }
                
                const countryListElement = document.querySelector('.country-list');
                if (!countryListElement) {
                    throw new Error('Ülke listesi elementi bulunamadı');
                }
                
                let countryItemsHTML = '';

                countries.forEach(country => {
                    const isActive = country.name === 'Türkiye' ? 'active' : '';
                    countryItemsHTML += `
                        <div class="country-item ${isActive}" data-country="${country.name}" data-code="${country.code}" data-flag="${country.flag}">
                            <span class="country-flag">${country.flag}</span>
                            <span class="country-name">${country.name}</span>
                            <span class="country-code">${country.code}</span>
                        </div>
                    `;
                });

                countryListElement.innerHTML = countryItemsHTML;

                // Ülke seçimi işlemi için event listener'ları ekle
                const countryItems = document.querySelectorAll('.country-item');
                countryItems.forEach(item => {
                    item.addEventListener('click', function() {
                        const countryCode = this.getAttribute('data-code');
                        const countryFlag = this.getAttribute('data-flag');
                        
                        // Seçilen ülke bilgilerini toggle'a atama
                        document.querySelector('.country-select-toggle .country-flag').textContent = countryFlag;
                        document.querySelector('.country-select-toggle .country-code').textContent = countryCode;
                        
                        // Aktif ülkeyi değiştirme
                        countryItems.forEach(item => item.classList.remove('active'));
                        this.classList.add('active');
                        
                        // Dropdown'u kapatma
                        document.querySelector('.country-select').classList.remove('active');
                        
                        // Telefon inputunu güncellemek için placeholder değiştir
                        document.querySelector('.phone-field input[type="tel"]').placeholder = "Telefon";
                    });
                });
            })
            .catch(error => {
                console.error('Ülke listesi yüklenirken bir hata oluştu:', error);
                // Hata durumunda fallback ülke listesini kullan
                useFallbackCountries();
            });
    }

    // Fallback ülke listesini kullanma fonksiyonu
    function useFallbackCountries() {
        console.log('Fallback ülke listesi kullanılıyor');
        const countryListElement = document.querySelector('.country-list');
        if (!countryListElement) return;
        
        let countryItemsHTML = '';
        
        fallbackCountries.forEach(country => {
            const isActive = country.name === 'Türkiye' ? 'active' : '';
            countryItemsHTML += `
                <div class="country-item ${isActive}" data-country="${country.name}" data-code="${country.code}" data-flag="${country.flag}">
                    <span class="country-flag">${country.flag}</span>
                    <span class="country-name">${country.name}</span>
                    <span class="country-code">${country.code}</span>
                </div>
            `;
        });
        
        countryListElement.innerHTML = countryItemsHTML;
        
        // Ülke seçimi işlemi için event listener'ları ekle
        const countryItems = document.querySelectorAll('.country-item');
        countryItems.forEach(item => {
            item.addEventListener('click', function() {
                const countryCode = this.getAttribute('data-code');
                const countryFlag = this.getAttribute('data-flag');
                
                // Seçilen ülke bilgilerini toggle'a atama
                document.querySelector('.country-select-toggle .country-flag').textContent = countryFlag;
                document.querySelector('.country-select-toggle .country-code').textContent = countryCode;
                
                // Aktif ülkeyi değiştirme
                countryItems.forEach(item => item.classList.remove('active'));
                this.classList.add('active');
                
                // Dropdown'u kapatma
                document.querySelector('.country-select').classList.remove('active');
                
                // Telefon inputunu güncellemek için placeholder değiştir
                document.querySelector('.phone-field input[type="tel"]').placeholder = "Telefon";
            });
        });
    }

    // Ülke JSON verisi yüklenemezse inline olarak kullanacağımız alternatif veri
    const fallbackCountries = [
        { name: "Türkiye", code: "+90", flag: "🇹🇷" },
        { name: "Amerika Birleşik Devletleri", code: "+1", flag: "🇺🇸" },
        { name: "Almanya", code: "+49", flag: "🇩🇪" },
        { name: "Fransa", code: "+33", flag: "🇫🇷" },
        { name: "Birleşik Krallık", code: "+44", flag: "🇬🇧" }
    ];
</script>