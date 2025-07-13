# Ödeme Sistemi Kurulum Rehberi

## 🔧 **Gerekli Yapılandırmalar**

### 1. **Ödeme API Bilgileri**

`config/payment_config.php` dosyasında aşağıdaki bilgileri güncelleyin:

```php
define('PAYMENT_MERCHANT_ID', 'your_merchant_id_here');
define('PAYMENT_API_KEY', 'your_api_key_here');
```

### 2. **Güvenlik Ayarları**

- `PAYMENT_FORCE_HTTPS`: Canlı ortamda `true` olmalı
- `PAYMENT_DEBUG`: Canlı ortamda `false` olmalı

### 3. **Veritabanı Kontrolü**

`donations_made` tablosunun mevcut olduğundan emin olun:

```sql
CREATE TABLE IF NOT EXISTS `donations_made` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `donation_option_id` int(11) NOT NULL,
  `donor_name` varchar(100) NOT NULL,
  `donor_email` varchar(100) NOT NULL,
  `donor_phone` varchar(20) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `donation_option` varchar(50) NOT NULL,
  `donor_type` varchar(20) NOT NULL DEFAULT 'individual',
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending',
  `order_number` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

## 🚀 **Test Süreci**

### 1. **Geliştirme Modu**

- `PAYMENT_DEBUG = true` ile test edin
- Gerçek ödeme yapılmaz, test sonucu döner

### 2. **Canlı Mod**

- `PAYMENT_DEBUG = false` yapın
- Gerçek API bilgilerini girin
- HTTPS zorunluluğunu aktif edin

## ⚠️ **Önemli Notlar**

1. **API Bilgileri**: Kuveyt Türk'ten alınan gerçek API bilgilerini kullanın
2. **HTTPS**: Canlı ortamda mutlaka HTTPS kullanın
3. **Hata Logları**: `error_log` ile hataları takip edin
4. **Test Kartları**: Geliştirme sırasında test kartları kullanın

## 🔍 **Hata Ayıklama**

### Yaygın Sorunlar:

1. **API Bağlantı Hatası**: API bilgilerini kontrol edin
2. **CSRF Token Hatası**: Session'ları kontrol edin
3. **Veritabanı Hatası**: Tablo yapısını kontrol edin
4. **HTTPS Hatası**: SSL sertifikasını kontrol edin

### Log Dosyaları:

- PHP error log: `/var/log/php_errors.log`
- Apache error log: `/var/log/apache2/error.log`

## 📞 **Destek**

Sorun yaşadığınızda:

1. Error loglarını kontrol edin
2. API yanıtlarını inceleyin
3. Veritabanı bağlantısını test edin
4. HTTPS sertifikasını kontrol edin
