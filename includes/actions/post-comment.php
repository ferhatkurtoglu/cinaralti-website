<?php
session_start();
require_once __DIR__ . '/../../config/config.php';

// JSON yanıt için header ayarla
header('Content-Type: application/json');

// Form verilerini al
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
$postId = filter_input(INPUT_POST, 'post', FILTER_SANITIZE_STRING);

// Hata kontrolü
if (empty($name) || empty($email) || empty($comment) || empty($postId)) {
    echo json_encode([
        'success' => false,
        'message' => 'Tüm alanları doldurunuz.'
    ]);
    exit;
}

// E-posta formatını kontrol et
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false,
        'message' => 'Geçerli bir e-posta adresi giriniz.'
    ]);
    exit;
}

// Blogs.json dosyasını oku
$blogsFile = dirname(__DIR__) . '/../public/data/blogs.json';
$blogsData = ['blogs' => []];

if (file_exists($blogsFile)) {
    $blogsJson = file_get_contents($blogsFile);
    $blogsData = json_decode($blogsJson, true) ?: ['blogs' => []];
}

// Blog'u bul
$blogIndex = -1;
foreach ($blogsData['blogs'] as $index => $blog) {
    if ($blog['id'] === $postId) {
        $blogIndex = $index;
        break;
    }
}

if ($blogIndex === -1) {
    echo json_encode([
        'success' => false,
        'message' => 'Blog yazısı bulunamadı.'
    ]);
    exit;
}

// Yeni yorumu ekle
$newComment = [
    'id' => 'comment-' . uniqid(),
    'name' => $name,
    'email' => $email,
    'comment' => $comment,
    'date' => date('Y-m-d H:i:s'),
    'approved' => false
];

// Yorumları başlat (eğer yoksa)
if (!isset($blogsData['blogs'][$blogIndex]['comments'])) {
    $blogsData['blogs'][$blogIndex]['comments'] = [];
}

// Yorumu ekle
$blogsData['blogs'][$blogIndex]['comments'][] = $newComment;

// JSON dosyasına kaydet
if (!is_dir(dirname($blogsFile))) {
    mkdir(dirname($blogsFile), 0777, true);
}

if (file_put_contents($blogsFile, json_encode($blogsData, JSON_PRETTY_PRINT))) {
    echo json_encode([
        'success' => true,
        'message' => 'Yorumunuz başarıyla gönderildi. Onaylandıktan sonra yayınlanacaktır.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Yorum gönderilirken bir hata oluştu. Lütfen tekrar deneyin.'
    ]);
}
exit; 