<?php
// Gerekli servisleri dahil et
require_once dirname(__DIR__) . '/services/video-service.php';
require_once dirname(__DIR__) . '/includes/blog-service.php';

// Video servisi
$videoService = new VideoService();
$homeVideos = $videoService->getFeaturedVideos(3); // Ana sayfa için öne çıkan 3 video

// Blog servisi
$blogService = getBlogService();
$homeBlogs = $blogService->getPublishedPosts(3); // Ana sayfa için son 3 makale
?>

<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Modern Hero Section - Ruhumuzun Ç Vitamini
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
<div class="clean-hero-section" id="hero">
    <div class="hero-background-simple">
        <div class="hero-gradient-simple"></div>
    </div>

    <div class="container">
        <div class="row align-items-center min-vh-100 py-5">
            <!-- Content Section -->
            <div class="col-lg-6 order-lg-1 order-2" data-aos="fade-up" data-aos-duration="800">
                <div class="hero-content-simple">
                    <!-- Main Title -->
                    <h1 class="hero-title-simple">
                        Ruhumuzun <span class="title-accent">"Ç" Vitamini</span>
                    </h1>

                    <!-- Hero Description -->
                    <div class="hero-description-simple">
                        <blockquote class="hero-quote-simple">
                            "Şu zamanda en mühim vazife, imana hizmettir. İman saâdet-i ebediyenin anahtarıdır."
                        </blockquote>
                        <p class="hero-text-simple">
                            Helal dairesinde hizmetimize ortak olmaya hazır mısın?
                        </p>
                    </div>

                    <!-- Hero Action Buttons -->
                    <div class="hero-actions-simple">
                        <a href="<?= BASE_URL ?>/about" class="btn btn-primary btn-lg rounded-pill me-3">
                            Hikayemizi Keşfet
                        </a>
                        <a href="<?= BASE_URL ?>/contact" class="btn btn-outline-primary btn-lg rounded-pill">
                            Bize Katıl
                        </a>
                    </div>
                </div>
            </div>

            <!-- Image Section -->
            <div class="col-lg-6 order-lg-2 order-1" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                <div class="hero-image-simple">
                    <img src="<?= BASE_URL ?>/assets/image/OSY.png" alt="Osman Sungur Yeken" class="img-fluid rounded-3">
                </div>
            </div>
        </div>
    </div>


</div>
<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Modern Feature Section - Program & Faaliyetler
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
<div class="modern-features-section section-padding-120 bg-white" id="feature">
    <div class="container">
        <!-- Section Header -->
        <div class="row justify-content-center text-center mb-5">
            <div class="col-xxl-8 col-xl-9 col-lg-10">
                <div class="section-header" data-aos="fade-up" data-aos-duration="800">
                    <div class="section-badge mb-3">
                        <span class="badge-text">
                            <i class="fas fa-star me-2"></i>
                            Faaliyetlerimiz
                        </span>
                    </div>
                    <h2 class="section-title heading-lg text-gradient mb-4">
                        Hayatınızı Değiştirecek <span class="text-primary">Program & Faaliyetler</span>
                    </h2>
                    <p class="section-subtitle lead text-muted">
                        İmanımızı güçlendiren, bilgimizi artıran ve kalplerimizi birleştiren özel programlarımızla
                        tanışın
                    </p>
                </div>
            </div>
        </div>

        <!-- Features Grid -->
        <div class="row justify-content-center gutter-y-50">
            <!-- Cuma & Cumartesi Sohbetleri -->
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                <div class="modern-feature-card h-100">
                    <div class="feature-card-inner">
                        <div class="feature-icon-wrapper">
                            <div class="feature-icon-bg"></div>
                            <div class="feature-icon">
                                <img src="<?= BASE_URL ?>/assets/image/icons/ic_sohbet.png" alt="Sohbetler">
                            </div>
                            <div class="feature-badge">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="feature-content">
                            <h3 class="feature-title">Cuma & Cumartesi<br>Sohbetleri</h3>
                            <p class="feature-description">
                                Her hafta Ankara'da Cumartesi, İstanbul'da Cuma günleri bir araya geliyoruz.
                                Çınaraltı ekibi olarak sizleri de bekliyoruz!
                            </p>
                            <div class="feature-highlights">
                                <div class="highlight-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>Ankara & İstanbul</span>
                                </div>
                                <div class="highlight-item">
                                    <i class="fas fa-calendar-week"></i>
                                    <span>Haftalık</span>
                                </div>
                            </div>
                        </div>
                        <div class="feature-hover-effect"></div>
                    </div>
                </div>
            </div>

            <!-- Çınaraltı Akademi -->
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="400">
                <div class="modern-feature-card h-100">
                    <div class="feature-card-inner">
                        <div class="feature-icon-wrapper">
                            <div class="feature-icon-bg"></div>
                            <div class="feature-icon">
                                <img src="<?= BASE_URL ?>/assets/image/icons/ic_academy.png" alt="Akademi">
                            </div>
                            <div class="feature-badge">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                        </div>
                        <div class="feature-content">
                            <h3 class="feature-title">Çınaraltı<br>Akademi</h3>
                            <p class="feature-description">
                                Kontenjanla sınırlı, yılın belirli haftalarında düzenlediğimiz 6 günlük
                                eğlenceli aktiviteler ve akademi eğitimleri.
                            </p>
                            <div class="feature-highlights">
                                <div class="highlight-item">
                                    <i class="fas fa-clock"></i>
                                    <span>6 Günlük Program</span>
                                </div>
                                <div class="highlight-item">
                                    <i class="fas fa-users-cog"></i>
                                    <span>Sınırlı Kontenjan</span>
                                </div>
                            </div>
                        </div>
                        <div class="feature-hover-effect"></div>
                    </div>
                </div>
            </div>

            <!-- Çocuk Dersleri -->
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="600">
                <div class="modern-feature-card h-100">
                    <div class="feature-card-inner">
                        <div class="feature-icon-wrapper">
                            <div class="feature-icon-bg"></div>
                            <div class="feature-icon">
                                <img src="<?= BASE_URL ?>/assets/image/icons/ic_young.png" alt="Çocuk Dersleri">
                            </div>
                            <div class="feature-badge">
                                <i class="fas fa-child"></i>
                            </div>
                        </div>
                        <div class="feature-content">
                            <h3 class="feature-title">Çocuk<br>Dersleri</h3>
                            <p class="feature-description">
                                8-12 yaş arası küçük kardeşlerimize özel ders, oyun ve etkinlikler
                                ile keyifli öğrenme deneyimi.
                            </p>
                            <div class="feature-highlights">
                                <div class="highlight-item">
                                    <i class="fas fa-birthday-cake"></i>
                                    <span>8-12 Yaş</span>
                                </div>
                                <div class="highlight-item">
                                    <i class="fas fa-gamepad"></i>
                                    <span>Oyun & Etkinlik</span>
                                </div>
                            </div>
                        </div>
                        <div class="feature-hover-effect"></div>
                    </div>
                </div>
            </div>

            <!-- İnsani Yardım Faaliyetleri -->
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="800">
                <div class="modern-feature-card h-100">
                    <div class="feature-card-inner">
                        <div class="feature-icon-wrapper">
                            <div class="feature-icon-bg"></div>
                            <div class="feature-icon">
                                <i class="fas fa-hands-helping text-white"></i>
                            </div>
                            <div class="feature-badge">
                                <i class="fas fa-heart"></i>
                            </div>
                        </div>
                        <div class="feature-content">
                            <h3 class="feature-title">İnsani Yardım<br>Faaliyetleri</h3>
                            <p class="feature-description">
                                Gıda, eğitim ve sağlık destekleri ile muhtaç kardeşlerimize
                                ulaşarak paylaşmanın huzurunu yaşıyoruz.
                            </p>
                            <div class="feature-highlights">
                                <div class="highlight-item">
                                    <i class="fas fa-gift"></i>
                                    <span>1200+ Yardım</span>
                                </div>
                                <div class="highlight-item">
                                    <i class="fas fa-globe"></i>
                                    <span>3 Şehir</span>
                                </div>
                            </div>
                        </div>
                        <div class="feature-hover-effect"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="row justify-content-center mt-5 pt-4">
            <div class="col-auto">
                <div class="feature-cta" data-aos="fade-up" data-aos-delay="800">
                    <a href="<?= BASE_URL ?>/contact"
                        class="btn btn-primary btn-lg rounded-pill px-5 py-3 btn-glow-effect">
                        <span class="btn-text">
                            <i class="fas fa-comment-dots me-2"></i>
                            Daha Fazla Bilgi İçin Bizimle İletişime Geç
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Background Decorations -->
    <div class="feature-bg-decorations">
        <div class="decoration-circle decoration-circle-1"></div>
        <div class="decoration-circle decoration-circle-2"></div>
        <div class="decoration-dots decoration-dots-1"></div>
        <div class="decoration-dots decoration-dots-2"></div>
    </div>
</div>
<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Modern About Section with Enhanced Design
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
<div class="modern-about-section section-padding-120 bg-gradient-soft" id="about">
    <div class="container">
        <!-- Section Header -->
        <div class="row justify-content-center text-center mb-5">
            <div class="col-xxl-8 col-xl-9 col-lg-10">
                <div class="section-header" data-aos="fade-up" data-aos-duration="800">
                    <div class="section-badge mb-3">
                        <span class="badge-text">
                            <i class="fas fa-heart me-2"></i>
                            Biz Kimiz?
                        </span>
                    </div>
                    <h2 class="section-title heading-lg text-gradient mb-4">
                        Biz Kimiz?
                    </h2>
                    <p class="section-subtitle lead text-muted">
                        İman, ilim ve kardeşlik köprüsü kuran, kalpleri birleştiren bir topluluk
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row align-items-center gutter-y-60">
            <!-- Image Section -->
            <div class="col-lg-6 order-lg-1 order-2" data-aos="fade-right" data-aos-duration="1000"
                data-aos-delay="200">
                <div class="about-image-wrapper position-relative">
                    <div class="main-image">
                        <img src="<?= BASE_URL ?>/assets/image/home-3/content-1.png" alt="Çınaraltı Topluluğu"
                            class="img-fluid rounded-20">
                    </div>
                    <!-- Floating Elements -->
                    <div class="floating-card floating-card-1" data-aos="fade-up" data-aos-delay="600">
                        <div class="mini-card bg-white shadow-soft rounded-15 p-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-primary-light rounded-circle me-3">
                                    <i class="fas fa-users text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">1500+</h6>
                                    <small class="text-muted">Video içeriği</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="floating-card floating-card-2" data-aos="fade-up" data-aos-delay="800">
                        <div class="mini-card bg-white shadow-soft rounded-15 p-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-success-light rounded-circle me-3">
                                    <i class="fas fa-calendar-check text-success"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">100+</h6>
                                    <small class="text-muted">Etkinlik</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Decorative Shape -->
                    <div class="decorative-shape">
                        <img src="<?= BASE_URL ?>/assets/image/home-3/content-1-shape.svg" alt="decorative shape"
                            class="floating-animation">
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="col-lg-6 order-lg-2 order-1" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="300">
                <div class="about-content">
                    <!-- Key Features -->
                    <div class="feature-highlight-list mb-4">
                        <div class="feature-item" data-aos="fade-up" data-aos-delay="400">
                            <div class="feature-icon">
                                <i class="fas fa-mosque text-primary"></i>
                            </div>
                            <div class="feature-content">
                                <h5 class="feature-title">İslami Değerler</h5>
                                <p class="feature-desc">İslam'ın temel değerlerini samimi bir ortamda paylaşıyoruz</p>
                            </div>
                        </div>
                        <div class="feature-item" data-aos="fade-up" data-aos-delay="500">
                            <div class="feature-icon">
                                <i class="fas fa-handshake text-success"></i>
                            </div>
                            <div class="feature-content">
                                <h5 class="feature-title">Kardeşlik Bağı</h5>
                                <p class="feature-desc">Hoşgörü ve dayanışma ile güçlü bir aile atmosferi</p>
                            </div>
                        </div>
                        <div class="feature-item" data-aos="fade-up" data-aos-delay="600">
                            <div class="feature-icon">
                                <i class="fas fa-graduation-cap text-warning"></i>
                            </div>
                            <div class="feature-content">
                                <h5 class="feature-title">Eğitim & Gelişim</h5>
                                <p class="feature-desc">Kalplerin ve fikirlerin özgürce gelişebildiği ortam</p>
                            </div>
                        </div>
                    </div>

                    <!-- Main Description -->
                    <div class="about-description mb-4" data-aos="fade-up" data-aos-delay="700">
                        <p class="lead text-dark mb-3">
                            <strong>Çınaraltı olarak</strong>, İslam'ı daha yakından tanımak ve imani sorularına cevap
                            arayan
                            herkese samimi bir ortam sunmayı amaçlıyoruz.
                        </p>
                        <p class="text-muted">
                            Gençlerle birlikte gerçekleştirdiğimiz eğlenceli etkinlikler ve sohbetlerle,
                            inancımızın temel değerlerini paylaşırken aynı zamanda hoşgörü ve dayanışma
                            duygusunu da güçlendiriyoruz.
                        </p>
                    </div>

                    <!-- Call to Action -->
                    <div class="cta-buttons" data-aos="fade-up" data-aos-delay="800">
                        <a href="<?= BASE_URL ?>/about"
                            class="btn btn-primary btn-lg rounded-pill me-3 btn-hover-effect">
                            <span class="btn-text">Hikayemizi Keşfet</span>
                            <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                        <a href="<?= BASE_URL ?>/contact"
                            class="btn btn-outline-primary btn-lg rounded-pill btn-hover-effect">
                            <span class="btn-text">Bize Katıl</span>
                            <i class="fas fa-users ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="row justify-content-center mt-5 pt-5">
            <div class="col-12">
                <div class="stats-wrapper bg-white rounded-20 shadow-soft p-4" data-aos="fade-up" data-aos-delay="900">
                    <div class="row text-center">
                        <div class="col-md-3 col-6 mb-md-0 mb-3">
                            <div class="stat-item">
                                <div class="stat-icon mb-2">
                                    <i class="fas fa-calendar-alt text-primary"></i>
                                </div>
                                <h4 class="stat-number text-primary mb-1">5+</h4>
                                <p class="stat-label text-muted mb-0">Yıllık Deneyim</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-md-0 mb-3">
                            <div class="stat-item">
                                <div class="stat-icon mb-2">
                                    <i class="fas fa-heart text-danger"></i>
                                </div>
                                <h4 class="stat-number text-danger mb-1">500+</h4>
                                <p class="stat-label text-muted mb-0">Mutlu Üye</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-item">
                                <div class="stat-icon mb-2">
                                    <i class="fas fa-play-circle text-success"></i>
                                </div>
                                <h4 class="stat-number text-success mb-1">1500+</h4>
                                <p class="stat-label text-muted mb-0">Video İçerik</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-item">
                                <div class="stat-icon mb-2">
                                    <i class="fas fa-bookmark text-warning"></i>
                                </div>
                                <h4 class="stat-number text-warning mb-1">50+</h4>
                                <p class="stat-label text-muted mb-0">Makale</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Modern Video Production Section
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
<div class="modern-video-section section-padding-120 bg-white" id="video-production">
    <div class="container">
        <!-- Section Header -->
        <div class="row justify-content-center text-center mb-5">
            <div class="col-xxl-8 col-xl-9 col-lg-10">
                <div class="section-header" data-aos="fade-up" data-aos-duration="800">
                    <div class="section-badge-dark mb-3">
                        <span class="badge-text-dark">
                            <i class="fas fa-video me-2"></i>
                            Video Üretimi
                        </span>
                    </div>
                    <h2 class="section-title heading-lg text-dark mb-4">
                        İslami Video İçerikleri Üreten Bir <span class="text-gradient-green">Fabrika</span>
                    </h2>
                    <p class="section-subtitle lead text-muted">
                        Modern teknoloji ile İslam'ın evrensel mesajını tüm dünyaya ulaştırıyoruz
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row align-items-center gutter-y-60">
            <!-- Content Section -->
            <div class="col-lg-6 order-lg-1 order-2" data-aos="fade-right" data-aos-duration="1000"
                data-aos-delay="200">
                <div class="video-content">
                    <!-- Feature Stats -->
                    <div class="video-stats-grid mb-4">
                        <div class="video-stat-item" data-aos="fade-up" data-aos-delay="300">
                            <div class="stat-icon-wrapper">
                                <i class="fas fa-globe text-success"></i>
                            </div>
                            <div class="stat-content">
                                <h4 class="stat-value text-dark">Global</h4>
                                <p class="stat-desc text-muted">Erişim</p>
                            </div>
                        </div>
                        <div class="video-stat-item" data-aos="fade-up" data-aos-delay="400">
                            <div class="stat-icon-wrapper">
                                <i class="fas fa-clock text-warning"></i>
                            </div>
                            <div class="stat-content">
                                <h4 class="stat-value text-dark">7/24</h4>
                                <p class="stat-desc text-muted">Aktif</p>
                            </div>
                        </div>
                        <div class="video-stat-item" data-aos="fade-up" data-aos-delay="500">
                            <div class="stat-icon-wrapper">
                                <i class="fas fa-heart text-danger"></i>
                            </div>
                            <div class="stat-content">
                                <h4 class="stat-value text-dark">Kalite</h4>
                                <p class="stat-desc text-muted">İçerik</p>
                            </div>
                        </div>
                    </div>

                    <!-- Main Description -->
                    <div class="video-description mb-4" data-aos="fade-up" data-aos-delay="600">
                        <p class="lead text-dark mb-3">
                            <strong>Dünya çok büyüdü.</strong> Fiziksel olarak her mahalleye girip her eve İslam'ın
                            mesajını ulaştırmak zorlaştı.
                        </p>
                        <p class="text-muted mb-3">
                            Derdi veren Allah devayı da yaratır. Bir internet kablosuyla tüm evlere girmekle Kuran'ın
                            mesajını ulaştırmak mümkün.
                        </p>
                        <div class="highlight-box" data-aos="fade-up" data-aos-delay="700">
                            <div class="highlight-icon">
                                <i class="fas fa-quote-left"></i>
                            </div>
                            <p class="highlight-text">
                                "Modern teknoloji ile İslam'ın evrensel mesajını milyonlarca insana ulaştırıyoruz"
                            </p>
                        </div>
                    </div>



                    <!-- Call to Action -->
                    <div class="video-cta" data-aos="fade-up" data-aos-delay="900">
                        <a href="<?= BASE_URL ?>/video"
                            class="btn btn-success btn-lg rounded-pill me-3 btn-glow-effect">
                            <span class="btn-text">
                                <i class="fab fa-youtube me-2"></i>
                                Videolarımızı İzle
                            </span>
                        </a>
                        <a href="<?= BASE_URL ?>/about"
                            class="btn btn-outline-primary btn-lg rounded-pill btn-hover-effect">
                            <span class="btn-text">Daha Fazla Bilgi</span>
                            <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Image Section -->
            <div class="col-lg-6 order-lg-2 order-1" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="300">
                <div class="video-image-wrapper position-relative">
                    <div class="main-video-image">
                        <img src="<?= BASE_URL ?>/assets/image/home-3/content-2.png" alt="Video Prodüksiyon"
                            class="img-fluid rounded-20">
                        <div class="video-overlay">
                            <div class="play-button-large" data-aos="zoom-in" data-aos-delay="1000">
                                <i class="fas fa-play"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Floating Tech Cards -->
                    <div class="tech-card tech-card-1" data-aos="fade-up" data-aos-delay="700">
                        <div class="tech-mini-card bg-dark shadow-lg rounded-15 p-3">
                            <div class="d-flex align-items-center">
                                <div class="tech-icon-box bg-success-light rounded-circle me-3">
                                    <i class="fas fa-camera text-success"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-white">4K</h6>
                                    <small class="text-muted">Ultra HD</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tech-card tech-card-2" data-aos="fade-up" data-aos-delay="900">
                        <div class="tech-mini-card bg-dark shadow-lg rounded-15 p-3">
                            <div class="d-flex align-items-center">
                                <div class="tech-icon-box bg-primary-light rounded-circle me-3">
                                    <i class="fas fa-broadcast-tower text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-white">LIVE</h6>
                                    <small class="text-muted">Canlı Yayın</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Decorative Elements -->
                    <div class="video-decorative-shape">
                        <img src="<?= BASE_URL ?>/assets/image/home-3/content-2-shape.svg" alt="decorative shape"
                            class="pulse-animation">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    İnsani Yardım Faaliyetleri Bölümü
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
<div class="humanitarian-section section-padding-120 bg-gradient-dark" id="humanitarian-aid">
    <div class="container">
        <!-- Section Header -->
        <div class="row justify-content-center text-center mb-5">
            <div class="col-xxl-8 col-xl-9 col-lg-10">
                <div class="section-header" data-aos="fade-up" data-aos-duration="800">
                    <div class="section-badge-light mb-3">
                        <span class="badge-text-light">
                            <i class="fas fa-hands-helping me-2"></i>
                            İnsani Yardım
                        </span>
                    </div>
                    <h2 class="section-title heading-lg text-white mb-4">
                        <span class="text-gradient-yellow">Paylaşmanın Huzuru</span> ile Muhtaçlara Ulaşıyoruz
                    </h2>
                    <p class="section-subtitle lead text-light-muted">
                        "Kim bir müminin dünya sıkıntısından birini giderirse, Allah da onun ahiret sıkıntılarından
                        birini giderir"
                    </p>
                </div>
            </div>
        </div>

        <!-- Humanitarian Activities Grid -->
        <div class="row justify-content-center gutter-y-50">
            <!-- Gıda Yardımı -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                <div class="humanitarian-card h-100">
                    <div class="humanitarian-card-inner">
                        <div class="humanitarian-icon-wrapper">
                            <div class="humanitarian-icon-bg"></div>
                            <div class="humanitarian-icon">
                                <i class="fas fa-utensils text-white"></i>
                            </div>
                            <div class="humanitarian-badge">
                                <i class="fas fa-heart"></i>
                            </div>
                        </div>
                        <div class="humanitarian-content">
                            <h3 class="humanitarian-title text-white">Gıda Yardımları</h3>
                            <p class="humanitarian-description text-light-muted">
                                İhtiyaç sahibi aileler için düzenli gıda kolileri hazırlayarak,
                                komşularımızın sofrasına bereket getiriyoruz.
                            </p>
                            <div class="humanitarian-stats">
                                <div class="stat-item">
                                    <span class="stat-number text-yellow">500+</span>
                                    <span class="stat-label">Aile</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-number text-yellow">12</span>
                                    <span class="stat-label">Aylık</span>
                                </div>
                            </div>
                        </div>
                        <div class="humanitarian-hover-effect"></div>
                    </div>
                </div>
            </div>

            <!-- Eğitim Desteği -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="400">
                <div class="humanitarian-card h-100 featured-humanitarian">
                    <div class="humanitarian-card-inner">
                        <div class="humanitarian-popular-badge">
                            <i class="fas fa-star"></i>
                            <span>Aktif</span>
                        </div>
                        <div class="humanitarian-icon-wrapper">
                            <div class="humanitarian-icon-bg"></div>
                            <div class="humanitarian-icon">
                                <i class="fas fa-graduation-cap text-white"></i>
                            </div>
                            <div class="humanitarian-badge">
                                <i class="fas fa-book"></i>
                            </div>
                        </div>
                        <div class="humanitarian-content">
                            <h3 class="humanitarian-title text-white">Eğitim Destekleri</h3>
                            <p class="humanitarian-description text-light-muted">
                                Öğrenci kardeşlerimize burs, kitap ve kırtasiye desteği sağlayarak
                                eğitimlerinin devamını destekliyoruz.
                            </p>
                            <div class="humanitarian-stats">
                                <div class="stat-item">
                                    <span class="stat-number text-yellow">200+</span>
                                    <span class="stat-label">Öğrenci</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-number text-yellow">4</span>
                                    <span class="stat-label">Yıldır</span>
                                </div>
                            </div>
                        </div>
                        <div class="humanitarian-hover-effect"></div>
                    </div>
                </div>
            </div>

            <!-- Sağlık Yardımları -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="600">
                <div class="humanitarian-card h-100">
                    <div class="humanitarian-card-inner">
                        <div class="humanitarian-icon-wrapper">
                            <div class="humanitarian-icon-bg"></div>
                            <div class="humanitarian-icon">
                                <i class="fas fa-heartbeat text-white"></i>
                            </div>
                            <div class="humanitarian-badge">
                                <i class="fas fa-plus"></i>
                            </div>
                        </div>
                        <div class="humanitarian-content">
                            <h3 class="humanitarian-title text-white">Sağlık Destekleri</h3>
                            <p class="humanitarian-description text-light-muted">
                                Tedavi masrafları, ilaç giderleri ve tıbbi cihaz ihtiyaçları için
                                hasta kardeşlerimize destek oluyoruz.
                            </p>
                            <div class="humanitarian-stats">
                                <div class="stat-item">
                                    <span class="stat-number text-yellow">150+</span>
                                    <span class="stat-label">Hasta</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-number text-yellow">7/24</span>
                                    <span class="stat-label">Destek</span>
                                </div>
                            </div>
                        </div>
                        <div class="humanitarian-hover-effect"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="row justify-content-center mt-5 pt-4">
            <div class="col-auto">
                <div class="humanitarian-cta" data-aos="fade-up" data-aos-delay="800">
                    <a href="<?= BASE_URL ?>/donate"
                        class="btn btn-yellow btn-lg rounded-pill px-5 py-3 btn-glow-effect me-3">
                        <span class="btn-text">
                            <i class="fas fa-heart me-2"></i>
                            Yardım Et
                        </span>
                    </a>
                    <a href="<?= BASE_URL ?>/contact" class="btn btn-outline-light btn-lg rounded-pill px-5 py-3">
                        <span class="btn-text">
                            <i class="fas fa-phone me-2"></i>
                            Bilgi Al
                        </span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Impact Statistics -->
        <div class="row justify-content-center mt-5 pt-4">
            <div class="col-12">
                <div class="impact-stats-wrapper bg-white-10 rounded-20 p-4" data-aos="fade-up" data-aos-delay="900">
                    <div class="row text-center">
                        <div class="col-md-3 col-6 mb-md-0 mb-3">
                            <div class="impact-stat-item">
                                <div class="impact-stat-icon mb-2">
                                    <i class="fas fa-users text-yellow"></i>
                                </div>
                                <h4 class="impact-stat-number text-white mb-1">850+</h4>
                                <p class="impact-stat-label text-light-muted mb-0">Desteklenen Aile</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-md-0 mb-3">
                            <div class="impact-stat-item">
                                <div class="impact-stat-icon mb-2">
                                    <i class="fas fa-gift text-yellow"></i>
                                </div>
                                <h4 class="impact-stat-number text-white mb-1">1200+</h4>
                                <p class="impact-stat-label text-light-muted mb-0">Yardım Paketi</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="impact-stat-item">
                                <div class="impact-stat-icon mb-2">
                                    <i class="fas fa-hand-holding-heart text-yellow"></i>
                                </div>
                                <h4 class="impact-stat-number text-white mb-1">5+</h4>
                                <p class="impact-stat-label text-light-muted mb-0">Yıllık Deneyim</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="impact-stat-item">
                                <div class="impact-stat-icon mb-2">
                                    <i class="fas fa-globe text-yellow"></i>
                                </div>
                                <h4 class="impact-stat-number text-white mb-1">3</h4>
                                <p class="impact-stat-label text-light-muted mb-0">Şehir</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Background Decorations -->
    <div class="humanitarian-bg-decorations">
        <div class="decoration-heart decoration-heart-1"></div>
        <div class="decoration-heart decoration-heart-2"></div>
        <div class="decoration-dots decoration-dots-1"></div>
        <div class="decoration-dots decoration-dots-2"></div>
    </div>
</div>

<div class="ticker-01_section">
    <div class="ticker-01_wrapper">
        <div class="ticker-01_content">
            <div class="ticker-item">
                <p>Çınaraltı</p>
                <img src="<?= BASE_URL ?>/assets/image/logo.png" alt="Çınaraltı Logo">
            </div>
            <div class="ticker-item">
                <p>Çınaraltı</p>
                <img src="<?= BASE_URL ?>/assets/image/logo.png" alt="Çınaraltı Logo">
            </div>
            <div class="ticker-item">
                <p>Çınaraltı</p>
                <img src="<?= BASE_URL ?>/assets/image/logo.png" alt="Çınaraltı Logo">
            </div>
            <div class="ticker-item">
                <p>Çınaraltı</p>
                <img src="<?= BASE_URL ?>/assets/image/logo.png" alt="Çınaraltı Logo">
            </div>
        </div>
        <div class="ticker-01_content">
            <div class="ticker-item">
                <p>Çınaraltı</p>
                <img src="<?= BASE_URL ?>/assets/image/logo.png" alt="Çınaraltı Logo">
            </div>
            <div class="ticker-item">
                <p>Çınaraltı</p>
                <img src="<?= BASE_URL ?>/assets/image/logo.png" alt="Çınaraltı Logo">
            </div>
            <div class="ticker-item">
                <p>Çınaraltı</p>
                <img src="<?= BASE_URL ?>/assets/image/logo.png" alt="Çınaraltı Logo">
            </div>
            <div class="ticker-item">
                <p>Çınaraltı</p>
                <img src="<?= BASE_URL ?>/assets/image/logo.png" alt="Çınaraltı Logo">
            </div>
        </div>
        <div class="ticker-01_content">
            <div class="ticker-item">
                <p>Çınaraltı</p>
                <img src="<?= BASE_URL ?>/assets/image/logo.png" alt="Çınaraltı Logo">
            </div>
            <div class="ticker-item">
                <p>Çınaraltı</p>
                <img src="<?= BASE_URL ?>/assets/image/logo.png" alt="Çınaraltı Logo">
            </div>
            <div class="ticker-item">
                <p>Çınaraltı</p>
                <img src="<?= BASE_URL ?>/assets/image/logo.png" alt="Çınaraltı Logo">
            </div>
            <div class="ticker-item">
                <p>Çınaraltı</p>
                <img src="<?= BASE_URL ?>/assets/image/logo.png" alt="Çınaraltı Logo">
            </div>
        </div>
        <div class="ticker-01_content">
            <div class="ticker-item">
                <p>Çınaraltı</p>
                <img src="<?= BASE_URL ?>/assets/image/logo.png" alt="Çınaraltı Logo">
            </div>
            <div class="ticker-item">
                <p>Çınaraltı</p>
                <img src="<?= BASE_URL ?>/assets/image/logo.png" alt="Çınaraltı Logo">
            </div>
            <div class="ticker-item">
                <p>Çınaraltı</p>
                <img src="<?= BASE_URL ?>/assets/image/logo.png" alt="Çınaraltı Logo">
            </div>
            <div class="ticker-item">
                <p>Çınaraltı</p>
                <img src="<?= BASE_URL ?>/assets/image/logo.png" alt="Çınaraltı Logo">
            </div>
        </div>
        <div class="ticker-01_content">
            <div class="ticker-item">
                <p>Çınaraltı</p>
                <img src="<?= BASE_URL ?>/assets/image/logo.png" alt="Çınaraltı Logo">
            </div>
            <div class="ticker-item">
                <p>Çınaraltı</p>
                <img src="<?= BASE_URL ?>/assets/image/logo.png" alt="Çınaraltı Logo">
            </div>
            <div class="ticker-item">
                <p>Çınaraltı</p>
                <img src="<?= BASE_URL ?>/assets/image/logo.png" alt="Çınaraltı Logo">
            </div>
            <div class="ticker-item">
                <p>Çınaraltı</p>
                <img src="<?= BASE_URL ?>/assets/image/logo.png" alt="Çınaraltı Logo">
            </div>
        </div>
        <div class="ticker-01_content">
            <div class="ticker-item">
                <p>Çınaraltı</p>
                <img src="<?= BASE_URL ?>/assets/image/logo.png" alt="Çınaraltı Logo">
            </div>
            <div class="ticker-item">
                <p>Çınaraltı</p>
                <img src="<?= BASE_URL ?>/assets/image/logo.png" alt="Çınaraltı Logo">
            </div>
            <div class="ticker-item">
                <p>Çınaraltı</p>
                <img src="<?= BASE_URL ?>/assets/image/logo.png" alt="Çınaraltı Logo">
            </div>
            <div class="ticker-item">
                <p>Çınaraltı</p>
                <img src="<?= BASE_URL ?>/assets/image/logo.png" alt="Çınaraltı Logo">
            </div>
        </div>
    </div>
</div>




<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Home 3  : Video Section 
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
<div class="home-3_video-section section-padding bg-gradient-soft" id="courses">
    <div class="home-3_video-shape">
        <img src="<?= BASE_URL ?>/assets/image/home-3/video-shape.svg" alt="">
    </div>
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class=" col-xl-10 col-lg-11 col-md-12 ">
                <div class="d-flex align-items-center justify-content-center gap-3 mb-5">
                    <img src="<?= BASE_URL ?>/assets/image/icons/ic_youtube.png" alt="youtube" class="youtube-icon">
                    <h2 class="section-heading__title heading-md text-black mb-0">
                        YouTube Videolarımız
                    </h2>
                </div>
            </div>
        </div>
        <div class="row gutter-y-40 justify-content-center">
            <?php if (!empty($homeVideos)): ?>
            <?php foreach ($homeVideos as $index => $video): ?>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?= (($index + 1) * 100) ?>">
                <div class="video-widget">
                    <div class="video-widget__thumbnail-wrapper">
                        <div class="video-widget__thumbnail">
                            <img src="<?= $videoService->getYouTubeThumbnail($video['url']) ?>"
                                alt="<?= htmlspecialchars($video['title']) ?>">
                            <a href="<?= $video['url'] ?>" data-fancybox
                                class="btn-play absolute-center btn-play--outline btn-play--70">
                                <i class="fa-solid fa-play"></i>
                            </a>
                        </div>
                    </div>
                    <h3 class="video-widget__title"><?= htmlspecialchars($video['title']) ?></h3>
                    <p><?= htmlspecialchars(substr($video['description'], 0, 100)) ?><?= strlen($video['description']) > 100 ? '...' : '' ?>
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <!-- Fallback statik videolar -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="video-widget">
                    <div class="video-widget__thumbnail-wrapper">
                        <div class="video-widget__thumbnail">
                            <img src="<?= BASE_URL ?>/assets/image/home-3/video-thumbnail-1.png" alt="image alt">
                            <a href="https://www.youtube.com/watch?v=zo9dJFo8H8g" data-fancybox
                                class="btn-play absolute-center btn-play--outline btn-play--70">
                                <i class="fa-solid fa-play"></i>
                            </a>
                        </div>
                    </div>
                    <h3 class="video-widget__title">Örnek Video</h3>
                    <p>Henüz video eklenmemiş.</p>
                </div>
            </div>
            <?php endif; ?>
            <div class="section-button">
                <a href="<?= BASE_URL ?>/video" class="btn-masco btn-primary-l03 rounded-pill btn-shadow">
                    <span>Tüm videoları görüntüle</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Home 8  : Service Section 
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->

<div class="home-8_blog-section padding-bottom-120 bg-white" id="blog">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-xxl-10 col-xl-11 col-lg-12 col-md-12 col-auto">
                <div class="section-heading">
                    <h2 class="section-heading__title heading-lg text-black-2">MAKALELER
                    </h2>
                    <p>Osman Sungur Yeken'in kaleminden, iman hakikatleri, siyer, sahabeler ve birçok önemli konuda
                        derinleşen makaleleriyle sizlere bilgi dolu bir platform sunuyoruz. İslam’ın temel prensiplerini
                        anlamak, tarih boyunca yaşanmış örnekleri keşfetmek ve günlük hayatımıza anlam katmak için
                        yazıldı.</p>
                </div>
            </div>
        </div>
        <div class="row gutter-y-default justify-content-center">
            <?php if (!empty($homeBlogs)): ?>
            <?php foreach ($homeBlogs as $blog): ?>
            <div class="col-lg-4 col-md-6 col-sm-8">
                <div class="blog-card h-100">
                    <div class="blog-card__image">
                        <img src="<?= $blogService->getImagePath($blog['cover_image']) ?>"
                            alt="<?= htmlspecialchars($blog['title']) ?>">
                        <a href="<?= BASE_URL ?>/blog-details?slug=<?= $blog['slug'] ?>" class="blog-card__badge">
                            <?= htmlspecialchars($blog['category_name'] ?? 'Genel') ?>
                        </a>
                    </div>
                    <div class="blog-card__body">
                        <div class="blog-card__meta">
                            <span><i
                                    class="fa-regular fa-user me-2"></i><?= htmlspecialchars($blog['author_name'] ?? 'Osman Sungur Yeken') ?></span>
                            <span><i
                                    class="fa-regular fa-calendar-days me-2"></i><?= $blogService->formatDate($blog['created_at']) ?>
                            </span>
                        </div>
                        <h3 class="blog-card__title"><?= htmlspecialchars($blog['title']) ?></h3>
                        <a href="<?= BASE_URL ?>/blog-details?slug=<?= $blog['slug'] ?>"
                            class="blog-card__link btn-link btn-arrow">Devamını oku</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <!-- Fallback statik makaleler -->
            <div class="col-lg-4 col-md-6 col-sm-8">
                <div class="blog-card h-100">
                    <div class="blog-card__image">
                        <img src="<?= BASE_URL ?>/assets/image/home-8/blog-image-1.png" alt="image alt">
                        <a href="#" class="blog-card__badge">Genel</a>
                    </div>
                    <div class="blog-card__body">
                        <div class="blog-card__meta">
                            <span><i class="fa-regular fa-user me-2"></i>Osman Sungur Yeken</span>
                            <span><i class="fa-regular fa-calendar-days me-2"></i>Yakında</span>
                        </div>
                        <h3 class="blog-card__title">Henüz makale eklenmemiş</h3>
                        <a href="<?= BASE_URL ?>/blog" class="blog-card__link btn-link btn-arrow">Tüm makaleleri gör</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <div class="section-button">
                <a href="<?= BASE_URL ?>/blog" class="btn-masco btn-primary-l03 rounded-pill btn-shadow">
                    <span>Tüm makaleleri görüntüle</span>
                </a>
            </div>
        </div>
    </div>
</div>