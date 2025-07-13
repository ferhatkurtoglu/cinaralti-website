-- ================================================================
-- Çınaraltı Vakfı - Tam Veritabanı Şeması
-- MySQL 8.0+ için optimize edilmiş
-- Oluşturulma Tarihi: 2024
-- ================================================================

-- Veritabanı oluştur
CREATE DATABASE IF NOT EXISTS cinaralti_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cinaralti_db;

-- ================================================================
-- 1. KULLANICI VE YÖNETİM TABLOLARI
-- ================================================================

-- Kullanıcılar tablosu
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin', 'editor', 'viewer') NOT NULL DEFAULT 'viewer',
  avatar VARCHAR(255) DEFAULT NULL,
  last_login DATETIME DEFAULT NULL,
  status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  KEY idx_email (email),
  KEY idx_role (role),
  KEY idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Site ayarları tablosu
CREATE TABLE IF NOT EXISTS settings (
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
CREATE TABLE IF NOT EXISTS donation_categories (
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
CREATE TABLE IF NOT EXISTS donation_options (
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
CREATE TABLE IF NOT EXISTS donation_option_categories (
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
CREATE TABLE IF NOT EXISTS donations_made (
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
CREATE TABLE IF NOT EXISTS contact_messages (
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
  
  CONSTRAINT blog_posts_author_id_fkey FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT blog_posts_category_id_fkey FOREIGN KEY (category_id) REFERENCES blog_categories(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Video tablosu
CREATE TABLE IF NOT EXISTS videos (
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
-- 5. OLAY VE LOGLAMa TABLOLARI (İsteğe bağlı)
-- ================================================================

-- Sistem logları tablosu
CREATE TABLE IF NOT EXISTS system_logs (
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
-- 6. TRIGGER'LAR VE STORED PROCEDURE'LAR
-- ================================================================

-- Bağış toplamını otomatik güncelleme trigger'ı
DELIMITER $$
CREATE TRIGGER update_donation_collected_amount 
AFTER INSERT ON donations_made
FOR EACH ROW
BEGIN
    UPDATE donation_options 
    SET collected_amount = (
        SELECT COALESCE(SUM(amount), 0) 
        FROM donations_made 
        WHERE donation_option_id = NEW.donation_option_id 
        AND payment_status = 'completed'
    )
    WHERE id = NEW.donation_option_id;
END$$
DELIMITER ;

-- Bağış güncellemesi trigger'ı
DELIMITER $$
CREATE TRIGGER update_donation_collected_amount_on_update
AFTER UPDATE ON donations_made
FOR EACH ROW
BEGIN
    IF OLD.payment_status != NEW.payment_status OR OLD.amount != NEW.amount THEN
        UPDATE donation_options 
        SET collected_amount = (
            SELECT COALESCE(SUM(amount), 0) 
            FROM donations_made 
            WHERE donation_option_id = NEW.donation_option_id 
            AND payment_status = 'completed'
        )
        WHERE id = NEW.donation_option_id;
    END IF;
END$$
DELIMITER ;

-- ================================================================
-- 7. VİEW'LAR
-- ================================================================

-- Bağış istatistikleri view
CREATE OR REPLACE VIEW donation_statistics AS
SELECT 
    do.id,
    do.name,
    do.target_amount,
    do.collected_amount,
    ROUND((do.collected_amount / NULLIF(do.target_amount, 0)) * 100, 2) as completion_percentage,
    COUNT(dm.id) as total_donations,
    COUNT(DISTINCT dm.donor_email) as unique_donors,
    AVG(dm.amount) as average_donation,
    MIN(dm.amount) as min_donation,
    MAX(dm.amount) as max_donation
FROM donation_options do
LEFT JOIN donations_made dm ON do.id = dm.donation_option_id AND dm.payment_status = 'completed'
GROUP BY do.id, do.name, do.target_amount, do.collected_amount;

-- Aylık bağış özeti view
CREATE OR REPLACE VIEW monthly_donation_summary AS
SELECT 
    YEAR(dm.created_at) as year,
    MONTH(dm.created_at) as month,
    COUNT(*) as total_donations,
    SUM(dm.amount) as total_amount,
    COUNT(DISTINCT dm.donor_email) as unique_donors,
    AVG(dm.amount) as average_donation
FROM donations_made dm
WHERE dm.payment_status = 'completed'
GROUP BY YEAR(dm.created_at), MONTH(dm.created_at)
ORDER BY year DESC, month DESC;

-- ================================================================
-- 8. İNDEXLER VE PERFORMANS OPTİMİZASYONU
-- ================================================================

-- Composite indexler
CREATE INDEX idx_donations_date_status ON donations_made(donation_date, payment_status);
CREATE INDEX idx_donations_amount_date ON donations_made(amount, created_at);
CREATE INDEX idx_blog_posts_status_featured ON blog_posts(status, featured);
CREATE INDEX idx_videos_status_featured ON videos(status, featured);

-- Full-text search indexler
ALTER TABLE blog_posts ADD FULLTEXT(title, content);
ALTER TABLE videos ADD FULLTEXT(title, description);

-- ================================================================
-- TAMAMLANDI
-- ================================================================ 