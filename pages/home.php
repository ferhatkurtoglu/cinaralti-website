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
    Home 3 : Hero Section 
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
  <div class="home-3_hero-section" id="hero">
      <div class="home-3_hero-shape">
          <img src="../public/assets/image/home-3/hero-shape.png" alt="image alt" />
      </div>
      <div class="container">
          <div class="row row--custom">
              <div class="col-lg-6 offset-lg-1 col-sm-4 col-5" data-aos-duration="1000" data-aos="fade-left"
                  data-aos-delay="300">

                  <div class="home-3_hero-image-block">
                      <div class="home-3_hero-image">
                          <img class="hero-image" src="../public/assets/image/OSY.png" alt="hero image" />

                      </div>
                  </div>

              </div>
              <div class="col-lg-4 col-md-10" data-aos-duration="1000" data-aos="fade-right" data-aos-delay="300">
                  <div class="home-3_hero-content">
                      <div class="home-3_hero-content-text">
                          <h1 class="hero-content__title heading-xl text-black">
                              Ruhumuzun "Ç" Vitamini
                          </h1>
                          <p>
                              “Şu zamanda en mühim vazife, imana hizmettir. İman saâdet-i ebediyenin anahtarıdır.” Helal dairesinde
                              hizmetimize ortak olmaya hazır mısın?
                          </p>
                      </div>

                  </div>
              </div>
          </div>
      </div>
  </div>
  <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Home 3  : Feature Section 
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
  <div class="home-3_feature-section section-padding-100" id="feature">
      <div class="container">
          <div class="row justify-content-center text-center">
              <div class="col-xxl-6 col-xl-7 col-lg-8 col-md-10">
                  <div class="section-heading">
                      <h2 class="section-heading__title heading-md text-black">Hayatınızı değiştirecek program &amp; faaliyetler
                      </h2>
                  </div>
              </div>
          </div>
          <div class="row justify-content-center gutter-y-default">
              <div class="col-lg-4 col-md-6 col-xs-10" data-aos-duration="1000" data-aos="fade-left" data-aos-delay="700">
                  <div class="feature-widget">
                      <div class="feature-widget__icon">
                          <img src="../public/assets/image/icons/ic_sohbet.png" alt="image alt">
                      </div>
                      <div class="feature-widget__body">
                          <h3 class="feature-widget__title ">Cuma &amp; Cumartesi sohbetleri</h3>
                          <p> Her hafta Ankara'da Cumartesi, İstanbul'da Cuma günleri bir araya geliyoruz. Çınaraltı ekibi olarak
                              sizleri de bekliyoruz!</p>
                      </div>
                  </div>
              </div>
              <div class="col-lg-4 col-md-6 col-xs-10" data-aos-duration="1000" data-aos="fade-left" data-aos-delay="600">
                  <div class="feature-widget">
                      <div class="feature-widget__icon">
                          <img src="../public/assets/image/icons/ic_academy.png" alt="image alt">
                      </div>
                      <div class="feature-widget__body">
                          <h3 class="feature-widget__title ">Çınaraltı Akademi</h3>
                          <p>Kontenjanla sınırlı, yılın belirli haftalarında düzenlediğimiz 6 günlük eğlenceli aktiviteler ve
                              akademi eğitimleri.</p>
                      </div>
                  </div>
              </div>
              <div class="col-lg-4 col-md-6 col-xs-10" data-aos-duration="1000" data-aos="fade-left" data-aos-delay="500">
                  <div class="feature-widget">
                      <div class="feature-widget__icon">
                          <img src="../public/assets/image/icons/ic_young.png" alt="image alt">
                      </div>
                      <div class="feature-widget__body">
                          <h3 class="feature-widget__title ">Çocuk dersleri</h3>
                          <p>8-12 yaş arası küçük kardeşlerimize özel ders, oyun ve etkinlikler.</p>
                      </div>
                  </div>
              </div>
              <div class="col-auto">
                  <a href="<?= BASE_URL ?>/contact" class="btn-masco btn-primary-l03 btn-shadow rounded-pill">
                      <span>
                          Daha fazla bilgi için bizimle iletişime geç
                      </span>
                  </a>
              </div>

          </div>


      </div>

  </div>
  <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Home 3  : Content Section 1
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
  <div class="home-3_content-section-1 padding-top-120 padding-bottom-150 bg-light-2" id="about">
      <div class="container">
          <div class="row row--custom ">
              <div class="offset-lg-1 col-xxl-auto col-md-3  col-xs-4 col-5" data-aos-duration="1000" data-aos="fade-right">
                  <div class="home-3_content-image-1-block ">
                      <div class="home-3_content-image-1">
                          <img src="../public/assets/image/home-3/content-1.png" alt="alternative text">
                      </div>
                      <div class="home-3_content-image-1-shape absolute-center">
                          <img src="../public/assets/image/home-3/content-1-shape.svg" alt="image shape" class="">
                      </div>
                  </div>
              </div>
              <div class="offset-xl-1 col-xl-6 col-lg-7 col-md-10 col-auto" data-aos-duration="1000" data-aos="fade-left">
                  <div class="content">
                      <div class="content-text-block">
                          <h2 class="content-title heading-md text-black">
                              Hakkımızda
                          </h2>
                          <p>Çınaraltı olarak, İslam’ı daha yakından tanımak ve imani sorularına cevap arayan herkese samimi bir
                              ortam sunmayı amaçlıyoruz. Gençlerle birlikte gerçekleştirdiğimiz eğlenceli etkinlikler ve
                              sohbetlerle, inancımızın temel değerlerini paylaşırken aynı zamanda hoşgörü ve dayanışma duygusunu da
                              güçlendiriyoruz. </p>
                          <p> Kalplerin ve fikirlerin özgürce gelişebileceği, birbirimize destek olabileceğimiz bir
                              aile atmosferi oluşturmak en büyük önceliğimiz. Gelin, hep birlikte Çınaraltı’nda buluşalım!</p>
                      </div>
                      <div class="content-button-block">
                          <a href="<?= BASE_URL ?>/about" class="btn-masco btn-primary-l03 btn-shadow rounded-pill"><span>Hakkımızda daha
                                  fazlası için</span></a>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Home 3  : Content Section 2
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
  <div class="home-3_content-section-2 padding-bottom-120 bg-light-2">
      <div class="container">
          <div class="row row--custom ">
              <div class="col-xl-4 offset-lg-1 col-md-3 col-xs-4 col-5" data-aos-duration="1000" data-aos="fade-left">
                  <div class="home-3_content-image-2-block content-image--mobile-width">
                      <div class="home-3_content-image-2">
                          <img src="../public/assets/image/home-3/content-2.png" alt="alternative text">
                      </div>
                      <div class="home-3_content-image-2-shape absolute-center">
                          <img src="../public/assets/image/home-3/content-2-shape.svg" alt="image shape" class="">
                      </div>
                  </div>
              </div>
              <div class="col-xxl-6 col-xl-7 col-lg-8 col-md-10  " data-aos-duration="1000" data-aos="fade-right">
                  <div class="content">
                      <div class="content-text-block">
                          <h2 class="content-title heading-md text-black">
                              Aynı Zamanda İslami Video İçerikleri Üreten Bir Fabrika
                          </h2>
                          <p>Dünya çok büyüdü. Fiziksel olarak her mahalleye girip her eve İslamın mesajını ulaştırmak zorlaştı.
                              Derdi veren Allah devayı da yaratır. Bir internet kablosuyla tüm evlere girmekle Kuran’ın mesajını
                              ulaştırmak mümkün.</p>
                      </div>

                  </div>
              </div>
          </div>
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
  <div class="home-3_video-section section-padding" id="courses">
      <div class="home-3_video-shape">
          <img src="../public/assets/image/home-3/video-shape.svg" alt="">
      </div>
      <div class="container">
          <div class="row justify-content-center text-center">
              <div class=" col-xl-10 col-lg-11 col-md-12 ">
                  <div class="d-flex align-items-center justify-content-center gap-3 mb-5">
                      <img src="../public/assets/image/icons/ic_youtube.png" alt="youtube" class="youtube-icon">
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
                                  <img src="<?= $videoService->getYouTubeThumbnail($video['url']) ?>" alt="<?= htmlspecialchars($video['title']) ?>">
                                  <a href="<?= $video['url'] ?>" data-fancybox
                                      class="btn-play absolute-center btn-play--outline btn-play--70">
                                      <i class="fa-solid fa-play"></i>
                                  </a>
                              </div>
                          </div>
                          <h3 class="video-widget__title"><?= htmlspecialchars($video['title']) ?></h3>
                          <p><?= htmlspecialchars(substr($video['description'], 0, 100)) ?><?= strlen($video['description']) > 100 ? '...' : '' ?></p>
                      </div>
                  </div>
                  <?php endforeach; ?>
              <?php else: ?>
                  <!-- Fallback statik videolar -->
                  <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                      <div class="video-widget">
                          <div class="video-widget__thumbnail-wrapper">
                              <div class="video-widget__thumbnail">
                                  <img src="../public/assets/image/home-3/video-thumbnail-1.png" alt="image alt">
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

  <div class="home-8_blog-section padding-bottom-120 bg-cream" id="blog">
      <div class="container">
          <div class="row justify-content-center text-center">
              <div class="col-xxl-10 col-xl-11 col-lg-12 col-md-12 col-auto">
                  <div class="section-heading">
                      <h2 class="section-heading__title heading-lg text-black-2">MAKALELER
                      </h2>
                      <p>Osman Sungur Yeken'in kaleminden, iman hakikatleri, siyer, sahabeler ve birçok önemli konuda
                          derinleşen makaleleriyle sizlere bilgi dolu bir platform sunuyoruz. İslam’ın temel prensiplerini
                          anlamak, tarih boyunca yaşanmış örnekleri keşfetmek ve günlük hayatımıza anlam katmak için yazıldı.</p>
                  </div>
              </div>
          </div>
          <div class="row gutter-y-default justify-content-center">
              <?php if (!empty($homeBlogs)): ?>
                  <?php foreach ($homeBlogs as $blog): ?>
                  <div class="col-lg-4 col-md-6 col-sm-8">
                      <div class="blog-card h-100">
                          <div class="blog-card__image">
                              <img src="<?= $blogService->getImagePath($blog['cover_image']) ?>" alt="<?= htmlspecialchars($blog['title']) ?>">
                              <a href="<?= BASE_URL ?>/blog-details?slug=<?= $blog['slug'] ?>" class="blog-card__badge">
                                  <?= htmlspecialchars($blog['category_name'] ?? 'Genel') ?>
                              </a>
                          </div>
                          <div class="blog-card__body">
                                                        <div class="blog-card__meta">
                              <span><i class="fa-regular fa-user me-2"></i><?= htmlspecialchars($blog['author_name'] ?? 'Osman Sungur Yeken') ?></span>
                              <span><i class="fa-regular fa-calendar-days me-2"></i><?= $blogService->formatDate($blog['created_at']) ?>
                              </span>
                          </div>
                              <h3 class="blog-card__title"><?= htmlspecialchars($blog['title']) ?></h3>
                              <a href="<?= BASE_URL ?>/blog-details?slug=<?= $blog['slug'] ?>" class="blog-card__link btn-link btn-arrow">Devamını oku</a>
                          </div>
                      </div>
                  </div>
                  <?php endforeach; ?>
              <?php else: ?>
                  <!-- Fallback statik makaleler -->
                  <div class="col-lg-4 col-md-6 col-sm-8">
                      <div class="blog-card h-100">
                          <div class="blog-card__image">
                              <img src="../public/assets/image/home-8/blog-image-1.png" alt="image alt">
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