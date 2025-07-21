<?php

class BlogModel {
    private $pdo;
    
    public function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4;unix_socket=" . DB_SOCKET;
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            throw new Exception("Database bağlantısı kurulamadı: " . $e->getMessage());
        }
    }
    
    public function prepare($sql) {
        return $this->pdo->prepare($sql);
    }
    
    // Tüm blog yazılarını getir
    public function getAllPosts($limit = null, $offset = null, $status = null) {
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
                LEFT JOIN blog_categories bc ON bp.category_id = bc.id";
        
        $params = [];
        
        if ($status) {
            $sql .= " WHERE bp.status = :status";
            $params['status'] = $status;
        }
        
        $sql .= " ORDER BY bp.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT :limit";
            $params['limit'] = $limit;
        }
        
        if ($offset) {
            $sql .= " OFFSET :offset";
            $params['offset'] = $offset;
        }
        
        $stmt = $this->pdo->prepare($sql);
        
        foreach ($params as $key => $value) {
            if ($key === 'limit' || $key === 'offset') {
                $stmt->bindValue(":$key", $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue(":$key", $value);
            }
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Tek blog yazısını getir
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
                WHERE bp.id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    // Slug ile blog yazısını getir
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
                WHERE bp.slug = :slug";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    // Yeni blog yazısı oluştur
    public function createPost($data) {
        $sql = "INSERT INTO blog_posts (
                    id, title, content, excerpt, slug, status, featured, 
                    cover_image, author_id, category_id, tags, created_at, updated_at
                ) VALUES (
                    :id, :title, :content, :excerpt, :slug, :status, :featured,
                    :cover_image, :author_id, :category_id, :tags, NOW(), NOW()
                )";
        
        $stmt = $this->pdo->prepare($sql);
        
        // UUID oluştur
        $data['id'] = $this->generateUUID();
        
        $stmt->bindParam(':id', $data['id']);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':content', $data['content']);
        $stmt->bindParam(':excerpt', $data['excerpt']);
        $stmt->bindParam(':slug', $data['slug']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':featured', $data['featured'], PDO::PARAM_BOOL);
        $stmt->bindParam(':cover_image', $data['cover_image']);
        $stmt->bindParam(':author_id', $data['author_id']);
        $stmt->bindParam(':category_id', $data['category_id']);
        $stmt->bindParam(':tags', $data['tags']);
        
        if ($stmt->execute()) {
            return $data['id'];
        }
        
        return false;
    }
    
    // Blog yazısını güncelle
    public function updatePost($id, $data) {
        $sql = "UPDATE blog_posts SET 
                    title = :title,
                    content = :content,
                    excerpt = :excerpt,
                    slug = :slug,
                    status = :status,
                    featured = :featured,
                    cover_image = :cover_image,
                    category_id = :category_id,
                    tags = :tags,
                    updated_at = NOW()
                WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':content', $data['content']);
        $stmt->bindParam(':excerpt', $data['excerpt']);
        $stmt->bindParam(':slug', $data['slug']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':featured', $data['featured'], PDO::PARAM_BOOL);
        $stmt->bindParam(':cover_image', $data['cover_image']);
        $stmt->bindParam(':category_id', $data['category_id']);
        $stmt->bindParam(':tags', $data['tags']);
        
        return $stmt->execute();
    }
    
    // Blog yazısını sil
    public function deletePost($id) {
        $sql = "DELETE FROM blog_posts WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
    
    // Kategori ID'sine göre blog yazılarını getir
    public function getPostsByCategory($categoryId) {
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
                    bc.name as category_name
                FROM blog_posts bp
                LEFT JOIN users u ON bp.author_id = u.id
                LEFT JOIN blog_categories bc ON bp.category_id = bc.id
                WHERE bp.category_id = :category_id
                ORDER BY bp.created_at DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':category_id', $categoryId);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Öne çıkan yazıları getir
    public function getFeaturedPosts($limit = 5) {
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
                    bc.name as category_name
                FROM blog_posts bp
                LEFT JOIN users u ON bp.author_id = u.id
                LEFT JOIN blog_categories bc ON bp.category_id = bc.id
                WHERE bp.featured = 1 AND bp.status = 'published'
                ORDER BY bp.created_at DESC
                LIMIT :limit";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Arama
    public function searchPosts($searchTerm) {
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
                    bc.name as category_name
                FROM blog_posts bp
                LEFT JOIN users u ON bp.author_id = u.id
                LEFT JOIN blog_categories bc ON bp.category_id = bc.id
                WHERE (bp.title LIKE :search OR bp.content LIKE :search OR bp.excerpt LIKE :search)
                AND bp.status = 'published'
                ORDER BY bp.created_at DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $searchTerm = "%$searchTerm%";
        $stmt->bindParam(':search', $searchTerm);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Slug'ın benzersiz olup olmadığını kontrol et
    public function isSlugUnique($slug, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM blog_posts WHERE slug = :slug";
        $params = [':slug' => $slug];
        
        if ($excludeId) {
            $sql .= " AND id != :id";
            $params[':id'] = $excludeId;
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchColumn() == 0;
    }
    
    // UUID oluştur
    private function generateUUID() {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
    
    // Slug oluştur
    public function generateSlug($title) {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Benzersizlik kontrolü
        $originalSlug = $slug;
        $counter = 1;
        
        while (!$this->isSlugUnique($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    // İstatistikler
    public function getStats() {
        $sql = "SELECT 
                    COUNT(*) as total_posts,
                    COUNT(CASE WHEN status = 'published' THEN 1 END) as published_posts,
                    COUNT(CASE WHEN status = 'draft' THEN 1 END) as draft_posts,
                    COUNT(CASE WHEN featured = 1 THEN 1 END) as featured_posts
                FROM blog_posts";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetch();
    }
} 