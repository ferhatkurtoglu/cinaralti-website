<?php

require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/includes/functions.php';

class VideoService {
    private $pdo;
    
    public function __construct() {
        $this->pdo = db_connect();
    }
    
    /**
     * Tüm videoları getirir (sayfalama ile)
     */
    public function getAllVideos($page = 1, $limit = 6) {
        try {
            $offset = ($page - 1) * $limit;
            
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
                        bc.name as category_name,
                        bc.slug as category_slug
                    FROM videos v
                    LEFT JOIN users u ON v.author_id = u.id
                    LEFT JOIN blog_categories bc ON v.category_id = bc.id
                    WHERE v.status = 'published'
                    ORDER BY v.created_at DESC
                    LIMIT :limit OFFSET :offset";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Video getirme hatası: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * ID'ye göre video getirir
     */
    public function getVideoById($id) {
        try {
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
                        v.category_id,
                        u.name as author_name,
                        bc.name as category_name,
                        bc.slug as category_slug
                    FROM videos v
                    LEFT JOIN users u ON v.author_id = u.id
                    LEFT JOIN blog_categories bc ON v.category_id = bc.id
                    WHERE v.id = :id AND v.status = 'published'";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Video getirme hatası: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Öne çıkan videoları getirir
     */
    public function getFeaturedVideos($limit = 3) {
        try {
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
                        bc.name as category_name,
                        bc.slug as category_slug
                    FROM videos v
                    LEFT JOIN users u ON v.author_id = u.id
                    LEFT JOIN blog_categories bc ON v.category_id = bc.id
                    WHERE v.status = 'published' AND v.featured = 1
                    ORDER BY v.created_at DESC
                    LIMIT :limit";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Öne çıkan video getirme hatası: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Video kategorilerini getirir
     */
    public function getVideoCategories() {
        try {
            $sql = "SELECT 
                        bc.id,
                        bc.name,
                        bc.slug,
                        COUNT(v.id) as video_count
                    FROM blog_categories bc
                    LEFT JOIN videos v ON bc.id = v.category_id AND v.status = 'published'
                    WHERE bc.type = 'video' OR bc.type = 'blog'
                    GROUP BY bc.id, bc.name, bc.slug
                    HAVING video_count > 0
                    ORDER BY bc.name";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Video kategorileri getirme hatası: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Toplam video sayısını getirir
     */
    public function getTotalVideoCount() {
        try {
            $sql = "SELECT COUNT(*) as total FROM videos WHERE status = 'published'";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
            
        } catch (Exception $e) {
            error_log("Toplam video sayısı getirme hatası: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Video arama fonksiyonu
     */
    public function searchVideos($searchTerm, $page = 1, $limit = 6) {
        try {
            $offset = ($page - 1) * $limit;
            $searchTerm = "%{$searchTerm}%";
            
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
                        bc.name as category_name,
                        bc.slug as category_slug
                    FROM videos v
                    LEFT JOIN users u ON v.author_id = u.id
                    LEFT JOIN blog_categories bc ON v.category_id = bc.id
                    WHERE v.status = 'published' 
                    AND (v.title LIKE :search OR v.description LIKE :search)
                    ORDER BY v.created_at DESC
                    LIMIT :limit OFFSET :offset";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Video arama hatası: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * YouTube ID'sini URL'den çıkarır
     */
    public function extractYouTubeId($url) {
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches);
        return isset($matches[1]) ? $matches[1] : null;
    }
    
    /**
     * YouTube thumbnail URL'sini oluşturur
     */
    public function getYouTubeThumbnail($url, $quality = 'mqdefault') {
        $videoId = $this->extractYouTubeId($url);
        if ($videoId) {
            return "https://img.youtube.com/vi/{$videoId}/{$quality}.jpg";
        }
        return null;
    }
    
    /**
     * YouTube embed URL'sini oluşturur
     */
    public function getYouTubeEmbedUrl($url) {
        $videoId = $this->extractYouTubeId($url);
        if ($videoId) {
            return "https://www.youtube.com/embed/{$videoId}";
        }
        return $url;
    }
    
    /**
     * Kategoriye göre videoları getirir
     */
    public function getVideosByCategory($categorySlug, $page = 1, $limit = 6) {
        try {
            $offset = ($page - 1) * $limit;
            
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
                        bc.name as category_name,
                        bc.slug as category_slug
                    FROM videos v
                    LEFT JOIN users u ON v.author_id = u.id
                    LEFT JOIN blog_categories bc ON v.category_id = bc.id
                    WHERE v.status = 'published' AND bc.slug = :category_slug
                    ORDER BY v.created_at DESC
                    LIMIT :limit OFFSET :offset";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':category_slug', $categorySlug, PDO::PARAM_STR);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Kategoriye göre video getirme hatası: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Kategoriye göre toplam video sayısını getirir
     */
    public function getTotalVideoCountByCategory($categorySlug) {
        try {
            $sql = "SELECT COUNT(*) as total 
                    FROM videos v
                    LEFT JOIN blog_categories bc ON v.category_id = bc.id
                    WHERE v.status = 'published' AND bc.slug = :category_slug";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':category_slug', $categorySlug, PDO::PARAM_STR);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
            
        } catch (Exception $e) {
            error_log("Kategoriye göre toplam video sayısı getirme hatası: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Tarihi Türkçe formatına çevirir
     */
    public function formatDateTurkish($date) {
        $months = [
            '01' => 'Ocak', '02' => 'Şubat', '03' => 'Mart', '04' => 'Nisan',
            '05' => 'Mayıs', '06' => 'Haziran', '07' => 'Temmuz', '08' => 'Ağustos',
            '09' => 'Eylül', '10' => 'Ekim', '11' => 'Kasım', '12' => 'Aralık'
        ];
        
        $dateObj = new DateTime($date);
        $day = $dateObj->format('d');
        $month = $months[$dateObj->format('m')];
        $year = $dateObj->format('Y');
        
        return "{$day} {$month} {$year}";
    }
} 