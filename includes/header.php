<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="color-scheme" content="dark light">

    <title>
        <?php
// Sayfa tipine göre başlık ekliyoruz
$current_uri = $_SERVER['REQUEST_URI'];
$page_titles = [
    'home.php' => 'Ana Sayfa',
    'about.php' => 'Hakkımızda',
    'video.php' => 'Videolar',
    'blog.php' => 'Makaleler',
    'contact.php' => 'İletişim',
    'contact-ank.php' => 'Ankara İletişim',
    'contact-ist.php' => 'İstanbul İletişim',
    'contact-deu.php' => 'Almanya İletişim',
    'donate.php' => 'Bağış Yap',
    'donate-details.php' => 'Bağış Detayları',
    'cart.php' => 'Sepetim',
    'blog-details.php' => 'Makale Detayı',
    'faq.php' => 'SSS',
];

// URL'den hangi sayfada olduğumuzu tespit ediyoruz
$current_page = '';

if (strpos($current_uri, '/donate') !== false && strpos($current_uri, '/donate-details') === false) {
    $current_page = 'donate.php';
} elseif (strpos($current_uri, '/about') !== false) {
    $current_page = 'about.php';
} elseif (strpos($current_uri, '/video') !== false) {
    $current_page = 'video.php';
} elseif (strpos($current_uri, '/blog-details') !== false) {
    $current_page = 'blog-details.php';
} elseif (strpos($current_uri, '/blog') !== false) {
    $current_page = 'blog.php';
} elseif (strpos($current_uri, '/contact-ank') !== false) {
    $current_page = 'contact-ank.php';
} elseif (strpos($current_uri, '/contact-ist') !== false) {
    $current_page = 'contact-ist.php';
} elseif (strpos($current_uri, '/contact-deu') !== false) {
    $current_page = 'contact-deu.php';
} elseif (strpos($current_uri, '/contact') !== false) {
    $current_page = 'contact.php';
} elseif (strpos($current_uri, '/donate-details') !== false) {
    $current_page = 'donate-details.php';
} elseif (strpos($current_uri, '/cart') !== false) {
    $current_page = 'cart.php';
} elseif (strpos($current_uri, '/faq') !== false) {
    $current_page = 'faq.php';
} elseif (strpos($current_uri, '/career-details') !== false) {
    $current_page = 'career-details.php';
} elseif (strpos($current_uri, '/career') !== false) {
    $current_page = 'career.php';
} elseif (strpos($current_uri, '/zakat-calculator') !== false) {
    $current_page = 'zakat-calculator.php';
} elseif (strpos($current_uri, '/home') !== false || $current_uri === '/' || $current_uri === '') {
    $current_page = 'home.php';
}

// Tespit edilen sayfaya göre başlık yazdırıyoruz
if (isset($page_titles[$current_page])) {
    echo $page_titles[$current_page] . ' - ';
}
?>
        Çınaraltı
    </title>
    <link rel="shortcut icon" href="./../public/assets/image/favicon.ico" type="image/x-icon">
    <link rel="icon" href="./../public/assets/image/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="./../public/assets/image/logo.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./../public/assets/image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./../public/assets/image/favicon-16x16.png">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Plugin'stylesheets  -->
    <link rel="stylesheet" type="text/css" href="./../public/assets/fonts/typography/fonts.css">
    <link rel="stylesheet" href="./../public/assets/fonts/fontawesome/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="./../public/assets/plugins/aos/aos.min.css">
    <link rel="stylesheet" type="text/css" href="./../public/assets/plugins/fancybox/jquery.fancybox.min.css">
    <!-- Vendor stylesheets  -->
    <link rel="stylesheet" type="text/css" href="./../public/assets/plugins/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./../public/assets/css/style.css">
    <style>
    @import url('https://fonts.cdnfonts.com/css/clash-display');
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Syne:wght@500;600;700&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Public+Sans:wght@500;600;700;800;900&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Cabin:wght@500;600;700&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap');
    @import url('https://fonts.cdnfonts.com/css/clash-display');

    .btn-cart-header {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #f2f9f4;
        color: #4CAF50;
        transition: all 0.3s ease;
    }

    .btn-cart-header i {
        font-size: 16px;
    }

    .btn-donate {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 40px;
        padding: 0 15px;
        border-radius: 10px;
        background: #4CAF50;
        color: white;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-donate:hover {
        background: #3d9140;
        color: white;
        text-decoration: none;
    }

    .header-cta-btn-wrapper {
        display: flex;
        align-items: center;
        margin-left: auto;
        gap: 4px;
    }

    .brand-logo {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .brand-logo a {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
    }

    .nav-link-item {
        white-space: nowrap;
        font-size: 8px;
    }

    /* iPad Pro ve büyük tablet görünümü için medya sorguları */
    @media screen and (min-width: 1024px) {
        .brand-logo {
            gap: 6px;
        }

        .brand-logo a {
            gap: 6px;
        }

        .brand-logo img {
            width: 40px;
            height: 40px;
        }

        .brand-text {
            font-size: 24px;
        }

        .nav-link-item {
            font-size: 12px;
            padding: 0 6px;
        }

        .site-menu-main {
            margin-left: 12px;
            gap: 4px;
        }

        .header-cta-btn-wrapper {
            gap: 5px;
            margin-right: 5px;
        }

        .btn-cart-header {
            width: 44px;
            height: 44px;
            min-width: 44px;
            border-radius: 8px;
        }

        .btn-donate {
            height: 44px;
            padding: 0 16px;
            font-size: 15px;
            border-radius: 8px;
            font-weight: 500;
            white-space: nowrap;
        }
    }

    /* Tam 1024px ekran boyutu için özel düzenleme */
    @media screen and (width: 1024px) {
        .site-menu-main {
            gap: 2px;
        }

        .nav-link-item {
            font-size: 10px;
            padding: 0 2px;
        }

        .brand-text {
            font-size: 22px;
        }

        .btn-cart-header {
            width: 34px;
            height: 34px;
            min-width: 34px;
            margin-left: -25px;

        }

        .btn-donate {
            margin-left: -25px;
            height: 34px;
            padding: 0 12px;
            font-size: 12px;
            border-radius: 10px;
            background-color: #4CAF50;
            color: white;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
            white-space: nowrap;
        }
    }

    /* Masaüstü ve geniş ekranlar için ek medya sorgusu */
    @media screen and (min-width: 1366px) {
        .brand-logo {
            gap: 10px;
        }

        .brand-logo a {
            gap: 10px;
        }

        .brand-logo img {
            width: 55px;
            height: 55px;
        }

        .brand-text {
            font-size: 32px;
        }

        .nav-link-item {
            font-size: 14px;
            padding: 0 8px;
        }

        .site-menu-main {
            margin-left: 25px;
            gap: 12px;
        }

        .btn-cart-header {
            width: 50px;
            height: 50px;
            min-width: 50px;
        }

        .btn-cart-header i {
            font-size: 18px;
        }

        .btn-donate {
            height: 50px;
            padding: 0 20px;
            font-size: 16px;
            font-weight: 600;
        }
    }

    /* Tablet görünümü için medya sorguları */
    @media screen and (max-width: 1023.98px) {
        .brand-logo {
            gap: 4px;
        }

        .brand-logo a {
            gap: 4px;
            display: flex;
            align-items: center;
        }

        .brand-logo img {
            width: 45px;
            height: 45px;
        }

        .brand-text {
            font-size: 30px;
            line-height: 1;
        }

        .nav-link-item {
            font-size: 14px;
            padding: 0 8px;
        }

        .site-menu-main {
            margin-left: 25px;
            gap: 12px;
        }

        .header-cta-btn-wrapper {
            gap: 3px;
            margin-right: 8px;
        }

        .btn-cart-header {
            width: 44px;
            height: 44px;
        }

        .btn-donate {
            height: 44px;
            padding: 0 16px;
            font-size: 15px;
        }
    }

    @media screen and (max-width: 768px) {
        .brand-logo {
            gap: 4px;
        }

        .brand-logo a {
            gap: 4px;
        }

        .brand-logo img {
            width: 35px;
            height: 35px;
        }

        .brand-text {
            font-size: 22px;
        }

        .header-cta-btn-wrapper {
            margin-right: 10px;
            gap: 8px;
            display: flex;
            align-items: center;
        }

        .btn-cart-header {
            width: 34px;
            height: 34px;
            min-width: 34px;
            border-radius: 8px;
            margin-right: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-cart-header i {
            font-size: 14px;
        }

        .btn-donate {
            height: 34px;
            padding: 0 14px;
            font-size: 14px;
            white-space: nowrap;
            min-width: max-content;
            border-radius: 8px;
            background-color: #4CAF50;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-link-item {
            font-size: 15px;
        }

        .navbar {
            padding: 15px 0;
        }
    }

    @media screen and (max-width: 480px) {
        .header-cta-btn-wrapper {
            margin-right: 4px;
            gap: 6px;
        }

        .btn-cart-header {
            width: 30px;
            height: 30px;
            min-width: 30px;
            border-radius: 6px;
        }

        .btn-donate {
            height: 30px;
            padding: 0 12px;
            font-size: 12px;
            border-radius: 6px;
        }

        .btn-cart-header i {
            font-size: 12px;
        }

        .brand-logo {
            gap: 2px;
        }

        .brand-logo a {
            gap: 2px;
        }

        .brand-logo img {
            width: 25px;
            height: 25px;
        }

        .brand-text {
            font-size: 15px;
            line-height: 1;
        }

        .navbar {
            padding: 10px 0;
        }

        .nav-link-item {
            font-size: 12px;
        }
    }

    /* Çok küçük ekranlar için logo ve marka yazısı ayarları */
    @media screen and (max-width: 425px) {
        .brand-logo {
            gap: 4px;
        }

        .brand-logo a {
            gap: 4px;
        }

        .brand-logo img {
            width: 32px;
            height: 32px;
        }

        .brand-text {
            font-size: 18px;
            line-height: 1.1;
        }
    }

    /* En küçük mobil ekranlar için logo ayarları */
    @media screen and (max-width: 320px) {
        .brand-logo img {
            width: 28px;
            height: 28px;
        }

        .brand-text {
            font-size: 16px;
        }

        .brand-logo,
        .brand-logo a {
            gap: 3px;
        }
    }

    @media screen and (min-width: 768px) and (max-width: 1023.98px) {
        .brand-logo {
            gap: 4px;
        }

        .brand-logo a {
            gap: 4px;
        }

        .brand-logo img {
            width: 40px;
            height: 40px;
        }

        .brand-text {
            font-size: 28px;
            line-height: 1.2;
        }

        .header-cta-btn-wrapper {
            margin-right: 12px;
            gap: 8px;
        }

        .btn-cart-header {
            width: 40px;
            height: 40px;
            min-width: 40px;
        }

        .btn-cart-header i {
            font-size: 16px;
        }

        .btn-donate {
            height: 40px;
            padding: 0 16px;
            font-size: 15px;
        }
    }

    /* Sepet badge stili */
    .btn-cart-header {
        position: relative;
    }

    .cart-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background-color: #ff5722;
        color: white;
        font-size: 10px;
        font-weight: bold;
        min-width: 16px;
        height: 16px;
        border-radius: 50%;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 0 2px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
    }

    @media screen and (min-width: 1366px) {
        .cart-badge {
            min-width: 20px;
            height: 20px;
            font-size: 12px;
            top: -8px;
            right: -8px;
        }
    }

    @media screen and (max-width: 480px) {
        .cart-badge {
            min-width: 14px;
            height: 14px;
            font-size: 9px;
            top: -4px;
            right: -4px;
        }
    }
    </style>
</head>

<body>
    <div class="preloader-wrapper">
        <div class="lds-ellipsis">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>

    <div class="page-wrapper overflow-hidden">
        <header class="site-header site-header--transparent site-header--sticky">
            <div class="container">
                <nav class="navbar site-navbar">
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~
            Brand Logo
        ~~~~~~~~~~~~~~~~~~~~~~~~~~~-->

                    <div class="brand-logo">
                        <a href="<?= BASE_URL ?>/" class="href">
                            <!-- light version logo (logo must be black)-->
                            <img class="logo-light" src="../public/assets/image/logo.png" alt="brand logo">
                            <!-- Dark version logo (logo must be White)-->
                            <img class="logo-dark" src="../public/assets/image/logo.png" alt="brand logo">
                            <span class="brand-text">Çınaraltı</span>
                        </a>
                    </div>
                    <div class="menu-block-wrapper">
                        <div class="menu-overlay"></div>
                        <nav class="menu-block" id="append-menu-header">
                            <div class="mobile-menu-head">
                                <div class="current-menu-title"></div>
                                <div class="mobile-menu-close">&times;</div>
                            </div>
                            <ul class="site-menu-main">
                                <li class="nav-item">
                                    <a href="<?= BASE_URL ?>/home" class="nav-link-item">Ana Sayfa</i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= BASE_URL ?>/about" class="nav-link-item">Hakkımızda</i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= BASE_URL ?>/video" class="nav-link-item">Videolar</i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= BASE_URL ?>/blog" class="nav-link-item">Makaleler</i>
                                    </a>
                                </li>

                                <li class="nav-item nav-item-has-children">
                                    <a href="<?= BASE_URL ?>/contact" class="nav-link-item drop-trigger">İletişim<i
                                            class="fas fa-angle-down"></i>
                                    </a>
                                    <div class="sub-menu" id="submenu-13">
                                        <ul class="sub-menu_list">
                                            <li class="sub-menu_item">
                                                <a href="<?= BASE_URL ?>/contact-ank">
                                                    <span class="menu-item-text">Çınaraltı - Ankara</span>
                                                </a>
                                            </li>
                                            <li class="sub-menu_item">
                                                <a href="<?= BASE_URL ?>/contact-ist">
                                                    <span class="menu-item-text">Çınaraltı - İstanbul</span>
                                                </a>
                                            </li>
                                            <li class="sub-menu_item">
                                                <a href="<?= BASE_URL ?>/contact-deu">
                                                    <span class="menu-item-text">Çınaraltı - Almanya</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="header-cta-btn-wrapper">
                        <a href="<?= BASE_URL ?>/cart" class="btn-cart-header">
                            <i class="fas fa-shopping-bag"></i>
                            <span class="cart-badge" id="cartBadge"></span>
                        </a>
                        <a href="<?= BASE_URL ?>/donate" class="btn-donate">
                            <span>Bağış Yap</span>
                        </a>
                    </div>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~
          mobile menu trigger
         ~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <div class="mobile-menu-trigger">
                        <span></span>
                    </div>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~
            Mobile Menu Hamburger Ends
          ~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                </nav>
            </div>
        </header>

        <script>
        // Sepetteki bağış sayısını gösteren badge'i güncelleme fonksiyonu
        function updateCartBadge() {
            const cartBadge = document.getElementById('cartBadge');
            const cart = JSON.parse(localStorage.getItem('donationCart')) || [];

            if (cart.length > 0) {
                cartBadge.textContent = cart.length;
                cartBadge.style.display = 'flex';
            } else {
                cartBadge.style.display = 'none';
            }
        }

        // Sayfa yüklendiğinde ve localStorage değiştiğinde badge'i güncelle
        document.addEventListener('DOMContentLoaded', updateCartBadge);

        // localStorage değişikliklerini dinleme
        window.addEventListener('storage', function(e) {
            if (e.key === 'donationCart') {
                updateCartBadge();
            }
        });

        // Mevcut sayfada yapılan sepet değişikliklerini yakalamak için özel bir olay
        document.addEventListener('cartUpdated', updateCartBadge);

        // İlk yüklemede badge'i güncelle
        updateCartBadge();
        </script>
    </div>
</body>

</html>