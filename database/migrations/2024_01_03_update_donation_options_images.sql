-- Migration: Update donation_options table with image fields
-- Date: 2024-01-03
-- Description: Donation_options tablosuna cover_image ve gallery_images alanları eklenir

USE cinaralti_db;

-- Donation_options tablosuna cover_image sütunu ekleme
ALTER TABLE donation_options 
ADD COLUMN cover_image VARCHAR(255) DEFAULT NULL COMMENT 'Bağış seçeneği kapak görselinin yolu' AFTER image;

-- Donation_options tablosuna gallery_images sütunu ekleme (JSON formatında saklanacak)
ALTER TABLE donation_options 
ADD COLUMN gallery_images JSON DEFAULT NULL COMMENT 'Bağış seçeneği galeri görselleri (JSON dizi formatında)' AFTER cover_image;

-- Migration tamamlandı
SELECT 'Migration 2024_01_03_update_donation_options_images completed successfully' as message; 