-- Çınaraltı Vakfı - Donation Options Tablosunu Güncelleme
-- Bu script, donation_options tablosunu istenen yapıya uygun olarak günceller

-- Öncelikle name -> title ve is_active -> active alan adlarını değiştirelim
ALTER TABLE donation_options 
CHANGE COLUMN name title VARCHAR(255) NOT NULL,
CHANGE COLUMN is_active active TINYINT(1) DEFAULT 1;

-- Eksik alanları ekleyelim
ALTER TABLE donation_options 
ADD COLUMN category_id INT(11) NULL AFTER id,
ADD COLUMN target_amount DECIMAL(12,2) DEFAULT '0.00' AFTER description,
ADD COLUMN collected_amount DECIMAL(12,2) DEFAULT '0.00' AFTER target_amount,
ADD COLUMN position INT(11) DEFAULT '0' AFTER collected_amount;

-- Artık gerekmeyen alanları kaldıralım (isteğe bağlı)
-- NOT: Bu alanları kaldırmadan önce, bu alanları kullanan kodları güncellediğinizden emin olun
-- ALTER TABLE donation_options 
-- DROP COLUMN image,
-- DROP COLUMN cover_image,
-- DROP COLUMN gallery_images;

-- category_id için index ekleyelim
ALTER TABLE donation_options 
ADD INDEX idx_category_id (category_id);

-- Eğer donation_option_categories tablosu kullanılmayacaksa, category_id için foreign key ekleyebiliriz
-- ALTER TABLE donation_options 
-- ADD CONSTRAINT fk_donation_options_category 
-- FOREIGN KEY (category_id) REFERENCES donation_categories(id) ON DELETE SET NULL; 