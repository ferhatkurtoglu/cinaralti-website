-- Migration: Add order_number to donations_made table
-- Date: 2024-01-01
-- Description: Donations_made tablosuna order_number alanı eklenir

USE cinaralti_db;

-- Donations_made tablosuna order_number eklentisi
ALTER TABLE `donations_made` 
ADD COLUMN `order_number` varchar(50) DEFAULT NULL COMMENT 'Ödeme sistemi sipariş numarası' AFTER `payment_status`,
ADD INDEX `idx_order_number` (`order_number`);

-- Tablo açıklaması güncelle
ALTER TABLE `donations_made` COMMENT='Yapılan bağışların kayıt tablosu';

-- Migration tamamlandı
SELECT 'Migration 2024_01_01_add_order_number_to_donations completed successfully' as message; 