<?php
require_once __DIR__ . '/../../config/config.php';
secure_session_start();
require_once __DIR__ . '/../blog-service.php';

// JSON yanıt için header ayarla
header('Content-Type: application/json');

try {
    // Blog servisini başlat
    $blogService = getBlogService();
    
    // Form verilerini al ve temizle
    $data = [
        'name' => trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING)),
        'email' => trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL)),
        'website' => trim(filter_input(INPUT_POST, 'website', FILTER_SANITIZE_URL)),
        'comment' => trim(filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING)),
        'post_id' => trim(filter_input(INPUT_POST, 'post', FILTER_SANITIZE_STRING)),
        'parent_id' => trim(filter_input(INPUT_POST, 'parent_id', FILTER_SANITIZE_STRING)) ?: null,
    ];
    
    // Veri doğrulama
    $errors = $blogService->validateComment($data);
    
    if (!empty($errors)) {
        echo json_encode([
            'success' => false,
            'message' => implode(' ', $errors)
        ]);
        exit;
    }
    
    // Blog yazısının var olup olmadığını kontrol et
    $post = $blogService->getPostById($data['post_id']);
    if (!$post) {
        echo json_encode([
            'success' => false,
            'message' => 'Blog yazısı bulunamadı.'
        ]);
        exit;
    }
    
    // Spam kontrolü
    if ($blogService->isSpam($data)) {
        echo json_encode([
            'success' => false,
            'message' => 'Yorumunuz spam olarak algılandı. Lütfen tekrar deneyin.'
        ]);
        exit;
    }
    
    // Yorum verilerini hazırla
    $commentData = [
        'post_id' => $data['post_id'],
        'parent_id' => $data['parent_id'],
        'name' => $data['name'],
        'email' => $data['email'],
        'website' => $data['website'],
        'comment' => $data['comment'],
        'status' => 'pending', // Varsayılan olarak onay bekliyor
        'ip_address' => $blogService->getClientIp(),
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
    ];
    
    // Yorumu veritabanına ekle
    $commentId = $blogService->addComment($commentData);
    
    if ($commentId) {
        echo json_encode([
            'success' => true,
            'message' => 'Yorumunuz başarıyla gönderildi. Onaylandıktan sonra yayınlanacaktır.',
            'comment_id' => $commentId
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Yorum gönderilirken bir hata oluştu. Lütfen tekrar deneyin.'
        ]);
    }
    
} catch (Exception $e) {
    // Hata durumunda
    echo json_encode([
        'success' => false,
        'message' => 'Bir hata oluştu. Lütfen tekrar deneyin.'
    ]);
}

exit; 