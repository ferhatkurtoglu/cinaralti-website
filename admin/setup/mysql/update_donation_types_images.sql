-- Çınaraltı Vakfı - Donation Types Tablosuna Resim Alanları Ekleme
-- Bu script, bağış türleri tablosuna kapak resmi ve galeri resimleri sütunlarını ekler

-- Donation_types tablosuna cover_image sütunu ekleme
ALTER TABLE donation_types 
ADD COLUMN cover_image VARCHAR(255) DEFAULT NULL COMMENT 'Bağış türü kapak görselinin yolu' AFTER image;

-- Donation_types tablosuna gallery_images sütunu ekleme (JSON formatında saklanacak)
ALTER TABLE donation_types 
ADD COLUMN gallery_images JSON DEFAULT NULL COMMENT 'Bağış türü galeri görselleri (JSON dizi formatında)' AFTER cover_image;

-- Not: Bu değişiklik mevcut tabloya yeni alanlar ekleyecektir.
-- Mevcut kayıtlar için bu alanlar NULL değer alacaktır. 