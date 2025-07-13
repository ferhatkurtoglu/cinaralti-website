# Migration Dosyaları

Bu klasör, veritabanı değişikliklerini kronolojik sırayla içerir. Her migration dosyası belirli bir değişikliği veya güncellemeyi temsil eder.

## Migration Dosyaları Listesi

### 1. 2024_01_01_add_order_number_to_donations.sql

Donations_made tablosuna order_number alanı ekler.

### 2. 2024_01_02_add_donation_category_to_donations.sql

Donations_made tablosuna donation_category alanı ekler.

### 3. 2024_01_03_update_donation_options_images.sql

Donation_options tablosuna cover_image ve gallery_images alanları ekler.

### 4. 2024_01_04_add_blog_tables.sql

Blog sistemi için gerekli tabloları ekler.

### 5. 2024_01_05_add_cover_image_to_blog_posts.sql

Blog posts tablosuna cover_image alanı ekler.

## Kullanım

Migration dosyalarını sırayla çalıştırın:

```bash
# Her migration dosyasını sırayla çalıştır
mysql -u username -p database_name < 2024_01_01_add_order_number_to_donations.sql
mysql -u username -p database_name < 2024_01_02_add_donation_category_to_donations.sql
mysql -u username -p database_name < 2024_01_03_update_donation_options_images.sql
# ... ve devamı
```

## Önemli Notlar

- Migration dosyaları sırayla çalıştırılmalıdır
- Çalıştırılmadan önce veritabanı yedeği alınmalıdır
- Production ortamında dikkatli olunmalıdır
