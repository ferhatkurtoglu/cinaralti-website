# Çınaraltı Vakfı - Veritabanı Dokümantasyonu

Bu dokümantasyon, Çınaraltı Vakfı web sitesi için MySQL veritabanı yapısını ve kurulum sürecini açıklar.

## 📁 Klasör Yapısı

```
database/
├── schema/               # Veritabanı şema dosyaları
│   └── 01_main_schema.sql  # Ana veritabanı şeması
├── seeds/                # Örnek veri dosyaları
│   └── 01_sample_data.sql  # Örnek veriler
├── migrations/           # Veritabanı değişiklikleri
│   └── README.md         # Migration rehberi
├── docs/                 # Dokümantasyon dosyaları
└── README.md            # Bu dosya
```

## 🗄️ Veritabanı Yapısı

### Ana Tablolar

#### 1. Kullanıcı Yönetimi
- `users` - Sistem kullanıcıları
- `settings` - Site ayarları
- `system_logs` - Sistem logları

#### 2. Bağış Sistemi
- `donation_categories` - Bağış kategorileri
- `donation_options` - Bağış seçenekleri
- `donation_option_categories` - Bağış seçeneği-kategori ilişkisi
- `donations_made` - Yapılan bağışlar

#### 3. İçerik Yönetimi
- `blog_categories` - Blog kategorileri
- `blog_posts` - Blog yazıları
- `videos` - Video içerikleri

#### 4. İletişim
- `contact_messages` - İletişim formu mesajları

### Önemli Özellikler

- **Otomatik Trigger'lar**: Bağış toplamları otomatik güncellenir
- **View'lar**: İstatistikler için hazır sorgular
- **İndexler**: Performans optimizasyonu
- **Foreign Key'ler**: Veri bütünlüğü

## 🚀 Kurulum

### Yeni Kurulum

1. **Veritabanı Oluştur**:
   ```bash
   mysql -u root -p < database/schema/01_main_schema.sql
   ```

2. **Örnek Verileri Yükle**:
   ```bash
   mysql -u root -p cinaralti_db < database/seeds/01_sample_data.sql
   ```

### Mevcut Veritabanını Güncelle

Migration dosyalarını sırayla çalıştırın:
```bash
cd database/migrations
mysql -u root -p cinaralti_db < 2024_01_01_add_order_number_to_donations.sql
mysql -u root -p cinaralti_db < 2024_01_02_add_donation_category_to_donations.sql
# ... diğer migration dosyaları
```

## 📊 Hazır Sorgular

### Bağış İstatistikleri
```sql
-- Günlük bağış özeti
SELECT 
    DATE(donation_date) as date,
    COUNT(*) as total_donations,
    SUM(amount) as total_amount
FROM donations_made 
WHERE payment_status = 'completed'
GROUP BY DATE(donation_date)
ORDER BY date DESC;
```

### Aylık Bağış Raporu
```sql
-- Aylık bağış özeti view'ı kullan
SELECT * FROM monthly_donation_summary
LIMIT 12;
```

### Bağış Türü Performansı
```sql
-- Bağış türü istatistikleri view'ı kullan
SELECT * FROM donation_statistics
ORDER BY completion_percentage DESC;
```

## 🔧 Bakım

### Yedekleme
```bash
# Tam yedek
mysqldump -u root -p cinaralti_db > backup_$(date +%Y%m%d).sql

# Sadece yapı
mysqldump -u root -p --no-data cinaralti_db > structure_backup.sql
```

### Performans Optimizasyonu
```sql
-- İndex analizi
SHOW INDEX FROM donations_made;

-- Tablo analizi
ANALYZE TABLE donations_made;
```

## 📈 Performans İyileştirmeleri

### Otomatik Güncellemeler
- Bağış toplamları trigger ile otomatik güncellenir
- Sistem logları otomatik kaydedilir

### İndexler
- Bağış sorgularında kullanılan alanlar indexlenmiş
- Composite index'ler performansı artırır

### View'lar
- Sık kullanılan istatistikler için hazır view'lar
- Karmaşık sorgular basitleştirilmiş

## 🔒 Güvenlik

### Veri Bütünlüğü
- Foreign key constraint'ler
- Enum değerleri ile veri kontrolü
- NOT NULL constraint'ler

### Yetkilendirme
- Kullanıcı rolleri (admin, editor, viewer)
- Sistem logları ile izleme

## 📞 Destek

Veritabanı ile ilgili sorunlarda:
1. Önce bu dokümantasyonu kontrol edin
2. Log dosyalarını inceleyin
3. Yedek dosyalarını kontrol edin

## 🗂️ Dosya Geçmişi

- `2024-01-01`: Ana şema oluşturuldu
- `2024-01-02`: Migration sistemi eklendi
- `2024-01-03`: Dokümantasyon tamamlandı

## 🔄 Güncellemeler

Bu dokümantasyon düzenli olarak güncellenir. Son güncelleme: 2024

---

**Not**: Bu dokümantasyon MySQL 8.0+ için optimize edilmiştir. Önceki sürümlerde bazı özellikler desteklenmeyebilir. 