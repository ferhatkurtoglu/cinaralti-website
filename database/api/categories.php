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

// CORS ayarları
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

// OPTIONS isteği için
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// HTTP method'u al
$method = $_SERVER['REQUEST_METHOD'];

// PDO bağlantısı
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4;unix_socket=" . DB_SOCKET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database bağlantısı kurulamadı: ' . $e->getMessage()]);
    exit;
}

// Category model'i oluştur - ARTIK GEREKMİYOR, SİLDİM

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

// UUID oluşturma fonksiyonu
function generateUUID() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

// Slug oluşturma fonksiyonu
function generateSlug($name) {
    $slug = strtolower(trim($name));
    $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    return trim($slug, '-');
}

// GET istekleri
if ($method === 'GET') {
    try {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $slug = isset($_GET['slug']) ? $_GET['slug'] : null;
        $type = isset($_GET['type']) ? $_GET['type'] : 'blog';
        
        // Tek kategori getir (ID ile)
        if ($id) {
            $sql = "SELECT * FROM blog_categories WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $category = $stmt->fetch();
            
            if (!$category) {
                errorResponse('Kategori bulunamadı', 404);
            }
            successResponse($category);
        }
        
        // Tek kategori getir (Slug ile)
        if ($slug) {
            $sql = "SELECT * FROM blog_categories WHERE slug = :slug";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':slug', $slug);
            $stmt->execute();
            $category = $stmt->fetch();
            
            if (!$category) {
                errorResponse('Kategori bulunamadı', 404);
            }
            successResponse($category);
        }
        
        // Tüm kategorileri getir
        $sql = "SELECT * FROM blog_categories WHERE type = :type ORDER BY name ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':type', $type);
        $stmt->execute();
        $categories = $stmt->fetchAll();
        
        successResponse($categories);
        
    } catch (Exception $e) {
        errorResponse('Bir hata oluştu: ' . $e->getMessage(), 500);
    }
}

// POST istekleri (Yeni kategori oluştur)
if ($method === 'POST') {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            errorResponse('Geçersiz JSON verisi');
        }
        
        // Zorunlu alanları kontrol et
        if (!isset($input['name'])) {
            errorResponse('Kategori adı zorunludur');
        }
        
        // Slug oluştur
        $slug = isset($input['slug']) ? $input['slug'] : generateSlug($input['name']);
        
        $data = [
            'id' => generateUUID(), // UUID ekle
            'name' => $input['name'],
            'slug' => $slug,
            'description' => isset($input['description']) ? $input['description'] : null,
            'type' => isset($input['type']) ? $input['type'] : 'blog'
        ];
        
        $sql = "INSERT INTO blog_categories (id, name, slug, description, type) VALUES (:id, :name, :slug, :description, :type)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $data['id']);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':slug', $data['slug']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':type', $data['type']);
        
        if ($stmt->execute()) {
            successResponse(['id' => $data['id']], 'Kategori başarıyla oluşturuldu');
        } else {
            errorResponse('Kategori oluşturulamadı', 500);
        }
        
    } catch (Exception $e) {
        errorResponse('Bir hata oluştu: ' . $e->getMessage(), 500);
    }
}

// PUT istekleri (Kategori güncelle)
if ($method === 'PUT') {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            errorResponse('Geçersiz JSON verisi');
        }
        
        // URL parametresinden ID al
        $categoryId = isset($_GET['id']) ? $_GET['id'] : null;
        
        if (!$categoryId) {
            errorResponse('Kategori ID gerekli');
        }
        
        // Mevcut kategoriyi kontrol et
        $sql = "SELECT * FROM blog_categories WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $categoryId);
        $stmt->execute();
        $existingCategory = $stmt->fetch();

        if (!$existingCategory) {
            errorResponse('Kategori bulunamadı', 404);
        }
        
        // Güncelleme verilerini hazırla
        $data = [
            'name' => isset($input['name']) ? $input['name'] : $existingCategory['name'],
            'slug' => isset($input['slug']) ? $input['slug'] : $existingCategory['slug'],
            'description' => isset($input['description']) ? $input['description'] : $existingCategory['description'],
            'type' => isset($input['type']) ? $input['type'] : $existingCategory['type']
        ];
        
        $sql = "UPDATE blog_categories SET name = :name, slug = :slug, description = :description, type = :type WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':slug', $data['slug']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':type', $data['type']);
        $stmt->bindParam(':id', $categoryId);

        if ($stmt->execute()) {
            successResponse(['id' => $categoryId], 'Kategori başarıyla güncellendi');
        } else {
            errorResponse('Kategori güncellenemedi', 500);
        }
        
    } catch (Exception $e) {
        errorResponse('Bir hata oluştu: ' . $e->getMessage(), 500);
    }
}

// DELETE istekleri (Kategori sil)
if ($method === 'DELETE') {
    try {
        // URL parametresinden ID al
        $categoryId = isset($_GET['id']) ? $_GET['id'] : null;
        
        if (!$categoryId) {
            errorResponse('Kategori ID gerekli');
        }
        
        // Kategori var mı kontrol et
        $sql = "SELECT * FROM blog_categories WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $categoryId);
        $stmt->execute();
        $existingCategory = $stmt->fetch();

        if (!$existingCategory) {
            errorResponse('Kategori bulunamadı', 404);
        }
        
        $sql = "DELETE FROM blog_categories WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $categoryId);

        if ($stmt->execute()) {
            successResponse(['id' => $categoryId], 'Kategori başarıyla silindi');
        } else {
            errorResponse('Kategori silinemedi', 500);
        }
        
    } catch (Exception $e) {
        errorResponse('Bir hata oluştu: ' . $e->getMessage(), 500);
    }
}

// Desteklenmeyen method
errorResponse('Desteklenmeyen HTTP metodu', 405); 