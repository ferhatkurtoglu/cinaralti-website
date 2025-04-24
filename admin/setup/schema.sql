-- Çınaraltı Admin Panel Veritabanı Şeması

-- Bağış Kategorileri Tablosu
CREATE TABLE IF NOT EXISTS donation_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL,
    icon VARCHAR(50),
    description TEXT,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bağış Tipleri/Seçenekleri Tablosu
CREATE TABLE IF NOT EXISTS donation_types (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL,
    image VARCHAR(255),
    description TEXT,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bağış Tipi ve Kategorisi İlişkisi (Çoklu kategori seçimi için)
CREATE TABLE IF NOT EXISTS donation_type_categories (
    donation_type_id INT,
    category_id INT,
    PRIMARY KEY (donation_type_id, category_id),
    FOREIGN KEY (donation_type_id) REFERENCES donation_types(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES donation_categories(id) ON DELETE CASCADE
);

-- Bağışlar Tablosu (Yapılan gerçek bağışlar)
CREATE TABLE IF NOT EXISTS donations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    donation_type_id INT,
    amount DECIMAL(10,2) NOT NULL,
    donor_name VARCHAR(100),
    donor_email VARCHAR(255),
    donor_phone VARCHAR(20),
    donor_type ENUM('Bireysel', 'Grup', 'Kurumsal') DEFAULT 'Bireysel',
    donation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    payment_status ENUM('Bekliyor', 'Tamamlandı', 'İptal') DEFAULT 'Bekliyor',
    payment_method VARCHAR(50) DEFAULT 'Kart',
    is_regular TINYINT(1) DEFAULT 0,
    regular_period INT,
    regular_day INT,
    message TEXT,
    is_anonymous TINYINT(1) DEFAULT 0,
    ip_address VARCHAR(45),
    transaction_id VARCHAR(100),
    FOREIGN KEY (donation_type_id) REFERENCES donation_types(id)
);

-- Yönetici Kullanıcılar Tablosu
CREATE TABLE IF NOT EXISTS admin_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100),
    email VARCHAR(255) NOT NULL UNIQUE,
    role ENUM('Admin', 'Editor') DEFAULT 'Editor',
    is_active TINYINT(1) DEFAULT 1,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Örnek veriler ekleniyor
-- Kategoriler
INSERT INTO donation_categories (name, slug, icon, description) VALUES
('Acil Yardım', 'acil-yardim', '🆘', 'Acil durum yardımları'),
('Yetim', 'yetim', '👶', 'Yetim projesi yardımları'),
('Genel', 'genel', '❤️', 'Genel bağışlar'),
('Projeler', 'projeler', '🌱', 'Proje bağışları'),
('Eğitim', 'egitim', '📚', 'Eğitim bağışları'),
('Kurban', 'kurban', '🐑', 'Kurban bağışları');

-- Bağış Tipleri
INSERT INTO donation_types (name, slug, image, description) VALUES
('Genel Bağış', 'genel-bagis', 'donate1.jpg', 'Genel amaçlı bağış'),
('Zekat', 'zekat', 'donate1.jpg', 'Zekat bağışları'),
('Bina Satın Alma', 'bina-satin-alma', 'donate1.jpg', 'Bina satın alma projesi için bağış'),
('Kuran Talebelerinin İhtiyaçları', 'kuran-talebelerinin-ihtiyaclari', 'donate1.jpg', 'Kuran talebelerinin eğitim ihtiyaçları için bağış'),
('Afrika Bağışı', 'afrika-bagisi', 'donate1.jpg', 'Afrika yardım projesi için bağış'),
('Filistin Yardımı', 'filistin-yardimi', 'donate1.jpg', 'Filistin için acil yardım'),
('Yetim Projesi', 'yetim-projesi', 'donate1.jpg', 'Yetim çocuklara destek projesi'),
('Kurban Bağışı', 'kurban-bagisi', 'donate1.jpg', 'Kurban bağışı');

-- Bağış Tipi ve Kategori İlişkileri
INSERT INTO donation_type_categories (donation_type_id, category_id) VALUES
(1, 3), -- Genel Bağış -> Genel
(1, 1), -- Genel Bağış -> Acil Yardım
(2, 3), -- Zekat -> Genel
(2, 1), -- Zekat -> Acil Yardım
(3, 4), -- Bina Satın Alma -> Projeler
(3, 3), -- Bina Satın Alma -> Genel
(4, 5), -- Kuran Talebelerinin İhtiyaçları -> Eğitim
(4, 3), -- Kuran Talebelerinin İhtiyaçları -> Genel
(5, 1), -- Afrika Bağışı -> Acil Yardım
(5, 3), -- Afrika Bağışı -> Genel
(6, 1), -- Filistin Yardımı -> Acil Yardım
(6, 3), -- Filistin Yardımı -> Genel
(7, 2), -- Yetim Projesi -> Yetim
(7, 3), -- Yetim Projesi -> Genel
(8, 6), -- Kurban Bağışı -> Kurban
(8, 3); -- Kurban Bağışı -> Genel

-- Admin kullanıcı
INSERT INTO admin_users (username, password, name, email, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin Kullanıcı', 'admin@cinaralti.org', 'Admin');
-- Not: Password hash'i "password" için örnek hash. Gerçek uygulamada daha güvenli bir şekilde oluşturulmalıdır. 