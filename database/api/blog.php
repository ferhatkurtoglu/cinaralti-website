<?php

// Hata ayıklama için
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database bağlantısını doğrudan tanımla
define('DB_HOST', 'localhost');
define('DB_NAME', 'cinaralti_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_SOCKET', '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock');

require_once __DIR__ . '/../models/BlogModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';

// CORS ayarları
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

// OPTIONS isteği için
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// HTTP method ve path'i al
$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'];

// Blog model'i oluştur
$blogModel = new BlogModel();

// JSON yanıt fonksiyonu
function jsonResponse($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data);
    exit();
}

// Hata yanıtı fonksiyonu
function errorResponse($message, $status = 400) {
    jsonResponse(['error' => $message], $status);
}

// Başarı yanıtı fonksiyonu
function successResponse($data, $message = null) {
    $response = ['success' => true, 'data' => $data];
    if ($message) {
        $response['message'] = $message;
    }
    jsonResponse($response);
}

// GET istekleri
if ($method === 'GET') {
    // Parametreleri al
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : null;
    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : null;
    $status = isset($_GET['status']) ? $_GET['status'] : null;
    $search = isset($_GET['search']) ? $_GET['search'] : null;
    $featured = isset($_GET['featured']) ? $_GET['featured'] : null;
    $category = isset($_GET['category']) ? $_GET['category'] : null;
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    $slug = isset($_GET['slug']) ? $_GET['slug'] : null;
    
    try {
        // Tek post getir (ID ile)
        if ($id) {
            $post = $blogModel->getPostById($id);
            if (!$post) {
                errorResponse('Blog yazısı bulunamadı', 404);
            }
            successResponse($post);
        }
        
        // Tek post getir (Slug ile)
        if ($slug) {
            $post = $blogModel->getPostBySlug($slug);
            if (!$post) {
                errorResponse('Blog yazısı bulunamadı', 404);
            }
            successResponse($post);
        }
        
        // Arama
        if ($search) {
            $posts = $blogModel->searchPosts($search);
            successResponse($posts);
        }
        
        // Öne çıkan yazılar
        if ($featured) {
            $posts = $blogModel->getFeaturedPosts($limit ?: 5);
            successResponse($posts);
        }
        
        // Kategoriye göre
        if ($category) {
            $posts = $blogModel->getPostsByCategory($category);
            successResponse($posts);
        }
        
        // Tüm yazıları getir
        $posts = $blogModel->getAllPosts($limit, $offset, $status);
        successResponse($posts);
        
    } catch (Exception $e) {
        errorResponse('Bir hata oluştu: ' . $e->getMessage(), 500);
    }
}

// POST istekleri (Yeni post oluştur)
if ($method === 'POST') {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            errorResponse('Geçersiz JSON verisi');
        }
        
        // Zorunlu alanları kontrol et
        if (!isset($input['title']) || !isset($input['content']) || !isset($input['status'])) {
            errorResponse('Başlık, içerik ve durum alanları zorunludur');
        }
        
        // Slug oluştur
        $slug = isset($input['slug']) ? $input['slug'] : $blogModel->generateSlug($input['title']);
        
        // Excerpt oluştur (eğer verilmemişse)
        if (!isset($input['excerpt']) || empty($input['excerpt'])) {
            $input['excerpt'] = substr(strip_tags($input['content']), 0, 200) . '...';
        }
        
        $data = [
            'title' => $input['title'],
            'content' => $input['content'],
            'excerpt' => $input['excerpt'],
            'slug' => $slug,
            'status' => $input['status'],
            'featured' => isset($input['featured']) ? (bool)$input['featured'] : false,
            'cover_image' => isset($input['cover_image']) ? $input['cover_image'] : null,
            'author_id' => isset($input['author_id']) ? $input['author_id'] : 1, // Varsayılan author
            'category_id' => isset($input['category_id']) ? $input['category_id'] : null,
            'tags' => isset($input['tags']) ? $input['tags'] : null
        ];
        
        $postId = $blogModel->createPost($data);
        
        if ($postId) {
            successResponse(['id' => $postId], 'Blog yazısı başarıyla oluşturuldu');
        } else {
            errorResponse('Blog yazısı oluşturulamadı', 500);
        }
        
    } catch (Exception $e) {
        errorResponse('Bir hata oluştu: ' . $e->getMessage(), 500);
    }
}

// PUT istekleri (Post güncelle)
if ($method === 'PUT') {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            errorResponse('Geçersiz JSON verisi');
        }
        
        if (!isset($input['id'])) {
            errorResponse('Post ID gerekli');
        }
        
        $postId = $input['id'];
        
        // Mevcut post'u kontrol et
        $existingPost = $blogModel->getPostById($postId);
        if (!$existingPost) {
            errorResponse('Blog yazısı bulunamadı', 404);
        }
        
        // Güncelleme verilerini hazırla
        $data = [
            'title' => isset($input['title']) ? $input['title'] : $existingPost['title'],
            'content' => isset($input['content']) ? $input['content'] : $existingPost['content'],
            'excerpt' => isset($input['excerpt']) ? $input['excerpt'] : $existingPost['excerpt'],
            'slug' => isset($input['slug']) ? $input['slug'] : $existingPost['slug'],
            'status' => isset($input['status']) ? $input['status'] : $existingPost['status'],
            'featured' => isset($input['featured']) ? (bool)$input['featured'] : (bool)$existingPost['featured'],
            'cover_image' => isset($input['cover_image']) ? $input['cover_image'] : $existingPost['cover_image'],
            'category_id' => isset($input['category_id']) ? $input['category_id'] : $existingPost['category_id'],
            'tags' => isset($input['tags']) ? $input['tags'] : $existingPost['tags']
        ];
        
        $result = $blogModel->updatePost($postId, $data);
        
        if ($result) {
            successResponse(['id' => $postId], 'Blog yazısı başarıyla güncellendi');
        } else {
            errorResponse('Blog yazısı güncellenemedi', 500);
        }
        
    } catch (Exception $e) {
        errorResponse('Bir hata oluştu: ' . $e->getMessage(), 500);
    }
}

// DELETE istekleri (Post sil)
if ($method === 'DELETE') {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['id'])) {
            errorResponse('Post ID gerekli');
        }
        
        $postId = $input['id'];
        
        // Post'un var olup olmadığını kontrol et
        $existingPost = $blogModel->getPostById($postId);
        if (!$existingPost) {
            errorResponse('Blog yazısı bulunamadı', 404);
        }
        
        $result = $blogModel->deletePost($postId);
        
        if ($result) {
            successResponse(['id' => $postId], 'Blog yazısı başarıyla silindi');
        } else {
            errorResponse('Blog yazısı silinemedi', 500);
        }
        
    } catch (Exception $e) {
        errorResponse('Bir hata oluştu: ' . $e->getMessage(), 500);
    }
}

// Desteklenmeyen method
errorResponse('Desteklenmeyen HTTP metodu', 405); 