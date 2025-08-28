-- ================================================================
-- Çınaraltı Vakfı - Tam Kurulum Dosyası
-- Bu dosya tüm tabloları ve verileri tek seferde oluşturur
-- ================================================================

-- Önce mevcut tabloları temizle (dikkatli olun!)
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS system_logs;
DROP TABLE IF EXISTS blog_comments;
DROP TABLE IF EXISTS blog_posts;
DROP TABLE IF EXISTS videos;
DROP TABLE IF EXISTS blog_categories;
DROP TABLE IF EXISTS contact_messages;
DROP TABLE IF EXISTS donations_made;
DROP TABLE IF EXISTS donation_option_categories;
DROP TABLE IF EXISTS donation_options;
DROP TABLE IF EXISTS donation_categories;
DROP TABLE IF EXISTS settings;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;

-- ================================================================
-- 1. KULLANICI VE YÖNETİM TABLOLARI
-- ================================================================

-- Kullanıcılar tablosu
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin', 'editor', 'viewer') NOT NULL DEFAULT 'viewer',
  avatar VARCHAR(255) DEFAULT NULL,
  last_login DATETIME DEFAULT NULL,
  status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  session_token VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  KEY idx_email (email),
  KEY idx_role (role),
  KEY idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Site ayarları tablosu
CREATE TABLE settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  setting_key VARCHAR(255) NOT NULL UNIQUE,
  setting_value TEXT DEFAULT NULL,
  setting_group VARCHAR(255) DEFAULT 'general',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  KEY idx_setting_key (setting_key),
  KEY idx_setting_group (setting_group)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- 2. BAĞIŞ SİSTEMİ TABLOLARI
-- ================================================================

-- Bağış kategorileri tablosu
CREATE TABLE donation_categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  description TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  KEY idx_slug (slug),
  KEY idx_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bağış seçenekleri tablosu
CREATE TABLE donation_options (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  image VARCHAR(255) DEFAULT NULL,
  cover_image VARCHAR(255) DEFAULT NULL COMMENT 'Bağış türü kapak görselinin yolu',
  gallery_images JSON DEFAULT NULL COMMENT 'Bağış türü galeri görselleri (JSON dizi formatında)',
  description TEXT DEFAULT NULL,
  target_amount DECIMAL(12,2) DEFAULT '0.00',
  collected_amount DECIMAL(12,2) DEFAULT '0.00',
  position INT(11) DEFAULT '0',
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  KEY idx_slug (slug),
  KEY idx_is_active (is_active),
  KEY idx_position (position)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bağış seçeneği - kategori ilişki tablosu
CREATE TABLE donation_option_categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  donation_option_id INT NOT NULL,
  category_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (donation_option_id) REFERENCES donation_options(id) ON DELETE CASCADE,
  FOREIGN KEY (category_id) REFERENCES donation_categories(id) ON DELETE CASCADE,
  UNIQUE KEY unique_donation_option_category (donation_option_id, category_id),
  
  KEY idx_donation_option_id (donation_option_id),
  KEY idx_category_id (category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Yapılan bağışlar tablosu
CREATE TABLE donations_made (
  id INT AUTO_INCREMENT PRIMARY KEY,
  donation_option_id INT NOT NULL COMMENT 'İlgili bağış seçeneğinin ID si',
  donor_name VARCHAR(255) NOT NULL COMMENT 'Bağışçı adı',
  donor_email VARCHAR(255) NOT NULL COMMENT 'Bağışçı e-posta',
  donor_phone VARCHAR(50) DEFAULT NULL COMMENT 'Bağışçı telefon',
  city VARCHAR(100) DEFAULT NULL COMMENT 'Şehir bilgisi',
  amount DECIMAL(10,2) NOT NULL COMMENT 'Bağış miktarı',
  donation_option VARCHAR(50) NOT NULL COMMENT 'Bağış seçeneği',
  donation_category VARCHAR(100) DEFAULT NULL COMMENT 'Bağış kategorisi',
  donor_type VARCHAR(20) NOT NULL DEFAULT 'individual' COMMENT 'Bireysel veya kurumsal',
  payment_method VARCHAR(50) NOT NULL DEFAULT 'Banka',
  payment_status VARCHAR(20) NOT NULL DEFAULT 'pending' COMMENT 'Ödeme durumu',
  order_number VARCHAR(50) DEFAULT NULL COMMENT 'Ödeme sistemi sipariş numarası',
  payment_ref VARCHAR(255) DEFAULT NULL,
  donation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  note TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (donation_option_id) REFERENCES donation_options(id),
  
  KEY idx_donation_option_id (donation_option_id),
  KEY idx_payment_status (payment_status),
  KEY idx_donor_email (donor_email),
  KEY idx_created_at (created_at),
  KEY idx_order_number (order_number),
  KEY idx_donation_category (donation_category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='Yapılan bağışların kayıt tablosu';

-- ================================================================
-- 3. İLETİŞİM VE MESAJ TABLOLARI
-- ================================================================

-- İletişim formu mesajları tablosu
CREATE TABLE contact_messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  phone VARCHAR(50) DEFAULT NULL,
  subject VARCHAR(255) NOT NULL,
  message TEXT NOT NULL,
  status ENUM('Yeni', 'Okundu', 'Yanıtlandı', 'Arşivlendi') DEFAULT 'Yeni',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  KEY idx_status (status),
  KEY idx_created_at (created_at),
  KEY idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- 4. BLOG VE İÇERİK YÖNETİM TABLOLARI
-- ================================================================

-- Blog kategorileri tablosu
CREATE TABLE blog_categories (
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
CREATE TABLE blog_posts (
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
  
  CONSTRAINT blog_posts_author_id_fkey FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT blog_posts_category_id_fkey FOREIGN KEY (category_id) REFERENCES blog_categories(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Blog yorumları tablosu
CREATE TABLE blog_comments (
    id VARCHAR(191) NOT NULL PRIMARY KEY,
    post_id VARCHAR(191) NOT NULL,
    parent_id VARCHAR(191) DEFAULT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    website VARCHAR(255) DEFAULT NULL,
    comment TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent TEXT DEFAULT NULL,
    created_at DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
    updated_at DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
    
    KEY idx_post_id (post_id),
    KEY idx_parent_id (parent_id),
    KEY idx_status (status),
    KEY idx_created_at (created_at),
    KEY idx_email (email),
    
    CONSTRAINT blog_comments_post_id_fkey FOREIGN KEY (post_id) REFERENCES blog_posts(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT blog_comments_parent_id_fkey FOREIGN KEY (parent_id) REFERENCES blog_comments(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Video tablosu
CREATE TABLE videos (
  id VARCHAR(191) NOT NULL PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT DEFAULT NULL,
  url VARCHAR(500) NOT NULL,
  thumbnail VARCHAR(255) DEFAULT NULL,
  status VARCHAR(50) NOT NULL DEFAULT 'draft',
  featured TINYINT(1) NOT NULL DEFAULT 0,
  author_id INT(11) NOT NULL,
  category_id VARCHAR(191) DEFAULT NULL,
  tags VARCHAR(500) DEFAULT NULL,
  created_at DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  updated_at DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  
  KEY idx_author_id (author_id),
  KEY idx_category_id (category_id),
  KEY idx_status (status),
  KEY idx_featured (featured),
  
  CONSTRAINT videos_author_id_fkey FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT videos_category_id_fkey FOREIGN KEY (category_id) REFERENCES blog_categories(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- 5. SİSTEM LOGLARI
-- ================================================================

-- Sistem logları tablosu
CREATE TABLE system_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT DEFAULT NULL,
  action VARCHAR(100) NOT NULL,
  table_name VARCHAR(100) DEFAULT NULL,
  record_id INT DEFAULT NULL,
  old_values JSON DEFAULT NULL,
  new_values JSON DEFAULT NULL,
  ip_address VARCHAR(45) DEFAULT NULL,
  user_agent TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  KEY idx_user_id (user_id),
  KEY idx_action (action),
  KEY idx_table_name (table_name),
  KEY idx_created_at (created_at),
  
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- 6. ÖRNEK VERİLER
-- ================================================================

-- Admin kullanıcıları (Şifreler: admin123, editor123, viewer123)
INSERT INTO users (name, email, password, role, status) VALUES
('Admin Kullanıcı', 'admin@cinaralti.org', '$2a$10$jXImkXxvhJZ3wTDDBRuJnuZ4ZlCH1Jg6KQQyRYUEh18EZR2/Hto0.', 'admin', 'active'),
('Mehmet Editör', 'editor@cinaralti.org', '$2a$10$9Ztlc6O8XdA8e/ykrWVNkeTasfPIpE2Kn0yiXlJn/UzNRsC8k4gEW', 'editor', 'active'),
('Ayşe İzleyici', 'viewer@cinaralti.org', '$2a$10$g5cVfpB2MvTXwBuVnL4x8eo/qs9PXKJgJdR1Oa4/FDhEJ6YVLCnLm', 'viewer', 'active');

-- Bağış kategorileri
INSERT INTO donation_categories (name, slug, description) VALUES
('Acil Yardım', 'acil-yardim', 'Acil durumlarda ihtiyaç sahiplerine yardım'),
('Yetim', 'yetim', 'Yetim çocuklara yönelik yardımlar'),
('Genel', 'genel', 'Genel amaçlı bağışlar'),
('Projeler', 'projeler', 'Vakıf projeleri için bağışlar'),
('Eğitim', 'egitim', 'Eğitim amaçlı bağışlar'),
('Kurban', 'kurban', 'Kurban bağışları'),
('Sağlık', 'saglik', 'Sağlık yardımları'),
('Afet', 'afet', 'Afet bölgeleri için yardım');

-- Bağış seçenekleri
INSERT INTO donation_options (name, slug, image, description, target_amount, collected_amount, position, is_active) VALUES
('Genel Bağış', 'genel-bagis', 'donate1.jpg', 'Genel amaçlı bağış yapabilirsiniz', 50000.00, 12500.00, 1, 1),
('Zekat', 'zekat', 'donate2.jpg', 'Zekat bağışınızı buradan yapabilirsiniz', 100000.00, 25000.00, 2, 1),
('Bina Satın Alma', 'bina-satin-alma', 'donate3.jpg', 'Bina satın alma projesi için bağış', 500000.00, 150000.00, 3, 1),
('Kuran Talebelerinin İhtiyaçları', 'kuran-talebelerinin-ihtiyaclari', 'donate4.jpg', 'Kuran talebelerinin eğitim ihtiyaçları için bağış', 30000.00, 8000.00, 4, 1),
('Afrika Bağışı', 'afrika-bagisi', 'donate5.jpg', 'Afrika yardım projesi için bağış', 75000.00, 18000.00, 5, 1),
('Filistin Yardımı', 'filistin-yardimi', 'donate6.jpg', 'Filistin için acil yardım', 100000.00, 45000.00, 6, 1),
('Yetim Projesi', 'yetim-projesi', 'donate7.jpg', 'Yetim çocuklara destek projesi', 60000.00, 22000.00, 7, 1),
('Kurban Bağışı', 'kurban-bagisi', 'donate8.jpg', 'Kurban bağışı yapabilirsiniz', 40000.00, 15000.00, 8, 1),
('Su Kuyusu', 'su-kuyusu', 'donate9.jpg', 'Su kuyusu açtırma projesi', 25000.00, 12000.00, 9, 1),
('Okul Yapımı', 'okul-yapimi', 'donate10.jpg', 'Okul yapım projesi', 200000.00, 50000.00, 10, 1);

-- Blog kategorileri
INSERT INTO blog_categories (id, name, slug, description, type) VALUES
('clmz7x8y00000w8jm8h5j8k3l', 'Genel', 'genel', 'Genel blog yazıları', 'blog'),
('clmz7x8y00001w8jm8h5j8k3m', 'Duyurular', 'duyurular', 'Vakıf duyuruları', 'blog'),
('clmz7x8y00002w8jm8h5j8k3n', 'Etkinlikler', 'etkinlikler', 'Vakıf etkinlikleri', 'blog'),
('clmz7x8y00003w8jm8h5j8k3o', 'Proje Haberleri', 'proje-haberleri', 'Proje güncellemeleri', 'blog'),
('clmz7x8y00004w8jm8h5j8k3p', 'Videolar', 'videolar', 'Video içerikleri', 'video'),
('clmz7x8y00005w8jm8h5j8k3q', 'Haber', 'haber', 'Haberler', 'blog'),
('clmz7x8y00006w8jm8h5j8k3r', 'Röportaj', 'roportaj', 'Röportajlar', 'blog');

-- Site ayarları
INSERT INTO settings (setting_key, setting_value, setting_group) VALUES
('site_name', 'Çınaraltı Vakfı', 'general'),
('site_description', 'Hayırseverlik ve yardımlaşma vakfı', 'general'),
('site_email', 'info@cinaralti.org', 'general'),
('site_phone', '+90 555 123 4567', 'general'),
('site_address', 'İstanbul, Türkiye', 'general'),
('facebook_url', 'https://facebook.com/cinaralti', 'social'),
('twitter_url', 'https://twitter.com/cinaralti', 'social'),
('instagram_url', 'https://instagram.com/cinaralti', 'social'),
('youtube_url', 'https://youtube.com/cinaralti', 'social'),
('donation_goal_monthly', '50000', 'donation'),
('donation_goal_yearly', '600000', 'donation');

-- Kurulum tamamlandı mesajı
SELECT 'Çınaraltı Vakfı veritabanı kurulumu başarıyla tamamlandı!' as message;
