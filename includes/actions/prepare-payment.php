<?php
// Tüm output'u yakalayalım ve hata raporlamasını kapalım
ob_start();
error_reporting(0);
ini_set('display_errors', 0);

// İlk olarak JSON header set et
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// POST kontrolü
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ob_clean();
    echo '{"success":false,"error":"Sadece POST istekleri kabul edilir"}';
    exit;
}

// Session başlat (minimum kod)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// JSON verisini al
$input = file_get_contents('php://input');
if (!$input) {
    ob_clean();
    echo '{"success":false,"error":"POST data yok"}';
    exit;
}

$data = json_decode($input, true);
if (!$data) {
    ob_clean();
    echo '{"success":false,"error":"JSON decode hatası"}';
    exit;
}

// Test modu
if (isset($data['test'])) {
    ob_clean();
    echo '{"success":true,"message":"prepare-payment.php çalışıyor"}';
    exit;
}

// Ana işlem
if (!isset($data['cart']) || !isset($data['totalAmount'])) {
    ob_clean();
    echo '{"success":false,"error":"cart veya totalAmount eksik"}';
    exit;
}

$cart = $data['cart'];
$totalAmount = (float)$data['totalAmount'];

if (empty($cart) || $totalAmount <= 0) {
    error_log("CART TO PAYMENT ERROR: Geçersiz sepet veya tutar - Cart count: " . count($cart) . ", Total: $totalAmount");
    ob_clean();
    echo '{"success":false,"error":"Geçersiz sepet veya tutar"}';
    exit;
}

// İlk item'den bilgileri al
$first = $cart[0];
$name = trim($first['donorName'] ?? '');
$email = trim($first['donorEmail'] ?? '');
$phone = trim($first['donorPhone'] ?? '');

// Boş bilgiler için varsayılan değerler ata
if (empty($name)) {
    $name = 'Anonim Bağışçı';
}
if (empty($email)) {
    $email = 'bagisci@example.com';
}
if (empty($phone)) {
    $phone = '+90 555 000 0000';
}

// Bağış türünü sepetteki ilk öğeden al
$donationType = trim($first['title'] ?? 'Genel Bağış'); // title field'ı bağış türünü içeriyor
if (empty($donationType)) {
    $donationType = 'Genel Bağış';
}

// Session'a kaydet
$_SESSION['cart_total'] = $totalAmount;
$_SESSION['donor_name'] = $name;
$_SESSION['donor_email'] = $email;
$_SESSION['donor_phone'] = $phone;
$_SESSION['donor_city'] = 'İstanbul';
$_SESSION['donor_type'] = 'Bireysel';
$_SESSION['donation_type'] = $donationType;
$_SESSION['donation_id'] = 1;

// Debug bilgilerini logla
error_log("CART TO PAYMENT: Başarılı transfer - Total: $totalAmount, Donor: $name, Cart items: " . count($cart));

// Başarılı yanıt
ob_clean();
echo '{"success":true,"message":"Hazır","totalAmount":' . $totalAmount . ',"donorName":"' . $name . '"}';
exit;