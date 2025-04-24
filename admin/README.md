# Çınaraltı Admin Dashboard

Çınaraltı Vakfı için Next.js ile geliştirilmiş admin panel uygulaması. Bu panel, bağış yönetimi, bağış istatistikleri ve bağış seçeneklerinin yönetimini sağlar.

## Özellikler

- Bağış istatistikleri (günlük, haftalık, aylık, yıllık)
- Kategori bazlı bağış analizi
- Bağışçı tipi analizi
- Bağış seçenekleri yönetimi (ekleme, düzenleme, silme)
- Kullanıcı yönetimi

## Kurulum

1. Gerekli paketlerin yüklenmesi:

```bash
npm install
```

2. Geliştirme ortamında çalıştırma:

```bash
npm run dev
```

3. Veritabanı kurulumu:

```bash
# MySQL veritabanı için şema dosyasını içeri aktarın
mysql -u kullanıcıadı -p cinaralti_db < setup/schema.sql
```

## Veritabanı Yapısı

Uygulama, MySQL veritabanını kullanmaktadır. Veritabanı şeması şu tablolardan oluşur:

- `donation_categories` - Bağış kategorileri
- `donation_types` - Bağış tipleri/seçenekleri
- `donation_type_categories` - Bağış tipi ve kategori ilişkisi
- `donations` - Yapılan bağışlar
- `admin_users` - Yönetici kullanıcılar

## Teknolojiler

- Next.js 14
- React 18
- TypeScript
- TailwindCSS
- MySQL
- Chart.js

## Geliştirme

Bu proje Next.js App Router yapısıyla geliştirilmiştir. Ana dizinde şu klasörler bulunur:

- `app/` - Sayfa bileşenleri
- `components/` - Yeniden kullanılabilir UI bileşenleri
- `lib/` - Yardımcı fonksiyonlar ve API işlemleri
- `setup/` - Kurulum dosyaları

## Canlı Ortama Yükleme

```bash
# Projeyi build edin
npm run build

# Canlı ortamda çalıştırın
npm start
```
