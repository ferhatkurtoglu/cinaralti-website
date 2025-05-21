-- Donations_made tablosuna order_number eklentisi
ALTER TABLE `donations_made` 
ADD COLUMN `order_number` varchar(50) DEFAULT NULL COMMENT 'Ödeme sistemi sipariş numarası' AFTER `payment_status`,
ADD INDEX `idx_order_number` (`order_number`);

-- Açıklama ekle
COMMENT ON TABLE `donations_made` IS 'Yapılan bağışların kayıt tablosu'; 