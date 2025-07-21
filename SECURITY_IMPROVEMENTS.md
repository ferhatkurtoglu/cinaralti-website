# Çınaraltı Bağış Sistemi - Güvenlik İyileştirmeleri

Bu dokümantasyon, bağış sisteminde yapılan kritik güvenlik iyileştirmelerini ve uygulanması gereken adımları açıklamaktadır.

## 🔒 Yapılan Güvenlik İyileştirmeleri

### 1. CSRF Token Güvenliği

- **Önceki durum**: Basit CSRF token kontrolü
- **İyileştirme**:
  - Token timeout (30 dakika)
  - Token rotation (tek kullanımlık)
  - Güçlendirilmiş token generation
  - Timing attack koruması

### 2. Session Güvenliği

- **Yeni özellikler**:
  - Session hijacking koruması
  - IP adresi kontrolü
  - User agent doğrulaması
  - Session timeout (2 saat)
  - Secure session cookie ayarları

### 3. Rate Limiting Sistemi

- **Korunan alanlar**:
  - Ödeme denemeleri (3 deneme / 15 dakika)
  - Sayfa erişimleri
  - Cart işlemleri
- **Güvenlik logları**: Aşırı deneme girişimleri loglanır

### 4. Input Validation ve Sanitization

- **Kapsamlı kontroller**:
  - Bağış tutarı doğrulaması (min/max limitler)
  - Email format kontrolü
  - Telefon numarası doğrulaması
  - Kart numarası Luhn algoritması kontrolü
  - CVV ve son kullanma tarihi kontrolü
  - XSS koruması (ENT_QUOTES, UTF-8)

### 5. Server-Side Cart Validation

- **Önceki durum**: Client-side (LocalStorage) cart sistemi
- **İyileştirme**:
  - Server-side cart management
  - Item validation
  - CSRF korumalı API endpoints
  - Secure cart operations

### 6. Payment Configuration Güvenliği

- **Environment variables** kullanımı
- **API bilgileri** şifreleme
- **Production kontrolü**
- **Güvenli timeout ayarları**

### 7. Database Encryption

- **Sensitive data encryption**:
  - Donor email, phone, name
  - AES-256-CBC encryption
  - Secure key management
- **PII Masking**:
  - Log güvenliği
  - Email masking (u\*\*\*@domain.com)
  - Phone masking (\*\*\*\*1234)
  - Card number masking (1234\*\*\*\*5678)

### 8. Secure Logging

- **Güvenlik olayları**: Detaylı security logging
- **Sensitive data masking**: PII bilgileri loglardan maskelenir
- **Critical alerts**: Önemli güvenlik olayları için ek uyarılar

## 🚀 Kurulum Adımları

### 1. Environment Variables Ayarlama

`.env` dosyası oluşturun:

```bash
# Database Configuration
DB_HOST=localhost
DB_NAME=cinaralti_db
DB_USER=your_db_user
DB_PASS=your_secure_password

# Payment Configuration
PAYMENT_MERCHANT_ID=your_kuveyt_merchant_id
PAYMENT_API_KEY=your_kuveyt_api_key
PAYMENT_DEBUG=false

# Security Configuration
PAYMENT_FORCE_HTTPS=true
PAYMENT_CSRF_PROTECTION=true
PAYMENT_RATE_LIMIT_ENABLED=true
PAYMENT_MAX_ATTEMPTS=3
PAYMENT_RATE_LIMIT_WINDOW=900

# Encryption Key (Generate: php -r "echo bin2hex(random_bytes(32));")
ENCRYPTION_KEY=your_64_char_hex_encryption_key
```

### 2. Veritabanı Güncelleme

Mevcut veritabanındaki sensitive data'yı migrate etmeniz gerekebilir:

```sql
-- Backup oluşturun
CREATE TABLE donations_made_backup AS SELECT * FROM donations_made;

-- Encryption script çalıştırın (ayrı bir migration scripti olarak)
```

### 3. Web Server Konfigürasyonu

#### Apache (.htaccess)

```apache
# HTTPS yönlendirme
RewriteEngine On
RewriteCond %{HTTPS} !=on
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Security headers
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"
Header always set Referrer-Policy "strict-origin-when-cross-origin"
```

#### Nginx

```nginx
# Security headers
add_header X-Content-Type-Options nosniff;
add_header X-Frame-Options DENY;
add_header X-XSS-Protection "1; mode=block";
add_header Referrer-Policy "strict-origin-when-cross-origin";

# HTTPS yönlendirme
if ($scheme != "https") {
    return 301 https://$server_name$request_uri;
}
```

### 4. PHP Konfigürasyonu

`php.ini` ayarları:

```ini
session.cookie_httponly = 1
session.cookie_secure = 1
session.cookie_samesite = Strict
session.use_strict_mode = 1
display_errors = Off
log_errors = On
error_log = /path/to/secure/error.log
```

## 🛡️ Güvenlik Kontrol Listesi

### Production Öncesi Kontroller

- [ ] `.env` dosyası oluşturuldu ve güvenli konumda
- [ ] Environment variables test edildi
- [ ] HTTPS zorlaması aktif
- [ ] Payment API bilgileri doğru yapılandırıldı
- [ ] Encryption key'leri generate edildi
- [ ] Rate limiting test edildi
- [ ] Database encryption çalışıyor
- [ ] Security headers ayarlandı
- [ ] Error logging çalışıyor
- [ ] Debug mode production'da kapalı

### Sürekli İzleme

- [ ] Security loglarını düzenli kontrol edin
- [ ] Rate limiting uyarılarını izleyin
- [ ] Failed payment girişimlerini analiz edin
- [ ] Database encryption integrity'sini kontrol edin

## ⚠️ Önemli Güvenlik Notları

### Kritik Uyarılar

1. **Environment Variables**: `.env` dosyasını asla Git'e commit etmeyin
2. **Encryption Keys**: Production key'lerini güvenli şekilde saklayın
3. **Database Backup**: Encryption öncesi mutlaka backup alın
4. **SSL Certificate**: Geçerli SSL sertifikası kullanın
5. **Regular Updates**: Güvenlik yamalarını düzenli uygulayın

### İzlenmesi Gereken Metrikler

- Rate limiting tetikleme sayısı
- CSRF token hataları
- Session timeout'ları
- Failed payment attempts
- Encryption/decryption hataları

## 🔍 Test Senaryoları

### Güvenlik Testleri

1. **CSRF Attack Test**:

   - Token olmadan form gönderimi
   - Geçersiz token ile deneme
   - Token timeout testi

2. **Rate Limiting Test**:

   - Aşırı ödeme denemesi
   - Rapid form submission
   - Multiple IP testing

3. **Input Validation Test**:

   - SQL injection attempts
   - XSS payload'ları
   - Invalid data formats

4. **Session Security Test**:
   - Session hijacking attempt
   - Concurrent session testing
   - Timeout verification

## 📞 Destek ve Raporlama

Güvenlik açığı tespit ettiğinizde:

1. Derhal sistemi güvenli duruma getirin
2. Logları analiz edin
3. Etkilenen kullanıcıları belirleyin
4. Gerekli güncellemeleri yapın
5. İnsident raporunu hazırlayın

---

**Son Güncelleme**: 2025-01-27
**Güvenlik Versiyonu**: 2.0
**Uyumluluk**: PHP 7.4+, MySQL 5.7+
