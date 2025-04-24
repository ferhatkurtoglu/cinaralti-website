# Çınaraltı Admin Panel Uygulaması - Özet

Bu projede, Çınaraltı Vakfı için bir bağış yönetim sistemi olarak Next.js 14 ile bir admin dashboard uygulaması geliştirilmiştir.

## Tamamlanan Bölümler

1. **Veritabanı Şeması**

   - Bağış kategorileri, tipleri ve bağışlar için ilişkisel veritabanı şeması
   - Örnek veriler
   - MySQL entegrasyonu

2. **Admin Arayüzü**

   - Giriş sayfası
   - Responsive TailwindCSS tasarım
   - Sidebar ve navigasyon
   - Dashboard ana sayfası

3. **Bağış İstatistikleri**

   - Günlük, haftalık, aylık ve yıllık bağış trendleri
   - Kategori bazlı bağış analizi
   - Bağışçı türlerine göre analiz
   - Chart.js ile veri görselleştirme

4. **Bağış Seçenekleri Yönetimi**
   - Bağış tiplerini listeleme
   - Yeni bağış seçeneği ekleme
   - Mevcut bağış seçeneklerini düzenleme
   - Bağış seçeneklerini silme
   - Bağış seçeneklerine kategori atama

## Eksik/Tamamlanmamış Bölümler

1. **Bağış Listesi**

   - Yapılan tüm bağışları görüntüleme
   - Filtreleme ve arama
   - Bağış detaylarını görüntüleme
   - Bağış durumunu güncelleme

2. **Kullanıcı Yönetimi**

   - Admin kullanıcı ekleme/düzenleme
   - Rol ve yetki sistemi

3. **Gerçek API Entegrasyonu**

   - Şu anda UI ve mock verilerle çalışmaktadır
   - API endpoint'leri geliştirilmeli
   - Gerçek veritabanı CRUD işlemleri yapılmalı

4. **Güvenlik İyileştirmeleri**
   - Kapsamlı kimlik doğrulama ve yetkilendirme
   - API rate limiting
   - Güvenlik denetimleri ve penetrasyon testleri

## Önerilen Sonraki Adımlar

1. Eksik bağış listesi ve kullanıcı yönetim sayfalarının tamamlanması
2. API katmanının geliştirilmesi ve gerçek veritabanı entegrasyonunun yapılması
3. Güvenlik testleri ve optimizasyonlar
4. Varolan siteye entegrasyon için gerekli API endpoint'lerinin dokümantasyonu

## Uygulamayı Çalıştırma

Uygulama Next.js ile geliştirilmiştir ve aşağıdaki komutlarla çalıştırılabilir:

```bash
# Geliştirme ortamında çalıştırma
npm run dev

# Uygulamayı build etme
npm run build

# Uygulamayı canlı ortamda çalıştırma
npm start
```

Not: Uygulama başlatılmadan önce MySQL veritabanının kurulu olması ve `setup/schema.sql` dosyasının çalıştırılmış olması gerekmektedir.
