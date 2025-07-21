<?php

// Blog service fonksiyonları
require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/../config/database.php';

class BlogService {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Yayınlanmış blog yazılarını getir
     */
    public function getPublishedPosts($limit = null, $offset = null) {
        $sql = "SELECT 
                    bp.id,
                    bp.title,
                    bp.content,
                    bp.excerpt,
                    bp.slug,
                    bp.status,
                    bp.featured,
                    bp.cover_image,
                    bp.tags,
                    bp.created_at,
                    bp.updated_at,
                    u.name as author_name,
                    u.email as author_email,
                    bc.name as category_name,
                    bc.slug as category_slug
                FROM blog_posts bp
                LEFT JOIN users u ON bp.author_id = u.id
                LEFT JOIN blog_categories bc ON bp.category_id = bc.id
                WHERE bp.status = 'published'
                ORDER BY bp.created_at DESC";
        
        $params = [];
        
        if ($limit) {
            $sql .= " LIMIT :limit";
            $params['limit'] = $limit;
        }
        
        if ($offset) {
            $sql .= " OFFSET :offset";
            $params['offset'] = $offset;
        }
        
        $stmt = $this->db->prepare($sql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Slug ile blog yazısını getir
     */
    public function getPostBySlug($slug) {
        $sql = "SELECT 
                    bp.id,
                    bp.title,
                    bp.content,
                    bp.excerpt,
                    bp.slug,
                    bp.status,
                    bp.featured,
                    bp.cover_image,
                    bp.tags,
                    bp.created_at,
                    bp.updated_at,
                    u.name as author_name,
                    u.email as author_email,
                    bc.name as category_name,
                    bc.slug as category_slug
                FROM blog_posts bp
                LEFT JOIN users u ON bp.author_id = u.id
                LEFT JOIN blog_categories bc ON bp.category_id = bc.id
                WHERE bp.slug = :slug AND bp.status = 'published'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    /**
     * ID ile blog yazısını getir
     */
    public function getPostById($id) {
        $sql = "SELECT 
                    bp.id,
                    bp.title,
                    bp.content,
                    bp.excerpt,
                    bp.slug,
                    bp.status,
                    bp.featured,
                    bp.cover_image,
                    bp.tags,
                    bp.created_at,
                    bp.updated_at,
                    u.name as author_name,
                    u.email as author_email,
                    bc.name as category_name,
                    bc.slug as category_slug
                FROM blog_posts bp
                LEFT JOIN users u ON bp.author_id = u.id
                LEFT JOIN blog_categories bc ON bp.category_id = bc.id
                WHERE bp.id = :id AND bp.status = 'published'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    /**
     * Blog kategorilerini getir
     */
    public function getCategories() {
        $sql = "SELECT 
                    bc.id,
                    bc.name,
                    bc.slug,
                    bc.description,
                    COUNT(bp.id) as post_count
                FROM blog_categories bc
                LEFT JOIN blog_posts bp ON bc.id = bp.category_id AND bp.status = 'published'
                WHERE bc.type = 'blog'
                GROUP BY bc.id, bc.name, bc.slug, bc.description
                ORDER BY bc.name";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Son blog yazılarını getir
     */
    public function getRecentPosts($limit = 3, $excludeId = null) {
        $sql = "SELECT 
                    bp.id,
                    bp.title,
                    bp.slug,
                    bp.created_at,
                    bp.cover_image
                FROM blog_posts bp
                WHERE bp.status = 'published'";
        
        if ($excludeId) {
            $sql .= " AND bp.id != :excludeId";
        }
        
        $sql .= " ORDER BY bp.created_at DESC LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        
        if ($excludeId) {
            $stmt->bindParam(':excludeId', $excludeId);
        }
        
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Blog yazısı arama
     */
    public function searchPosts($searchTerm) {
        $sql = "SELECT 
                    bp.id,
                    bp.title,
                    bp.content,
                    bp.excerpt,
                    bp.slug,
                    bp.cover_image,
                    bp.created_at,
                    u.name as author_name,
                    bc.name as category_name
                FROM blog_posts bp
                LEFT JOIN users u ON bp.author_id = u.id
                LEFT JOIN blog_categories bc ON bp.category_id = bc.id
                WHERE (bp.title LIKE :search OR bp.content LIKE :search OR bp.excerpt LIKE :search)
                AND bp.status = 'published'
                ORDER BY bp.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $searchTerm = "%$searchTerm%";
        $stmt->bindParam(':search', $searchTerm);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * İçerik özetini oluştur
     */
    public function generateExcerpt($content, $length = 150) {
        // HTML etiketlerini temizle
        $content = strip_tags($content);
        
        // Fazla boşlukları temizle
        $content = preg_replace('/\s+/', ' ', $content);
        
        // Belirtilen uzunlukta kes
        if (strlen($content) > $length) {
            $content = substr($content, 0, $length);
            $content = substr($content, 0, strrpos($content, ' '));
            $content .= '...';
        }
        
        return $content;
    }
    
    /**
     * Tarihi Türkçe formatta döndür
     */
    public function formatDate($date) {
        $turkishMonths = [
            'January' => 'Ocak',
            'February' => 'Şubat',
            'March' => 'Mart',
            'April' => 'Nisan',
            'May' => 'Mayıs',
            'June' => 'Haziran',
            'July' => 'Temmuz',
            'August' => 'Ağustos',
            'September' => 'Eylül',
            'October' => 'Ekim',
            'November' => 'Kasım',
            'December' => 'Aralık'
        ];
        
        $formatted = date('d F Y', strtotime($date));
        
        foreach ($turkishMonths as $english => $turkish) {
            $formatted = str_replace($english, $turkish, $formatted);
        }
        
        return $formatted;
    }
    
    /**
     * Blog yorumlarını getir
     */
    public function getComments($postId, $status = 'approved') {
        $sql = "SELECT 
                    bc.id,
                    bc.post_id,
                    bc.parent_id,
                    bc.name,
                    bc.email,
                    bc.website,
                    bc.comment,
                    bc.status,
                    bc.created_at,
                    bc.updated_at
                FROM blog_comments bc
                WHERE bc.post_id = :post_id";
        
        if ($status !== 'all') {
            $sql .= " AND bc.status = :status";
        }
        
        $sql .= " ORDER BY bc.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':post_id', $postId);
        
        if ($status !== 'all') {
            $stmt->bindParam(':status', $status);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Yorum ekle
     */
    public function addComment($data) {
        $sql = "INSERT INTO blog_comments (
                    id, post_id, parent_id, name, email, website, 
                    comment, status, ip_address, user_agent, created_at, updated_at
                ) VALUES (
                    :id, :post_id, :parent_id, :name, :email, :website,
                    :comment, :status, :ip_address, :user_agent, NOW(), NOW()
                )";
        
        $stmt = $this->db->prepare($sql);
        
        // UUID oluştur
        $data['id'] = $this->generateCommentId();
        
        $stmt->bindParam(':id', $data['id']);
        $stmt->bindParam(':post_id', $data['post_id']);
        $stmt->bindParam(':parent_id', $data['parent_id']);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':website', $data['website']);
        $stmt->bindParam(':comment', $data['comment']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':ip_address', $data['ip_address']);
        $stmt->bindParam(':user_agent', $data['user_agent']);
        
        if ($stmt->execute()) {
            return $data['id'];
        }
        
        return false;
    }
    
    /**
     * Yorum sayısını getir
     */
    public function getCommentCount($postId, $status = 'approved') {
        $sql = "SELECT COUNT(*) FROM blog_comments WHERE post_id = :post_id";
        
        if ($status !== 'all') {
            $sql .= " AND status = :status";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':post_id', $postId);
        
        if ($status !== 'all') {
            $stmt->bindParam(':status', $status);
        }
        
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    
    /**
     * Yorum durumunu güncelle
     */
    public function updateCommentStatus($commentId, $status) {
        $sql = "UPDATE blog_comments SET status = :status, updated_at = NOW() WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $commentId);
        $stmt->bindParam(':status', $status);
        
        return $stmt->execute();
    }
    
    /**
     * Yorum ID'si oluştur
     */
    private function generateCommentId() {
        return 'comment-' . uniqid() . '-' . time();
    }
    
    /**
     * IP adresini al
     */
    public function getClientIp() {
        $ipKeys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
    
    /**
     * Yorum verilerini doğrula
     */
    public function validateComment($data) {
        $errors = [];
        
        // Zorunlu alanlar
        if (empty($data['name'])) {
            $errors[] = 'İsim alanı zorunludur.';
        }
        
        if (empty($data['email'])) {
            $errors[] = 'E-posta alanı zorunludur.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Geçerli bir e-posta adresi giriniz.';
        }
        
        if (empty($data['comment'])) {
            $errors[] = 'Yorum alanı zorunludur.';
        }
        
        if (empty($data['post_id'])) {
            $errors[] = 'Blog yazısı bilgisi eksik.';
        }
        
        // Uzunluk kontrolleri
        if (strlen($data['name']) > 255) {
            $errors[] = 'İsim çok uzun (maksimum 255 karakter).';
        }
        
        if (strlen($data['email']) > 255) {
            $errors[] = 'E-posta çok uzun (maksimum 255 karakter).';
        }
        
        if (strlen($data['comment']) > 1000) {
            $errors[] = 'Yorum çok uzun (maksimum 1000 karakter).';
        }
        
        // Website URL kontrolü
        if (!empty($data['website']) && !filter_var($data['website'], FILTER_VALIDATE_URL)) {
            $errors[] = 'Geçerli bir web sitesi adresi giriniz.';
        }
        
        return $errors;
    }
    
    /**
     * Spam kontrolü (basit)
     */
    public function isSpam($data) {
        $spamWords = ['spam', 'casino', 'poker', 'viagra', 'cialis', 'pharmacy'];
        $content = strtolower($data['comment'] . ' ' . $data['name']);
        
        foreach ($spamWords as $word) {
            if (strpos($content, $word) !== false) {
                return true;
            }
        }
        
        // Çok fazla link var mı?
        if (substr_count($data['comment'], 'http') > 2) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Varsayılan blog görseli
     */
    public function getDefaultImage() {
        return "/public/assets/image/blog/blog-image-1.png";
    }
    
    /**
     * Blog görselini al
     */
    public function getImagePath($coverImage) {
        if (!empty($coverImage)) {
            // Eğer zaten tam yol varsa direkt döndür
            if (strpos($coverImage, '/') === 0) {
                // Content-admin'den gelen yollar için (/uploads/blog/ ile başlıyorsa)
                if (strpos($coverImage, '/uploads/blog/') === 0) {
                    return '/cinaralti-website/public' . $coverImage;
                }
                return $coverImage;
            }
            
            // Başında / yoksa assets klasöründen al (eski resimler için)
            return '/public/assets/image/blog/' . $coverImage;
        }
        return $this->getDefaultImage();
    }
}

// Yardımcı fonksiyon
function getBlogService() {
    return new BlogService();
} 