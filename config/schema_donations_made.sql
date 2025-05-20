-- Donations_made tablosu oluşturma
CREATE TABLE IF NOT EXISTS `donations_made` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `donation_id` int(11) NOT NULL COMMENT 'İlgili bağış türünün ID si',
  `donor_name` varchar(100) NOT NULL COMMENT 'Bağışçı adı',
  `donor_email` varchar(100) NOT NULL COMMENT 'Bağışçı e-posta',
  `donor_phone` varchar(20) DEFAULT NULL COMMENT 'Bağışçı telefon',
  `city` varchar(100) DEFAULT NULL COMMENT 'Şehir bilgisi',
  `amount` decimal(10,2) NOT NULL COMMENT 'Bağış miktarı',
  `donation_type` varchar(50) NOT NULL COMMENT 'Bağış türü',
  `donor_type` varchar(20) NOT NULL DEFAULT 'individual' COMMENT 'Bireysel veya kurumsal',
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending' COMMENT 'Ödeme durumu',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_donation_id` (`donation_id`),
  KEY `idx_payment_status` (`payment_status`),
  KEY `idx_donor_email` (`donor_email`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; 