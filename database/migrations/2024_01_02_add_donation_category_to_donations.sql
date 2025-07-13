-- Migration: Add donation_category to donations_made table
-- Date: 2024-01-02
-- Description: Donations_made tablosuna donation_category alanı eklenir

USE cinaralti_db;

-- Donations_made tablosuna donation_category sütunu ekleme
ALTER TABLE `donations_made` 
ADD COLUMN `donation_category` varchar(100) DEFAULT NULL COMMENT 'Bağış kategorisi' AFTER `donation_option`;

-- Donation_category için index ekleme
ALTER TABLE `donations_made` 
ADD INDEX `idx_donation_category` (`donation_category`);

-- Migration tamamlandı
SELECT 'Migration 2024_01_02_add_donation_category_to_donations completed successfully' as message; 