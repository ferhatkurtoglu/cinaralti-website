<?php

require_once __DIR__ . '/../Database.php';

class CategoryModel {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    // Tüm kategorileri getir
    public function getAllCategories($type = 'blog') {
        $sql = "SELECT * FROM blog_categories WHERE type = :type ORDER BY name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':type', $type);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Kategori ID'sine göre getir
    public function getCategoryById($id) {
        $sql = "SELECT * FROM blog_categories WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    // Slug ile kategori getir
    public function getCategoryBySlug($slug) {
        $sql = "SELECT * FROM blog_categories WHERE slug = :slug";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    // Yeni kategori oluştur
    public function createCategory($data) {
        $sql = "INSERT INTO blog_categories (
                    id, name, slug, description, type, created_at, updated_at
                ) VALUES (
                    :id, :name, :slug, :description, :type, NOW(), NOW()
                )";
        
        $stmt = $this->db->prepare($sql);
        
        // UUID oluştur
        $data['id'] = $this->generateUUID();
        
        $stmt->bindParam(':id', $data['id']);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':slug', $data['slug']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':type', $data['type']);
        
        if ($stmt->execute()) {
            return $data['id'];
        }
        
        return false;
    }
    
    // Kategori güncelle
    public function updateCategory($id, $data) {
        $sql = "UPDATE blog_categories SET 
                    name = :name,
                    slug = :slug,
                    description = :description,
                    type = :type,
                    updated_at = NOW()
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':slug', $data['slug']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':type', $data['type']);
        
        return $stmt->execute();
    }
    
    // Kategori sil
    public function deleteCategory($id) {
        $sql = "DELETE FROM blog_categories WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
    
    // Slug'ın benzersiz olup olmadığını kontrol et
    public function isSlugUnique($slug, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM blog_categories WHERE slug = :slug";
        $params = [':slug' => $slug];
        
        if ($excludeId) {
            $sql .= " AND id != :id";
            $params[':id'] = $excludeId;
        }
        
        $stmt = $this->db->prepare($sql);
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
    public function generateSlug($name) {
        $slug = strtolower(trim($name));
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
} 