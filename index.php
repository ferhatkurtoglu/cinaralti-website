<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Çınaraltı - Yönlendiriliyor</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        text-align: center;
        padding: 50px;
        background: #f5f5f5;
    }

    .container {
        max-width: 600px;
        margin: 0 auto;
        background: white;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .logo {
        color: #2c5aa0;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .message {
        color: #666;
        margin-bottom: 30px;
    }

    .btn {
        background: #2c5aa0;
        color: white;
        padding: 15px 30px;
        text-decoration: none;
        border-radius: 5px;
        display: inline-block;
    }

    .btn:hover {
        background: #1e3f73;
    }
    </style>
    <script>
    // 3 saniye sonra otomatik yönlendirme
    setTimeout(function() {
        window.location.href = './public/';
    }, 3000);
    </script>
</head>

<body>
    <div class="container">
        <div class="logo">🌳 Çınaraltı</div>
        <div class="message">
            <h2>Sitemize Hoş Geldiniz!</h2>
            <p>3 saniye içinde ana sayfaya yönlendirileceksiniz...</p>
            <p>Eğer yönlendirme otomatik olmazsa aşağıdaki butona tıklayın.</p>
        </div>
        <a href="./public/" class="btn">Ana Sayfaya Git</a>

        <hr style="margin: 30px 0;">
        <small style="color: #999;">
            <a href="debug_hosting.php">Hosting Debug Bilgileri</a>
        </small>
    </div>
</body>