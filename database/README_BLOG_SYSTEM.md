# Blog Sistemi Backend

Bu klasör, Çınaraltı Vakfı blog sistemi için PHP tabanlı backend kodlarını içerir.

## Dosya Yapısı

```
database/
├── Database.php              # Database bağlantı sınıfı
├── models/
│   ├── BlogModel.php         # Blog yazıları için model
│   └── CategoryModel.php     # Kategoriler için model
├── api/
│   ├── blog.php             # Blog API endpoint'leri
│   └── categories.php       # Kategori API endpoint'leri
├── migrations/
│   └── 2024_01_04_create_blog_system.sql  # Blog sistemi migration'ı
├── seeds/
│   └── 02_blog_data.sql     # Örnek blog verileri
├── run_migrations.php       # Migration çalıştırma script'i
└── README_BLOG_SYSTEM.md    # Bu dosya
```

## Kurulum

1. **Database Migration'ını Çalıştır:**

   ```bash
   php database/run_migrations.php
   ```

2. **Veritabanı Yapısı:**
   - `blog_categories` tablosu: Blog kategorilerini tutar
   - `blog_posts` tablosu: Blog yazılarını tutar

## API Endpoint'leri

### Blog API (`/database/api/blog.php`)

#### GET İstekleri:

- `GET /database/api/blog.php` - Tüm blog yazılarını listele
- `GET /database/api/blog.php?id={id}` - Belirli bir blog yazısını getir
- `GET /database/api/blog.php?slug={slug}` - Slug ile blog yazısını getir
- `GET /database/api/blog.php?search={term}` - Blog yazılarında arama
- `GET /database/api/blog.php?featured=true` - Öne çıkan yazıları getir
- `GET /database/api/blog.php?category={category_id}` - Kategoriye göre yazıları getir
- `GET /database/api/blog.php?status={status}` - Duruma göre yazıları getir

#### POST İstekleri:

```json
{
  "title": "Blog Başlığı",
  "content": "Blog içeriği",
  "excerpt": "Özet",
  "slug": "blog-basligi",
  "status": "published",
  "featured": false,
  "cover_image": "/path/to/image.jpg",
  "category_id": "category-id",
  "tags": "tag1,tag2,tag3"
}
```

#### PUT İstekleri:

```json
{
  "id": "post-id",
  "title": "Güncellenmiş Başlık",
  "content": "Güncellenmiş içerik",
  "status": "published"
}
```

#### DELETE İstekleri:

```json
{
  "id": "post-id"
}
```

### Kategori API (`/database/api/categories.php`)

#### GET İstekleri:

- `GET /database/api/categories.php` - Tüm kategorileri listele
- `GET /database/api/categories.php?id={id}` - Belirli bir kategoriyi getir
- `GET /database/api/categories.php?slug={slug}` - Slug ile kategoriyi getir
- `GET /database/api/categories.php?type={type}` - Türe göre kategorileri getir

#### POST İstekleri:

```json
{
  "name": "Kategori Adı",
  "slug": "kategori-adi",
  "description": "Kategori açıklaması",
  "type": "blog"
}
```

#### PUT İstekleri:

```json
{
  "id": "category-id",
  "name": "Güncellenmiş Kategori Adı",
  "description": "Güncellenmiş açıklama"
}
```

#### DELETE İstekleri:

```json
{
  "id": "category-id"
}
```

## Model Sınıfları

### BlogModel

- `getAllPosts($limit, $offset, $status)` - Tüm blog yazılarını getirir
- `getPostById($id)` - ID ile blog yazısını getirir
- `getPostBySlug($slug)` - Slug ile blog yazısını getirir
- `createPost($data)` - Yeni blog yazısı oluşturur
- `updatePost($id, $data)` - Blog yazısını günceller
- `deletePost($id)` - Blog yazısını siler
- `searchPosts($term)` - Blog yazılarında arama yapar
- `getFeaturedPosts($limit)` - Öne çıkan yazıları getirir
- `getPostsByCategory($categoryId)` - Kategoriye göre yazıları getirir
- `generateSlug($title)` - Başlıktan slug oluşturur
- `getStats()` - Blog istatistiklerini getirir

### CategoryModel

- `getAllCategories($type)` - Tüm kategorileri getirir
- `getCategoryById($id)` - ID ile kategoriyi getirir
- `getCategoryBySlug($slug)` - Slug ile kategoriyi getirir
- `createCategory($data)` - Yeni kategori oluşturur
- `updateCategory($id, $data)` - Kategoriyi günceller
- `deleteCategory($id)` - Kategoriyi siler
- `generateSlug($name)` - İsimden slug oluşturur

## Content-Admin Entegrasyonu

Content-admin klasöründeki Next.js uygulaması, bu PHP backend'i kullanmak için güncellendi:

- `content-admin/src/app/api/blog/route.ts` - PHP backend'e proxy
- `content-admin/src/app/api/categories/route.ts` - PHP backend'e proxy

## Kullanım

1. Migration'ları çalıştırın
2. XAMPP'i başlatın
3. Content-admin uygulamasını başlatın
4. Blog yönetimi artık PHP backend üzerinden çalışacak

## Örnek Veriler

Migration çalıştırıldığında aşağıdaki örnek veriler eklenir:

### Kategoriler:

- Genel
- Dini Konular
- Duyurular
- Etkinlikler
- Projeler
- Yardım Kampanyaları
- Eğitim
- Sosyal Sorumluluk

### Blog Yazıları:

- 5 adet örnek blog yazısı
- Farklı kategorilerde
- Yayınlanmış ve taslak durumunda
- Öne çıkan yazılar dahil

## Güvenlik

- CORS yapılandırılmış
- SQL injection koruması (PDO prepared statements)
- Input validation
- Error handling

## Geliştirici Notları

- Tüm database işlemleri transaction destekli
- UUID tabanlı ID yapısı
- Türkçe karakter destekli slug oluşturma
- Otomatik timestamp güncelleme
- Foreign key constraints
- Index optimizasyonu
