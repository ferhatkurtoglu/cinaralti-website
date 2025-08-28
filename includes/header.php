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
    <link rel="shortcut icon" href="<?= BASE_URL ?>/assets/image/favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?= BASE_URL ?>/assets/image/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="<?= BASE_URL ?>/assets/image/logo.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= BASE_URL ?>/assets/image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= BASE_URL ?>/assets/image/favicon-16x16.png">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Plugin'stylesheets  -->
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/assets/fonts/typography/fonts.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/fonts/fontawesome/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/assets/plugins/aos/aos.min.css">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/assets/plugins/fancybox/jquery.fancybox.min.css">
    <!-- Vendor stylesheets  -->
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/assets/plugins/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/assets/css/style.css">
    <style>
    @import url('https://fonts.cdnfonts.com/css/clash-display');
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Syne:wght@500;600;700&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Public+Sans:wght@500;600;700;800;900&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Cabin:wght@500;600;700&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap');
    @import url('https://fonts.cdnfonts.com/css/clash-display');

    /* Dropdown için genel override */
    .page-wrapper,
    .container,
    nav,
    .navbar,
    .menu-block,
    .menu-block-wrapper {
        overflow: visible !important;
    }

    /* Fixed header için body padding */
    body {
        padding-top: 115px !important;
        transition: padding-top 0.3s ease !important;
    }

    body.scroll-active {
        padding-top: 70px !important;
    }







    /* Üst Header Stilleri */
    .page-wrapper .top-header {
        background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%) !important;
        padding: 6px 0 !important;
        border-bottom: 1px solid rgba(248, 249, 255, 0.3) !important;
        font-size: 12px !important;
        min-height: 32px !important;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        z-index: 1002 !important;
        display: block !important;
        width: 100% !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05) !important;
        margin: 0 !important;
        transition: transform 0.3s ease, opacity 0.3s ease !important;
    }

    /* Scroll edildiğinde üst header gizlenir */
    .page-wrapper .top-header.scroll-hidden {
        transform: translateY(-100%) !important;
        opacity: 0 !important;
    }

    .top-header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .contact-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    /* Üst Header Contact Dropdown */
    .top-header-contact {
        flex: 1;
        display: flex;
        justify-content: center;
    }

    .contact-dropdown {
        position: relative;
        z-index: 99999 !important;
    }

    .contact-trigger {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #495057;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        padding: 6px 12px;
        border-radius: 5px;
        transition: all 0.3s ease;
        background: rgba(82, 198, 83, 0.05);
        border: 1px solid rgba(82, 198, 83, 0.2);
    }

    .contact-trigger:hover {
        background-color: rgba(82, 198, 83, 0.1);
        color: #52c653;
        border-color: rgba(82, 198, 83, 0.3);
        transform: translateY(-1px);
    }

    .contact-trigger i:first-child {
        color: #52c653;
        font-size: 10px;
    }

    .contact-trigger i:last-child {
        font-size: 8px;
        transition: transform 0.3s ease;
    }

    .contact-dropdown:hover .contact-trigger i:last-child {
        transform: rotate(180deg);
    }

    .contact-dropdown-menu {
        position: absolute !important;
        top: 100% !important;
        left: 50% !important;
        transform: translateX(-50%) !important;
        background: white !important;
        border-radius: 8px !important;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        padding: 10px 0 !important;
        min-width: 200px !important;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease !important;
        z-index: 99999 !important;
        margin-top: 5px !important;
    }

    .contact-dropdown:hover .contact-dropdown-menu {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(0);
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 20px;
        color: #666;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .contact-item:hover {
        background-color: #f8f9fa;
        color: #52c653;
        text-decoration: none;
    }

    .contact-item i {
        width: 16px;
        color: #999;
        font-size: 12px;
    }

    .contact-item:hover i {
        color: #52c653;
    }

    .phone-number {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #495057;
        font-weight: 600;
        font-size: 12px;
        padding: 4px 8px;
        border-radius: 4px;
        transition: all 0.3s ease;
    }

    .phone-number:hover {
        background: rgba(76, 175, 80, 0.1);
        color: #4CAF50;
    }

    .phone-number i {
        color: #4CAF50;
        font-size: 11px;
    }

    .follow-text {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #28a745;
        font-weight: 600;
        font-size: 12px;
        padding: 4px 8px;
        border-radius: 4px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .follow-text:hover {
        background: rgba(40, 167, 69, 0.1);
        color: #1e7e34;
    }

    .follow-text i {
        font-size: 10px;
        color: #28a745;
    }

    .follow-text i:last-child {
        font-size: 8px;
        margin-left: 2px;
        transition: transform 0.3s ease;
    }

    /* Sosyal Medya Dropdown */
    .social-dropdown {
        position: relative;
        z-index: 99999 !important;
    }

    .social-dropdown:hover .follow-text i:last-child {
        transform: rotate(180deg);
    }

    .social-dropdown-menu {
        position: absolute !important;
        top: 100% !important;
        left: 50% !important;
        transform: translateX(-50%) !important;
        background: white !important;
        border-radius: 8px !important;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        padding: 10px 0 !important;
        min-width: 180px !important;
        opacity: 0;
        visibility: hidden;
        transform: translateX(-50%) translateY(-10px);
        transition: all 0.3s ease !important;
        z-index: 99999 !important;
        margin-top: 5px !important;
    }

    .social-dropdown:hover .social-dropdown-menu {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(0);
    }

    .social-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 20px;
        color: #666;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .social-item:hover {
        background-color: #f8f9fa;
        text-decoration: none;
    }

    .social-item i {
        width: 18px;
        font-size: 16px;
        text-align: center;
    }

    /* Sosyal medya ikonları için renkler */
    .social-item:hover i.fa-twitter {
        color: #1DA1F2;
    }

    .social-item:hover i.fa-instagram {
        color: #E4405F;
    }

    .social-item:hover i.fa-youtube {
        color: #FF0000;
    }

    .social-item:hover i.fa-tiktok {
        color: #000000;
    }

    .social-item:hover i.fa-facebook {
        color: #1877F2;
    }

    .social-item:hover span {
        color: #333;
    }

    .top-header-right {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .currency,
    .language {
        color: #6c757d;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 6px;
        border-radius: 3px;
        background: rgba(108, 117, 125, 0.1);
        transition: all 0.3s ease;
    }

    .currency:hover,
    .language:hover {
        background: rgba(108, 117, 125, 0.2);
        color: #495057;
    }

    /* Ana Header Stilleri */
    .page-wrapper .site-header {
        background: white !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
        position: fixed !important;
        top: 32px !important;
        left: 0 !important;
        right: 0 !important;
        z-index: 1001 !important;
        margin: 0 !important;
        overflow: visible !important;
        width: 100% !important;
        transition: all 0.3s ease !important;
    }

    /* Scroll edildiğinde ana header küçülür ve üste yapışır */
    .page-wrapper .site-header.scroll-compact {
        top: 0 !important;
        padding: 10px 0 !important;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15) !important;
    }

    .page-wrapper .site-header.scroll-compact .site-navbar {
        padding: 8px 0 !important;
        min-height: 60px !important;
    }

    .page-wrapper .site-header.scroll-compact .brand-logo img {
        width: 45px !important;
        height: 45px !important;
        transition: all 0.3s ease !important;
    }

    .page-wrapper .site-header.scroll-compact .brand-text {
        font-size: 22px !important;
        transition: all 0.3s ease !important;
    }

    .page-wrapper .site-header.scroll-compact .nav-link-item {
        font-size: 10px !important;
        padding: 4px 8px !important;
        transition: all 0.3s ease !important;
    }

    .page-wrapper .site-header.scroll-compact .btn-donate {
        height: 40px !important;
        padding: 0 20px !important;
        font-size: 14px !important;
        transition: all 0.3s ease !important;
    }

    .page-wrapper .site-header.scroll-compact .btn-cart-header {
        width: 40px !important;
        height: 40px !important;
        transition: all 0.3s ease !important;
    }

    .page-wrapper .site-header.scroll-compact .btn-login {
        height: 40px !important;
        padding: 0 15px !important;
        font-size: 13px !important;
        transition: all 0.3s ease !important;
    }



    /* Yeni Layout - 3 Bölüm */
    .site-navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        z-index: 9998;
        overflow: visible !important;
        padding: 15px 0;
        min-height: 80px;
    }

    .left-menu {
        flex: 1;
        display: flex;
        justify-content: flex-start;
        overflow: visible !important;
        position: relative;
        z-index: 9999;
        max-width: 35%;
    }

    .brand-logo {
        flex: 0 0 auto;
        display: flex;
        justify-content: center;
        align-items: center;
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        z-index: 10;
    }

    .brand-logo img {
        width: 55px;
        height: 55px;
    }

    .header-cta-btn-wrapper {
        flex: 1;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        padding-right: 0;
        max-width: 35%;
    }

    /* Mega Menu Stilleri */
    .nav-item-has-mega {
        position: relative;
        z-index: 99999 !important;
    }

    .mega-menu {
        position: absolute !important;
        top: 100% !important;
        left: 0 !important;
        background: white !important;
        border-radius: 10px !important;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1) !important;
        padding: 30px !important;
        min-width: 800px !important;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s ease !important;
        z-index: 99999 !important;
    }

    .nav-item-has-mega:hover .mega-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .mega-menu-content {
        display: flex;
        gap: 40px;
    }

    .mega-menu-section {
        flex: 1;
        min-width: 200px;
    }

    .mega-menu-section.single-column {
        flex: none;
        width: 100%;
    }



    .mega-menu-section h3 {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 2px solid #52c653;
    }

    .mega-menu-section ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .mega-menu-section ul li {
        margin-bottom: 8px;
    }

    .mega-menu-section ul li a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 0;
        color: #666;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .mega-menu-section ul li a:hover {
        color: #52c653;
        padding-left: 5px;
    }

    .mega-menu-section ul li a i {
        width: 16px;
        color: #999;
        font-size: 12px;
    }

    .mega-menu-section ul li a:hover i {
        color: #52c653;
    }

    /* Login Butonu */
    .btn-login {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 44px;
        padding: 0 18px;
        border-radius: 8px;
        background: transparent;
        color: #333;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 1px solid #ddd;
        white-space: nowrap;
    }

    .btn-login:hover {
        background: #f8f9fa;
        color: #333;
        text-decoration: none;
    }

    .btn-cart-header {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 8px;
        background: #f2f9f4;
        color: #4CAF50;
        transition: all 0.3s ease;
        position: relative;
    }

    .btn-cart-header i {
        font-size: 16px;
    }

    .btn-cart-header:hover {
        background: #e8f5e8;
        transform: translateY(-1px);
    }

    .btn-donate {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 44px;
        padding: 0 24px;
        border-radius: 8px;
        background: #52c653;
        color: white;
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(82, 198, 83, 0.3);
        white-space: nowrap;
        border: none;
    }

    .btn-donate:hover {
        background: #45b846;
        color: white;
        text-decoration: none;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(82, 198, 83, 0.4);
    }

    .brand-logo a {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
    }

    .brand-text {
        font-size: 26px;
        font-weight: 700;
        color: #333;
        font-family: 'Inter', sans-serif;
        line-height: 1;
        margin: 0;
    }

    .nav-link-item {
        white-space: nowrap;
        font-size: 11px;
        padding: 6px 10px;
        font-weight: 600;
        color: #333;
        text-decoration: none;
        transition: all 0.3s ease;
        border-radius: 4px;
        letter-spacing: 0.3px;
    }

    .nav-link-item:hover {
        color: #52c653;
        text-decoration: none;
        background: rgba(82, 198, 83, 0.1);
        transform: translateY(-1px);
    }

    .site-menu-main {
        display: flex;
        align-items: center;
        gap: 2px;
        list-style: none;
        margin: 0;
        padding: 0;
        overflow: visible !important;
        position: relative;
        z-index: 9999;
    }

    /* Masaüstü ve büyük ekranlar */
    @media screen and (min-width: 1024px) {
        .top-header {
            padding: 6px 0;
        }

        .contact-info {
            gap: 25px;
        }

        .phone-number,
        .follow-text {
            font-size: 13px;
        }

        .phone-number i,
        .follow-text i {
            font-size: 11px;
        }

        .currency,
        .language {
            font-size: 12px;
        }

        /* Desktop contact dropdown */
        .contact-trigger {
            font-size: 13px;
            padding: 6px 12px;
        }

        .contact-dropdown-menu {
            min-width: 200px;
        }

        .contact-item {
            padding: 10px 20px;
            font-size: 13px;
        }

        .brand-logo img {
            width: 50px;
            height: 50px;
        }

        .brand-text {
            font-size: 24px;
        }

        .nav-link-item {
            font-size: 12px;
            padding: 0 12px;
        }

        .site-menu-main {
            gap: 15px;
        }

        .header-cta-btn-wrapper {
            gap: 12px;
        }

        .btn-cart-header {
            width: 44px;
            height: 44px;
            min-width: 44px;
            border-radius: 8px;
        }

        .btn-login {
            height: 44px;
            padding: 0 18px;
            font-size: 15px;
        }

        .btn-donate {
            height: 44px;
            padding: 0 24px;
            font-size: 15px;
            border-radius: 8px;
            font-weight: 600;
            white-space: nowrap;
        }

        /* Mega Menu Desktop */
        .mega-menu {
            min-width: 900px;
            padding: 40px;
        }

        .mega-menu-content {
            gap: 50px;
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

    /* Ekstra küçük ekranlar için header iyileştirmeleri */
    @media screen and (max-width: 480px) {
        .brand-logo {
            gap: 2px;
        }

        .brand-logo img {
            width: 30px;
            height: 30px;
        }

        .brand-text {
            font-size: 18px;
        }

        .header-cta-btn-wrapper {
            margin-right: 5px;
            gap: 5px;
        }

        .btn-cart-header {
            width: 30px;
            height: 30px;
            min-width: 30px;
        }

        .btn-cart-header i {
            font-size: 12px;
        }

        .btn-donate {
            height: 30px;
            padding: 0 10px;
            font-size: 12px;
        }
    }

    @media screen and (max-width: 360px) {
        .brand-text {
            font-size: 16px;
        }

        .btn-cart-header {
            width: 28px;
            height: 28px;
            min-width: 28px;
        }

        .btn-donate {
            height: 28px;
            padding: 0 8px;
            font-size: 11px;
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

    @media screen and (max-width: 1023.98px) {

        /* Tablet ve mobil için mega menüyü gizle */
        .mega-menu {
            display: none;
        }

        /* Mobil menü için layout değişikliği */
        .site-navbar {
            flex-direction: row;
            justify-content: space-between;
        }

        .left-menu {
            order: 3;
        }

        .brand-logo {
            position: static;
            transform: none;
            order: 1;
            flex: 0 0 auto;
        }

        .header-cta-btn-wrapper {
            order: 2;
            flex: 0 0 auto;
        }

        .mobile-menu-trigger {
            order: 4;
        }
    }

    @media screen and (max-width: 768px) {
        .top-header {
            padding: 6px 0;
            font-size: 12px;
        }

        .contact-info {
            gap: 15px;
        }

        .phone-number,
        .follow-text {
            font-size: 12px;
        }

        .currency,
        .language {
            font-size: 11px;
        }

        /* Mobil için contact dropdown */
        .top-header-contact {
            display: none;
        }

        /* Mobil için sosyal medya dropdown */
        .social-dropdown-menu {
            min-width: 160px !important;
            font-size: 13px !important;
        }

        .social-item {
            padding: 8px 15px !important;
            font-size: 13px !important;
        }

        .social-item i {
            width: 16px !important;
            font-size: 14px !important;
        }

        .top-header-content {
            justify-content: space-between;
        }

        /* Mobil menüde iletişim için geçici çözüm - mobil menü içinde iletişim linkleri eklenebilir */

        .brand-logo img {
            width: 45px;
            height: 45px;
        }

        .header-cta-btn-wrapper {
            gap: 6px;
            display: flex;
            align-items: center;
        }

        .btn-cart-header {
            width: 36px;
            height: 36px;
            min-width: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-cart-header i {
            font-size: 14px;
        }

        .btn-login {
            height: 36px;
            padding: 0 12px;
            font-size: 13px;
        }

        .btn-donate {
            height: 36px;
            padding: 0 16px;
            font-size: 13px;
            white-space: nowrap;
            min-width: max-content;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-link-item {
            font-size: 14px;
        }

        .navbar {
            padding: 15px 0;
        }
    }

    @media screen and (max-width: 480px) {
        .top-header {
            padding: 5px 0;
            font-size: 11px;
        }

        .contact-info {
            gap: 10px;
        }

        .phone-number,
        .follow-text {
            font-size: 11px;
        }

        .currency,
        .language {
            font-size: 10px;
        }

        .header-cta-btn-wrapper {
            gap: 4px;
        }

        .btn-cart-header {
            width: 32px;
            height: 32px;
            min-width: 32px;
            border-radius: 6px;
        }

        .btn-login {
            height: 32px;
            padding: 0 10px;
            font-size: 11px;
        }

        .btn-donate {
            height: 32px;
            padding: 0 12px;
            font-size: 12px;
            border-radius: 6px;
        }

        .btn-cart-header i {
            font-size: 12px;
        }

        .brand-logo img {
            width: 35px;
            height: 35px;
        }

        .navbar {
            padding: 10px 0;
        }

        .nav-link-item {
            font-size: 11px;
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
        .top-header {
            padding: 4px 0;
        }

        .contact-info {
            gap: 8px;
        }

        .phone-number,
        .follow-text {
            font-size: 10px;
        }

        .brand-logo img {
            width: 30px;
            height: 30px;
        }

        .btn-cart-header {
            width: 28px;
            height: 28px;
            min-width: 28px;
        }

        .btn-login {
            height: 28px;
            padding: 0 8px;
            font-size: 10px;
        }

        .btn-donate {
            height: 28px;
            padding: 0 10px;
            font-size: 11px;
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
        <!-- Üst Header -->
        <div class="top-header">
            <div class="container">
                <div class="top-header-content">
                    <div class="contact-info">
                        <span class="phone-number">
                            <i class="fas fa-phone"></i>
                            0 212 631 21 21
                        </span>
                        <div class="social-dropdown">
                            <span class="follow-text">
                                <i class="fas fa-check-circle"></i>
                                Takip Et
                                <i class="fas fa-angle-down"></i>
                            </span>
                            <div class="social-dropdown-menu">
                                <a href="#" class="social-item" target="_blank">
                                    <i class="fab fa-twitter"></i>
                                    <span>Twitter/X</span>
                                </a>
                                <a href="#" class="social-item" target="_blank">
                                    <i class="fab fa-instagram"></i>
                                    <span>Instagram</span>
                                </a>
                                <a href="#" class="social-item" target="_blank">
                                    <i class="fab fa-youtube"></i>
                                    <span>YouTube</span>
                                </a>
                                <a href="#" class="social-item" target="_blank">
                                    <i class="fab fa-tiktok"></i>
                                    <span>TikTok</span>
                                </a>
                                <a href="#" class="social-item" target="_blank">
                                    <i class="fab fa-facebook"></i>
                                    <span>Facebook</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="top-header-contact">
                        <div class="contact-dropdown">
                            <span class="contact-trigger">
                                <i class="fas fa-map-marker-alt"></i>
                                İletişim
                                <i class="fas fa-angle-down"></i>
                            </span>
                            <div class="contact-dropdown-menu">
                                <a href="<?= BASE_URL ?>/contact-ank" class="contact-item">
                                    <i class="fas fa-building"></i>
                                    <span>Çınaraltı - Ankara</span>
                                </a>
                                <a href="<?= BASE_URL ?>/contact-ist" class="contact-item">
                                    <i class="fas fa-building"></i>
                                    <span>Çınaraltı - İstanbul</span>
                                </a>
                                <a href="<?= BASE_URL ?>/contact-deu" class="contact-item">
                                    <i class="fas fa-globe"></i>
                                    <span>Çınaraltı - Almanya</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="top-header-right">
                        <span class="currency">TR</span>
                        <span class="language">TRY</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ana Header -->
        <header class="site-header site-header--transparent site-header--sticky">
            <div class="container">
                <nav class="navbar site-navbar">
                    <!-- Sol Menü -->
                    <div class="left-menu">
                        <div class="menu-block-wrapper">
                            <div class="menu-overlay"></div>
                            <nav class="menu-block" id="append-menu-header">
                                <div class="mobile-menu-head">
                                    <div class="current-menu-title"></div>
                                    <div class="mobile-menu-close">&times;</div>
                                </div>
                                <ul class="site-menu-main">
                                    <!-- Biz Kimiz Mega Menu -->
                                    <li class="nav-item nav-item-has-mega">
                                        <a href="<?= BASE_URL ?>/home" class="nav-link-item">KURUMSAL</a>
                                        <div class="mega-menu">
                                            <div class="mega-menu-content">
                                                <div class="mega-menu-section">
                                                    <h3>Kurumsal</h3>
                                                    <ul>
                                                        <li><a href="#"><i class="fas fa-file-alt"></i> Biz Kimiz</a>
                                                        </li>
                                                        <li><a href="#"><i class="fas fa-info-circle"></i>
                                                                Hakkımızda</a></li>
                                                        <li><a href="#"><i class="fas fa-history"></i> Tarihçe</a></li>
                                                        <li><a href="#"><i class="fas fa-users"></i> Yetkili
                                                                Kurullar</a></li>
                                                        <li><a href="#"><i class="fas fa-file-contract"></i> Vakıf
                                                                Kuruluş Senedi</a></li>
                                                        <li><a href="#"><i class="fas fa-clock"></i> Denetim</a></li>
                                                        <li><a href="#"><i class="fas fa-balance-scale"></i> Etik
                                                                Değerler</a></li>
                                                        <li><a href="#"><i class="fas fa-chart-line"></i> Uyum ve
                                                                Risk</a></li>
                                                        <li><a href="#"><i class="fas fa-newspaper"></i> Medya Odası</a>
                                                        </li>
                                                        <li><a href="#"><i class="fas fa-hands-helping"></i> İnsan
                                                                Kaynakları</a></li>
                                                    </ul>
                                                </div>
                                                <div class="mega-menu-section">
                                                    <h3>Bilgilendirme</h3>
                                                    <ul>
                                                        <li><a href="#"><i class="fas fa-shield-alt"></i> Kişisel
                                                                Verilerin Korunması Kanunu</a></li>
                                                        <li><a href="#"><i class="fas fa-lock"></i> Bilgi Güvenliği
                                                                Politikası</a></li>
                                                        <li><a href="#"><i class="fas fa-ban"></i> Yolsuzluk ve Rüşvetle
                                                                Mücadele</a></li>
                                                        <li><a href="#"><i class="fas fa-users"></i> Bağışçı Hakları</a>
                                                        </li>
                                                        <li><a href="#"><i class="fas fa-credit-card"></i> Gıda
                                                                Bankacılığı</a></li>
                                                    </ul>
                                                </div>
                                                <div class="mega-menu-section">
                                                    <h3>Dökümanlar</h3>
                                                    <ul>
                                                        <li><a href="#"><i class="fas fa-percentage"></i> Vergi
                                                                Muafiyeti</a></li>
                                                        <li><a href="#"><i class="fas fa-table"></i> Gelir Gider
                                                                Tablosu</a></li>
                                                        <li><a href="#"><i class="fas fa-file-pdf"></i> Bağımsız Denetim
                                                                Raporu</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <!-- Ne Yapıyoruz Mega Menu -->
                                    <li class="nav-item nav-item-has-mega">
                                        <a href="<?= BASE_URL ?>/about" class="nav-link-item">PROJELER</a>
                                        <div class="mega-menu">
                                            <div class="mega-menu-content">
                                                <div class="mega-menu-section">
                                                    <h3>Gündem</h3>
                                                    <ul>
                                                        <li><a href="#"><i class="fas fa-globe-asia"></i> Filistin /
                                                                Gazze</a></li>
                                                        <li><a href="#"><i class="fas fa-eye"></i> Katarakt</a></li>
                                                        <li><a href="#"><i class="fas fa-child"></i> Yetim</a></li>
                                                        <li><a href="#"><i class="fas fa-flag"></i> Suriye</a></li>
                                                        <li><a href="#"><i class="fas fa-tint"></i> Su</a></li>
                                                        <li><a href="#"><i class="fas fa-ship"></i> Mavi Marmara</a>
                                                        </li>
                                                        <li><a href="#"><i class="fas fa-users"></i> İyilikte Yarışan
                                                                Sınıflar</a></li>
                                                        <li><a href="#"><i class="fas fa-map-marker-alt"></i>
                                                                Türkiye</a></li>
                                                    </ul>
                                                </div>
                                                <div class="mega-menu-section">
                                                    <h3>Çalışma Alanları</h3>
                                                    <ul>
                                                        <li><a href="#"><i class="fas fa-box"></i> İnsani Yardım</a>
                                                        </li>
                                                        <li><a href="#"><i class="fas fa-users"></i> İnsan Hakları</a>
                                                        </li>
                                                        <li><a href="#"><i class="fas fa-comments"></i> İnsani
                                                                Diplomasi</a></li>
                                                        <li><a href="#"><i class="fas fa-first-aid"></i> Acil Yardım</a>
                                                        </li>
                                                        <li><a href="#"><i class="fas fa-globe"></i> Afet Yönetimi</a>
                                                        </li>
                                                        <li><a href="#"><i class="fas fa-hands"></i> Gönüllü
                                                                Faaliyetleri</a></li>
                                                        <li><a href="#"><i class="fas fa-bullhorn"></i>
                                                                Bilinçlendirme</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <!-- Destekle Mega Menu -->
                                    <li class="nav-item nav-item-has-mega">
                                        <a href="<?= BASE_URL ?>/video" class="nav-link-item">DESTEKLE</a>
                                        <div class="mega-menu">
                                            <div class="mega-menu-content">
                                                <div class="mega-menu-section">
                                                    <h3>Bağış Türleri</h3>
                                                    <ul>
                                                        <li><a href="<?= BASE_URL ?>/donate"><i
                                                                    class="fas fa-heart"></i> Bağış Yapın</a></li>
                                                        <li><a href="#"><i class="fas fa-smile"></i> Sponsor Olun</a>
                                                        </li>
                                                        <li><a href="#"><i class="fas fa-tint"></i> Su Kuyusu Açın</a>
                                                        </li>
                                                        <li><a href="#"><i class="fas fa-building"></i> Gayrimenkul
                                                                Bağışı</a></li>
                                                        <li><a href="#"><i class="fas fa-piggy-bank"></i> Kumbara
                                                                Alın</a></li>
                                                    </ul>
                                                </div>
                                                <div class="mega-menu-section">
                                                    <h3>Destek Yöntemleri</h3>
                                                    <ul>
                                                        <li><a href="#"><i class="fas fa-download"></i> Uygulamayı
                                                                İndirin</a></li>
                                                        <li><a href="#"><i class="fas fa-hands"></i> Gönüllü Olun</a>
                                                        </li>
                                                        <li><a href="#"><i class="fas fa-bell"></i> Haberdar Olun</a>
                                                        </li>
                                                        <li><a href="#"><i class="fas fa-share"></i> Paylaş</a></li>
                                                        <li><a href="#"><i class="fas fa-envelope"></i> Bize Ulaşın</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </li>


                                </ul>
                            </nav>
                        </div>
                    </div>

                    <!-- Orta - Brand Logo -->
                    <div class="brand-logo">
                        <a href="<?= BASE_URL ?>/" class="href">
                            <!-- Çınaraltı Logo -->
                            <img class="logo-light" src="<?= BASE_URL ?>/assets/image/logo.png" alt="Çınaraltı">
                            <img class="logo-dark" src="<?= BASE_URL ?>/assets/image/logo.png" alt="Çınaraltı">
                            <span class="brand-text">Çınaraltı</span>
                        </a>
                    </div>

                    <!-- Sağ - Header Butonları -->
                    <div class="header-cta-btn-wrapper">
                        <a href="#" class="btn-login">Oturum aç</a>
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

        // Scroll efekti için JavaScript
        let lastScrollTop = 0;
        const topHeader = document.querySelector('.top-header');
        const siteHeader = document.querySelector('.site-header');
        const body = document.body;

        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            if (scrollTop > 50) {
                // Aşağı scroll - header'ları küçült
                topHeader.classList.add('scroll-hidden');
                siteHeader.classList.add('scroll-compact');
                body.classList.add('scroll-active');
            } else {
                // Üste scroll - header'ları normale döndür
                topHeader.classList.remove('scroll-hidden');
                siteHeader.classList.remove('scroll-compact');
                body.classList.remove('scroll-active');
            }

            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
        });
        </script>
    </div>
</body>

</html>