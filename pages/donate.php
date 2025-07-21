<!-- Donate -->

<?php 
if (DEBUG_MODE) error_log("Donate sayfası yüklendi"); 
echo "<!-- Donate sayfası çalışıyor -->";
?>

<style>
.inner_banner-subtitle {
    margin-top: 15px;
    text-align: center;
}

.zakat-calc-button {
    display: inline-block;
    background-color: rgba(76, 175, 80, 0.1);
    color: #2e7d32;
    padding: 10px 20px;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 500;
    box-shadow: 0 3px 10px rgba(76, 175, 80, 0.1);
    transition: all 0.3s ease;
}

.zakat-calc-button:hover {
    background-color: rgba(76, 175, 80, 0.2);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(76, 175, 80, 0.2);
    color: #2e7d32;
}

.zakat-calc-button i {
    margin-left: 8px;
}

@media (max-width: 768px) {
    .zakat-calc-button {
        padding: 8px 15px;
        font-size: 14px;
    }
}

.zakat-calculator-banner {
    background: linear-gradient(120deg, #f2f9f4 0%, #e8f5ec 100%);
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0 8px 20px rgba(76, 175, 80, 0.1);
    margin-top: 30px;
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(76, 175, 80, 0.15);
}

.zakat-calculator-banner:before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 150px;
    height: 150px;
    background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%234CAF50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M8 14s1.5 2 4 2 4-2 4-2"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>');
    background-repeat: no-repeat;
    background-size: 100px;
    background-position: 50px -20px;
    opacity: 0.1;
}

.zakat-banner-title {
    font-size: 24px;
    color: #2e7d32;
    font-weight: 600;
    margin-bottom: 10px;
}

.zakat-banner-text {
    font-size: 16px;
    color: #4e4e4e;
    margin-bottom: 0;
}

@media (max-width: 768px) {
    .zakat-calculator-banner {
        padding: 20px;
        margin-top: 15px;
    }

    .zakat-banner-title {
        font-size: 20px;
    }

    .zakat-banner-text {
        font-size: 14px;
    }
}
</style>

<div class="inner_banner-section">
    <h3 class="inner_banner-title">Bağış</h3>
    <div class="inner_banner-subtitle">
        <a href="<?= BASE_URL ?>/zakat-calculator" class="zakat-calc-button">
            Zekatınızı kolayca hesaplayın <i class="fa fa-calculator"></i>
        </a>
    </div>
</div>

<!-- Bağış Kategorileri Menüsü -->
<div class="donate-categories-menu">
    <div class="container">
        <div class="donate-categories-wrapper">
            <?php
            
            // Aktif kategoriyi belirle
            $activeCategory = isset($_GET['category']) ? $_GET['category'] : 'tumu';
            $activeCurrency = isset($_GET['currency']) ? $_GET['currency'] : '';
            
            // İkon haritası - Modern SVG ikonlar için
            $modernIconMap = [
                'tumu' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/></svg>',
                'acil-yardim' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="cat-icon-acil" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/></svg>',
                'zekat' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="cat-icon-zekat" viewBox="0 0 16 16"><path d="M4 10.781c.148 1.667 1.513 2.85 3.591 3.003V15h1.043v-1.216c2.27-.179 3.678-1.438 3.678-3.3 0-1.59-.947-2.51-2.956-3.028l-.722-.187V3.467c1.122.11 1.879.714 2.07 1.616h1.47c-.166-1.6-1.54-2.748-3.54-2.875V1H7.591v1.233c-1.939.23-3.27 1.472-3.27 3.156 0 1.454.966 2.483 2.661 2.917l.61.162v4.031c-1.149-.17-1.94-.8-2.131-1.718H4zm3.391-3.836c-1.043-.263-1.6-.825-1.6-1.616 0-.944.704-1.641 1.8-1.828v3.495l-.2-.05zm1.591 1.872c1.287.323 1.852.859 1.852 1.769 0 1.097-.826 1.828-2.2 1.939V8.73l.348.086z"/></svg>',
                'egitim' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="cat-icon-egitim" viewBox="0 0 16 16"><path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z"/></svg>',
                'yetim' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="cat-icon-yetim" viewBox="0 0 16 16"><path d="M13 2.5a1.5 1.5 0 0 1 3 0v11a1.5 1.5 0 0 1-3 0v-.214c-2.162-1.241-4.49-1.843-6.912-2.083l.405 2.712A1 1 0 0 1 5.51 15.1h-.548a1 1 0 0 1-.916-.599l-1.85-3.49a68.14 68.14 0 0 0-.202-.003A2.014 2.014 0 0 1 0 9V7a2.02 2.02 0 0 1 1.992-2.013 74.663 74.663 0 0 0 2.483-.075c3.043-.154 6.148-.849 8.525-2.199V2.5zm1 0v11a.5.5 0 0 0 1 0v-11a.5.5 0 0 0-1 0zm-1 1.35c-2.344 1.205-5.209 1.842-8 2.033v4.233c.18.01.359.022.537.036 2.568.189 5.093.744 7.463 1.993V3.85zm-9 6.215v-4.13a95.09 95.09 0 0 1-1.992.052A1.02 1.02 0 0 0 1 7v2c0 .55.448 1.002 1.006 1.009A60.49 60.49 0 0 1 4 10.065zm-.657.975 1.609 3.037.01.024h.548l-.002-.014-.443-2.966a68.019 68.019 0 0 0-1.722-.082z"/></svg>',
                'su-kuyusu' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="cat-icon-su" viewBox="0 0 16 16"><path d="M8 16a6 6 0 0 0 6-6c0-1.655-1.122-2.904-2.432-4.362C10.254 4.176 8.75 2.503 8 0c0 0-6 5.686-6 10a6 6 0 0 0 6 6ZM6.646 4.646l.708.708c-.29.29-.444.617-.51.97l.554.554c.48-.48.535-.998.535-1.232a2.5 2.5 0 0 1 5 0c0 .234.055.752.535 1.232l.554-.554c-.065-.353-.22-.68-.51-.97l.708-.708c.51.51.793 1.186.793 1.912 0 .967-.546 1.892-1.312 2.658-.766.765-1.691 1.311-2.658 1.311s-1.892-.546-2.658-1.311C5.546 8.45 5 7.525 5 6.558c0-.726.283-1.403.793-1.912Z"/></svg>',
                'genel' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="cat-icon-genel" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/></svg>',
                'projeler' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="cat-icon-projeler" viewBox="0 0 16 16"><path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383zm.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z"/></svg>',
                'kurban' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="cat-icon-kurban" viewBox="0 0 16 16"><path d="M16 3c-2.51 0-4.074 1.123-4.666 2.842a3.8 3.8 0 0 0-1.29-.307c-.769-.028-1.355.341-1.663.788-.305.44-.381.943-.178 1.329.259.5.779.8 1.438.812.243.004.499-.033.722-.111v4.994c0 .304.248.55.55.55h.05a.55.55 0 0 0 .537-.55V4.802c.645-1.628 2.002-2.5 4.5-2.5v1a.5.5 0 0 1 1 0V2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v1a.5.5 0 0 1 1 0v.298c1.136.132 2 .715 2 1.702 0 .94-.865 1.702-2 1.702v1c1.135 0 2 .763 2 1.702 0 .897-.776 1.537-1.91 1.692-.079-.096-.18-.192-.333-.192-.083 0-.167.021-.25.042-.045.011-.088.03-.125.049L1 9.45V12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1h-1.5l.001-1ZM3 4.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0Zm11.5 7.5h-11v-1h11v1Z"/></svg>',
                'currency' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="cat-icon-money" viewBox="0 0 16 16"><path d="M1.5 2A1.5 1.5 0 0 0 0 3.5v2h6a.5.5 0 0 1 .5.5c0 .253.08.644.306.958.207.288.557.542 1.194.542.637 0 .987-.254 1.194-.542.226-.314.306-.705.306-.958a.5.5 0 0 1 .5-.5h6v-2A1.5 1.5 0 0 0 14.5 2h-13z"/><path d="M16 6.5h-5.551a2.678 2.678 0 0 1-.443 1.042C9.613 8.088 8.963 8.5 8 8.5c-.963 0-1.613-.412-2.006-.958A2.679 2.679 0 0 1 5.551 6.5H0v6A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-6z"/></svg>',
                'calculator' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="cat-icon-calculator" viewBox="0 0 16 16"><path d="M12 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h8zM4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4z"/><path d="M4 2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-2zm0 4a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-4z"/></svg>'
            ];
            
            // Önce "Tümü" kategorisini göster
            $isActive = ($activeCategory == 'tumu') ? 'active' : '';
            echo '<a href="'. BASE_URL .'/donate?category=tumu" class="donate-category-item '. $isActive .'">';
            echo '<div class="category-icon-container">' . $modernIconMap['tumu'] . '</div>';
            echo '<span class="category-text">Tümü</span>';
            echo '</a>';
            
            try {
                // Veritabanı bağlantısı
                $db = db_connect();
                

                
                // Kategorileri veritabanından çek (tekrarları gruplandırarak)
                $stmt = $db->prepare("
                    SELECT id, name, slug
                    FROM donation_categories 
                    GROUP BY slug
                    ORDER BY name ASC
                ");
                $stmt->execute();
                $categories = $stmt->fetchAll();
                

                
                // Kategorileri göster
                foreach ($categories as $category) {
                    $isActive = ($category['slug'] == $activeCategory) ? 'active' : '';
                    $iconKey = isset($modernIconMap[$category['slug']]) ? $category['slug'] : 'genel';
                    
                    echo '<a href="'. BASE_URL .'/donate?category='. $category['slug'] .'" class="donate-category-item '. $isActive .'">';
                    echo '<div class="category-icon-container">' . $modernIconMap[$iconKey] . '</div>';
                    echo '<span class="category-text">'. $category['name'] .'</span>';
                    echo '</a>';
                }
                
            } catch (Exception $e) {
                // Hata durumunda statik kategori listesi gösterilir

                
                $staticCategories = [
                    'acil-yardim' => ['text' => 'Acil Yardım'],
                    'yetim' => ['text' => 'Yetim'],
                    'genel' => ['text' => 'Genel'],
                    'projeler' => ['text' => 'Projeler'],
                    'egitim' => ['text' => 'Eğitim'],
                    'kurban' => ['text' => 'Kurban']
                ];
                
                foreach ($staticCategories as $slug => $info) {
                    $isActive = ($slug == $activeCategory) ? 'active' : '';
                    $iconKey = isset($modernIconMap[$slug]) ? $slug : 'genel';
                    
                    echo '<a href="'. BASE_URL .'/donate?category='. $slug .'" class="donate-category-item '. $isActive .'">';
                    echo '<div class="category-icon-container">' . $modernIconMap[$iconKey] . '</div>';
                    echo '<span class="category-text">'. $info['text'] .'</span>';
                    echo '</a>';
                }
                

            }
            
            // Para birimi seçeneği
            $currencyActive = (!empty($activeCurrency)) ? 'active' : '';
            ?>
            <a href="#" class="donate-category-item currency-item <?= $currencyActive ?>" id="currencySettingsBtn">
                <div class="category-icon-container"><?= $modernIconMap['currency'] ?></div>
                <span class="category-text">₺ (Türk Lirası)</span>
            </a>

            <!-- Zekat Hesaplama Butonu -->
            <a href="<?= BASE_URL ?>/zakat-calculator" class="donate-category-item">
                <div class="category-icon-container"><?= $modernIconMap['calculator'] ?></div>
                <span class="category-text">Zekat Hesapla</span>
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
                    error_log("Donate sayfası - Veritabanı bağlantısı başarılı");
                }
                

                
                // Aktif kategoriyi veritabanındaki id'ye çevir
                    $categorySlug = isset($_GET['category']) ? $_GET['category'] : (isset($_SESSION['selected_category']) ? $_SESSION['selected_category'] : 'tumu');
                
                // Eğer kategori "tumu" ise tüm bağışları getir
                if ($categorySlug === 'tumu') {
                    $stmt = $db->prepare("
                        SELECT 
                            id, 
                            name as title, 
                            slug, 
                            description, 
                            image as main_image,
                            target_amount,
                            collected_amount
                        FROM 
                            donation_options
                        WHERE 
                            is_active = 1
                        GROUP BY
                            slug
                        ORDER BY
                            position ASC
                    ");
                    $stmt->execute();
                    $donationItems = $stmt->fetchAll();
                    
                    if (DEBUG_MODE) {
                        error_log("Tüm bağışlar sorgusu - Sonuç sayısı: " . count($donationItems));
                        if (count($donationItems) > 0) {
                            error_log("İlk bağış: " . json_encode($donationItems[0]));
                        }
                    }
                } else {
                    $stmt = $db->prepare("
                        SELECT id
                        FROM donation_categories 
                        WHERE slug = ?
                        GROUP BY slug
                        LIMIT 1
                    ");
                    $stmt->execute([$categorySlug]);
                    $category = $stmt->fetch();
                    $categoryId = $category ? $category['id'] : 0;
                    

                    
                    // Donation_options tablosundan bağış öğelerini çek
                    if ($categoryId > 0) {
                        $stmt = $db->prepare("
                            SELECT 
                                do.id, 
                                do.name as title, 
                                do.slug, 
                                do.description, 
                                do.image as main_image,
                                do.target_amount,
                                do.collected_amount
                            FROM 
                                donation_options do
                            INNER JOIN 
                                donation_option_categories doc ON do.id = doc.donation_option_id
                            WHERE 
                                doc.category_id = ? AND do.is_active = 1
                            GROUP BY 
                                do.slug
                            ORDER BY
                                do.position ASC
                        ");
                        $stmt->execute([$categoryId]);
                        $donationItems = $stmt->fetchAll();
                        

                    }
                }
                
                // Eğer hiç bağış öğesi bulunamadıysa, tüm aktif bağışları getir
                if (empty($donationItems)) {
                    // Özel kategori mesajı
                    if ($categorySlug !== 'tumu' && $categorySlug !== 'all') {
                        echo '<div class="col-12 text-center">
                            <div class="alert alert-info p-4 my-4" role="alert">
                                <h4 class="alert-heading"><i class="fa fa-info-circle"></i> Bilgi</h4>
                                <p class="mb-0">Henüz bu kategoride bağış seçeneği yok.</p>
                            </div>
                        </div>';
                    }
                    
                    $stmt = $db->prepare("
                        SELECT 
                            id, 
                            name as title, 
                            slug, 
                            description, 
                            image as main_image,
                            target_amount,
                            collected_amount
                        FROM 
                            donation_options
                        WHERE 
                            is_active = 1
                        GROUP BY
                            slug
                        ORDER BY
                            position ASC
                    ");
                    $stmt->execute();
                    $donationItems = $stmt->fetchAll();
                    
                    // DEBUG_MODE test mesajı kaldırıldı
                }
                
                // Eğer veritabanında bağış türü yoksa, JSON dosyasından yükle
                if (empty($donationItems)) {
                    if (DEBUG_MODE) {
                        echo '<div style="padding: 10px; background-color: #fff3cd; color: #856404; margin-bottom: 10px; border-radius: 5px;">Veritabanında bağış türü bulunamadı. JSON dosyasından yükleniyor...</div>';
                    }
                    
                    $jsonFile = file_get_contents(__DIR__ . "/../public/data/donations.json");
                    $jsonData = json_decode($jsonFile, true);
                    
                    if ($jsonData) {
                        // Tümü kategorisi seçiliyse veya kategori all ise tüm verileri göster
                        if ($categorySlug === 'tumu' || $categorySlug === 'all') {
                            $filteredData = $jsonData;
                        } else {
                            // Filtrele ve donationItems formatına dönüştür
                            $filteredData = array_filter($jsonData, function($item) use ($categorySlug) {
                                return in_array($categorySlug, $item['categories']);
                            });
                            
                            // Kategori filtrelemesinden sonuç gelmezse tüm verileri göster
                            if (empty($filteredData)) {
                                $filteredData = $jsonData;
                            }
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
                                <span
                                    class="donation-collected"><?= number_format($donation['collected_amount'], 0, ',', '.') ?>
                                    ₺</span>
                                <span
                                    class="donation-target"><?= number_format($donation['target_amount'], 0, ',', '.') ?>
                                    ₺</span>
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
                            <button class="donate-new-card-button"
                                onclick="openDonateModal('<?= $donation['title'] ?>', this)">Bağış Yap</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                    }
                } else {
                    echo '<div class="col-12 text-center">
                        <div class="alert alert-info p-4 my-4" role="alert">
                            <h4 class="alert-heading"><i class="fa fa-info-circle"></i> Bilgi</h4>
                            <p class="mb-0">Henüz bu kategoride bağış seçeneği yok.</p>
                        </div>
                    </div>';
                }
            } catch (Exception $e) {
                if (DEBUG_MODE) {
                    error_log("Donate sayfası Exception yakalandı: " . $e->getMessage());
                    error_log("Exception trace: " . $e->getTraceAsString());
                }
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
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 15px 0;
    position: sticky;
    top: 0;
    z-index: 100;
    border-bottom: 1px solid #eaeaea;
}

.donate-categories-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.donate-category-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-decoration: none;
    color: #555;
    padding: 12px;
    border-radius: 8px;
    transition: all 0.3s ease;
    text-align: center;
    min-width: 90px;
    position: relative;
}

.donate-category-item:hover {
    background-color: #f8f9fa;
    color: #28a745;
    transform: translateY(-2px);
}

.donate-category-item.active {
    color: #28a745;
    font-weight: 600;
    position: relative;
}

.donate-category-item.active::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 50%;
    transform: translateX(-50%);
    width: 80%;
    height: 3px;
    background-color: #28a745;
    border-radius: 3px;
}

.category-icon-container {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    background-color: #f8f9fa;
    border-radius: 50%;
    margin-bottom: 8px;
    transition: all 0.3s ease;
}

.donate-category-item:hover .category-icon-container,
.donate-category-item.active .category-icon-container {
    background-color: rgba(40, 167, 69, 0.1);
}

.category-icon-container svg {
    width: 24px;
    height: 24px;
    transition: all 0.3s ease;
}

/* İkon renkleri */
.cat-icon-acil {
    color: #dc3545;
}

.cat-icon-zekat {
    color: #6f42c1;
}

.cat-icon-egitim {
    color: #8950fc;
}

.cat-icon-yetim {
    color: #00acc1;
}

.cat-icon-su {
    color: #20c997;
}

.cat-icon-genel {
    color: #fd7e14;
}

.cat-icon-projeler {
    color: #17a2b8;
}

.cat-icon-kurban {
    color: #e83e8c;
}

.cat-icon-money {
    color: #198754;
}

.donate-category-item:hover svg,
.donate-category-item.active svg {
    color: #28a745;
}

.category-text {
    font-size: 14px;
    white-space: nowrap;
    font-weight: 500;
    transition: color 0.3s ease;
}

.currency-item {
    margin-left: 5px;
    position: relative;
}

.currency-item::before {
    content: '';
    position: absolute;
    left: -15px;
    top: 20%;
    height: 60%;
    width: 1px;
    background-color: #dee2e6;
}

/* Mobil görünüm için medya sorgusu */
@media (max-width: 768px) {
    .donate-categories-wrapper {
        justify-content: flex-start;
        overflow-x: auto;
        padding: 10px 15px;
        flex-wrap: nowrap;
        -webkit-overflow-scrolling: touch;
    }

    .donate-category-item {
        min-width: 80px;
        justify-content: center;
        flex-shrink: 0;
    }

    .category-icon-container {
        width: 40px;
        height: 40px;
    }

    .category-text {
        font-size: 12px;
    }

    .currency-item::before {
        display: none;
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

.donate-radio input:checked+.donate-radio__mark::after {
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
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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

.modal-btn-donate,
.modal-btn-cart {
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
    padding-bottom: 50%;
    /* Üst kısmın kartın %50'sini kaplaması için */
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
    padding-bottom: 50%;
    /* Üst kısmın kartın %50'sini kaplaması için */
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

.settings-option input:checked+.settings-option-circle:after {
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
    // Payment sayfasından gelen hata mesajını kontrol et
    <?php if (isset($_SESSION['payment_error'])): ?>
        showNotification('<?= addslashes($_SESSION['payment_error']) ?>', 'error');
        <?php unset($_SESSION['payment_error']); // Mesajı gösterdikten sonra temizle ?>
    <?php endif; ?>

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
        const donationType = document.querySelector('input[name="donateType"]:checked')
            .nextElementSibling.nextElementSibling.textContent;
        const donorName = document.querySelector('.donate-form__field input[placeholder="Ad Soyad"]')
            .value;
        const donorPhone = document.querySelector('.donate-form__field input[placeholder="Telefon"]')
            .value;
        const donorEmail = document.querySelector('.donate-form__field input[placeholder="E-Posta"]')
            .value;
        const donationType2 = document.querySelector('.donate-form__type .donate-type-btn.active')
            .textContent;

        // Sepete ekle
        addToCart({
            title: donationTitle,
            amount: donationAmount,
            donationType: donationType,
            donorName: donorName,
            donorPhone: donorPhone,
            donorEmail: donorEmail,
            donorType: donationType2,
            image: document.querySelector('.donate-new-card-image img[alt="' + donationTitle +
                '"]')?.src || '../public/assets/image/donate/donate1.jpg'
        });

        // Sepet sayfasına git
        window.location.href = '<?= BASE_URL ?>/cart';
    });

    // Sepete Ekle butonuna tıklama
    document.querySelector('.modal-btn-cart').addEventListener('click', function() {
        // Bağış bilgilerini al
        const donationTitle = document.getElementById('donateModalLabel').textContent;
        const donationAmount = document.querySelector('.donate-form__input').value;
        const donationType = document.querySelector('input[name="donateType"]:checked')
            .nextElementSibling.nextElementSibling.textContent;
        const donorName = document.querySelector('.donate-form__field input[placeholder="Ad Soyad"]')
            .value;
        const donorPhone = document.querySelector('.donate-form__field input[placeholder="Telefon"]')
            .value;
        const donorEmail = document.querySelector('.donate-form__field input[placeholder="E-Posta"]')
            .value;
        const donationType2 = document.querySelector('.donate-form__type .donate-type-btn.active')
            .textContent;

        // Sepete ekle
        addToCart({
            title: donationTitle,
            amount: donationAmount,
            donationType: donationType,
            donorName: donorName,
            donorPhone: donorPhone,
            donorEmail: donorEmail,
            donorType: donationType2,
            image: document.querySelector('.donate-new-card-image img[alt="' + donationTitle +
                '"]')?.src || '../public/assets/image/donate/donate1.jpg'
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
    function showNotification(message, type = 'success') {
        // Bildirim elementi oluştur
        const notification = document.createElement('div');
        notification.className = 'donation-notification';
        
        // Hata bildirimi için farklı renk
        if (type === 'error') {
            notification.style.backgroundColor = '#F44336';
            notification.style.borderColor = '#d32f2f';
        }
        
        // İkon tipine göre değiştir
        const icon = type === 'success' ? '✓' : type === 'error' ? '⚠️' : 'ℹ️';
        
        notification.innerHTML = `
                <div class="notification-icon">${icon}</div>
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
        const hideDelay = type === 'error' ? 4000 : 5000; // Hata mesajları daha uzun gösterilsin
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                notification.remove();
            }, 500);
        }, hideDelay);
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
        if (selectedLang && selectedLang !==
            '<?= isset($_COOKIE["userLanguage"]) ? $_COOKIE["userLanguage"] : "tr" ?>') {
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
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
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
                    document.querySelector('.country-select-toggle .country-flag').textContent =
                        countryFlag;
                    document.querySelector('.country-select-toggle .country-code').textContent =
                        countryCode;

                    // Aktif ülkeyi değiştirme
                    countryItems.forEach(item => item.classList.remove('active'));
                    this.classList.add('active');

                    // Dropdown'u kapatma
                    document.querySelector('.country-select').classList.remove('active');

                    // Telefon inputunu güncellemek için placeholder değiştir
                    document.querySelector('.phone-field input[type="tel"]').placeholder =
                        "Telefon";
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
const fallbackCountries = [{
        name: "Türkiye",
        code: "+90",
        flag: "🇹🇷"
    },
    {
        name: "Amerika Birleşik Devletleri",
        code: "+1",
        flag: "🇺🇸"
    },
    {
        name: "Almanya",
        code: "+49",
        flag: "🇩🇪"
    },
    {
        name: "Fransa",
        code: "+33",
        flag: "🇫🇷"
    },
    {
        name: "Birleşik Krallık",
        code: "+44",
        flag: "🇬🇧"
    }
];
</script>

<?php 
echo "\n\n<!-- DONATE PAGE DEBUG: Sayfa sonu -->\n";
?>