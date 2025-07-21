-- ================================================================
-- Blog Sistemi Migration
-- Tarih: 2024-01-04
-- Açıklama: Blog kategorileri ve blog yazıları tablolarını oluştur
-- ================================================================

-- Blog kategorileri tablosu
CREATE TABLE IF NOT EXISTS blog_categories (
  id VARCHAR(191) NOT NULL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  description TEXT DEFAULT NULL,
  type VARCHAR(50) NOT NULL DEFAULT 'blog',
  created_at DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  updated_at DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  
  KEY idx_slug (slug),
  KEY idx_type (type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Blog yazıları tablosu
CREATE TABLE IF NOT EXISTS blog_posts (
  id VARCHAR(191) NOT NULL PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  excerpt TEXT DEFAULT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  status VARCHAR(50) NOT NULL DEFAULT 'draft',
  featured TINYINT(1) NOT NULL DEFAULT 0,
  cover_image VARCHAR(255) DEFAULT NULL,
  author_id INT(11) NOT NULL,
  category_id VARCHAR(191) DEFAULT NULL,
  tags VARCHAR(500) DEFAULT NULL,
  created_at DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  updated_at DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  
  KEY idx_slug (slug),
  KEY idx_author_id (author_id),
  KEY idx_category_id (category_id),
  KEY idx_status (status),
  KEY idx_featured (featured),
  KEY idx_blog_posts_status_featured (status, featured),
  
  CONSTRAINT blog_posts_author_id_fkey FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT blog_posts_category_id_fkey FOREIGN KEY (category_id) REFERENCES blog_categories(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Varsayılan blog kategorilerini ekle
INSERT INTO blog_categories (id, name, slug, description, type) VALUES 
('blog-cat-1', 'Genel', 'genel', 'Genel blog yazıları', 'blog'),
('blog-cat-2', 'Dini Konular', 'dini-konular', 'Dini ve manevi konular', 'blog'),
('blog-cat-3', 'Duyurular', 'duyurular', 'Vakıf duyuruları', 'blog'),
('blog-cat-4', 'Etkinlikler', 'etkinlikler', 'Vakıf etkinlikleri', 'blog'),
('blog-cat-5', 'Projeler', 'projeler', 'Vakıf projeleri', 'blog')
ON DUPLICATE KEY UPDATE name=VALUES(name), description=VALUES(description); 