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

// HTTP method ve path'i al
$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'];

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

// UUID oluştur
function generateUUID() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
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
    
    try {
        // Tek video getir (ID ile)
        if ($id) {
            $sql = "SELECT 
                        v.id,
                        v.title,
                        v.description,
                        v.url,
                        v.thumbnail,
                        v.status,
                        v.featured,
                        v.tags,
                        v.created_at,
                        v.updated_at,
                        u.name as author_name,
                        u.email as author_email,
                        bc.name as category_name,
                        bc.slug as category_slug
                    FROM videos v
                    LEFT JOIN users u ON v.author_id = u.id
                    LEFT JOIN blog_categories bc ON v.category_id = bc.id
                    WHERE v.id = :id";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            $video = $stmt->fetch();
            if (!$video) {
                errorResponse('Video bulunamadı', 404);
            }
            
            // Yanıtı Next.js formatına dönüştür
            $formattedVideo = [
                'id' => $video['id'],
                'title' => $video['title'],
                'description' => $video['description'],
                'url' => $video['url'],
                'thumbnail' => $video['thumbnail'],
                'status' => $video['status'],
                'featured' => (bool)$video['featured'],
                'tags' => $video['tags'],
                'createdAt' => $video['created_at'],
                'updatedAt' => $video['updated_at'],
                'author' => [
                    'name' => $video['author_name'],
                ],
                'category' => $video['category_name'] ? [
                    'name' => $video['category_name'],
                ] : null,
            ];
            
            jsonResponse($formattedVideo);
        }
        
        // Tüm videoları getir
        $sql = "SELECT 
                    v.id,
                    v.title,
                    v.description,
                    v.url,
                    v.thumbnail,
                    v.status,
                    v.featured,
                    v.tags,
                    v.created_at,
                    v.updated_at,
                    u.name as author_name,
                    u.email as author_email,
                    bc.name as category_name,
                    bc.slug as category_slug
                FROM videos v
                LEFT JOIN users u ON v.author_id = u.id
                LEFT JOIN blog_categories bc ON v.category_id = bc.id";
        
        $params = [];
        $conditions = [];
        
        if ($status) {
            $conditions[] = "v.status = :status";
            $params['status'] = $status;
        }
        
        if ($featured) {
            $conditions[] = "v.featured = :featured";
            $params['featured'] = ($featured === 'true') ? 1 : 0;
        }
        
        if ($search) {
            $conditions[] = "(v.title LIKE :search OR v.description LIKE :search)";
            $params['search'] = "%{$search}%";
        }
        
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }
        
        $sql .= " ORDER BY v.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT :limit";
            $params['limit'] = $limit;
        }
        
        if ($offset) {
            $sql .= " OFFSET :offset";
            $params['offset'] = $offset;
        }
        
        $stmt = $pdo->prepare($sql);
        
        foreach ($params as $key => $value) {
            if ($key === 'limit' || $key === 'offset') {
                $stmt->bindValue(":$key", $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue(":$key", $value);
            }
        }
        
        $stmt->execute();
        $videos = $stmt->fetchAll();
        
        // Yanıtı Next.js formatına dönüştür
        $formattedVideos = array_map(function($video) {
            return [
                'id' => $video['id'],
                'title' => $video['title'],
                'description' => $video['description'],
                'url' => $video['url'],
                'thumbnail' => $video['thumbnail'],
                'status' => $video['status'],
                'featured' => (bool)$video['featured'],
                'tags' => $video['tags'],
                'createdAt' => $video['created_at'],
                'updatedAt' => $video['updated_at'],
                'author' => [
                    'name' => $video['author_name'],
                ],
                'category' => $video['category_name'] ? [
                    'name' => $video['category_name'],
                ] : null,
            ];
        }, $videos);
        
        jsonResponse($formattedVideos);
        
    } catch (Exception $e) {
        errorResponse('Bir hata oluştu: ' . $e->getMessage(), 500);
    }
}

// POST istekleri (Yeni video oluştur)
if ($method === 'POST') {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            errorResponse('Geçersiz JSON verisi');
        }
        
        // Zorunlu alanları kontrol et
        if (!isset($input['title']) || !isset($input['url']) || !isset($input['status'])) {
            errorResponse('Başlık, URL ve durum alanları zorunludur');
        }
        
        $data = [
            'id' => generateUUID(),
            'title' => $input['title'],
            'description' => isset($input['description']) ? $input['description'] : null,
            'url' => $input['url'],
            'thumbnail' => isset($input['thumbnail']) ? $input['thumbnail'] : null,
            'status' => $input['status'],
            'featured' => isset($input['featured']) ? (bool)$input['featured'] : false,
            'author_id' => isset($input['authorId']) ? $input['authorId'] : 1, // Varsayılan author
            'category_id' => isset($input['categoryId']) ? $input['categoryId'] : null,
            'tags' => isset($input['tags']) ? $input['tags'] : null
        ];
        
        $sql = "INSERT INTO videos (
                    id, title, description, url, thumbnail, status, featured, 
                    author_id, category_id, tags, created_at, updated_at
                ) VALUES (
                    :id, :title, :description, :url, :thumbnail, :status, :featured,
                    :author_id, :category_id, :tags, NOW(), NOW()
                )";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $data['id']);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':url', $data['url']);
        $stmt->bindParam(':thumbnail', $data['thumbnail']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':featured', $data['featured'], PDO::PARAM_BOOL);
        $stmt->bindParam(':author_id', $data['author_id']);
        $stmt->bindParam(':category_id', $data['category_id']);
        $stmt->bindParam(':tags', $data['tags']);
        
        if ($stmt->execute()) {
            successResponse(['id' => $data['id']], 'Video başarıyla oluşturuldu');
        } else {
            errorResponse('Video oluşturulamadı', 500);
        }
        
    } catch (Exception $e) {
        errorResponse('Bir hata oluştu: ' . $e->getMessage(), 500);
    }
}

// Desteklenmeyen method
errorResponse('Desteklenmeyen HTTP metodu', 405); 