# MySQL Veritabanı Kurulum Talimatları

Bu belge, Çınaraltı Vakfı web sitesi için MySQL veritabanının nasıl kurulacağını açıklar.

## Önkoşullar

- MySQL Server 8.0 veya üstü bir sürüm yüklü olmalı
- Veritabanı oluşturma ve yönetme yetkileri olan bir MySQL kullanıcısı
- phpMyAdmin (isteğe bağlı, ancak önerilir) veya MySQL komut satırı erişimi

## Kurulum Adımları

### 1. Veritabanını Oluşturma

Aşağıdaki komutları MySQL'de çalıştırarak yeni bir veritabanı oluşturun:

```sql
CREATE DATABASE cinaralti_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 2. Tabloları Oluşturma

`schema.sql` dosyasını veritabanına içe aktarın:

- **phpMyAdmin ile**:

  1. phpMyAdmin'e giriş yapın
  2. Sol menüden `cinaralti_db` veritabanını seçin
  3. "İçe aktar" sekmesine tıklayın
  4. `schema.sql` dosyasını seçin ve "Git" düğmesine tıklayın

- **MySQL Komut Satırı ile**:
  ```bash
  mysql -u USERNAME -p cinaralti_db < schema.sql
  ```

### 3. Örnek Verileri Yükleme (İsteğe Bağlı)

Geliştirme ortamında test verileri gerekiyorsa, `seed.sql` dosyasını içe aktarabilirsiniz:

- **phpMyAdmin ile**:

  1. phpMyAdmin'e giriş yapın
  2. Sol menüden `cinaralti_db` veritabanını seçin
  3. "İçe aktar" sekmesine tıklayın
  4. `seed.sql` dosyasını seçin ve "Git" düğmesine tıklayın

- **MySQL Komut Satırı ile**:
  ```bash
  mysql -u USERNAME -p cinaralti_db < seed.sql
  ```

## Bağlantı Yapılandırması

Veritabanına bağlanmak için `.env.local` dosyasındaki bağlantı bilgilerini güncelleyin:

```
DB_HOST=localhost
DB_USER=root
DB_PASS=password
DB_NAME=cinaralti_db
```

## Örnek Kullanıcılar (seed.sql ile kurulduğunda)

Örnek verileri yüklediyseniz, aşağıdaki kullanıcılar otomatik olarak oluşturulur:

1. **Admin Kullanıcısı**:

   - E-posta: admin@cinaralti.org
   - Şifre: admin123

2. **Editör Kullanıcısı**:

   - E-posta: editor@cinaralti.org
   - Şifre: editor123

3. **İzleyici Kullanıcısı**:
   - E-posta: viewer@cinaralti.org
   - Şifre: viewer123

## Sorun Giderme

- **Bağlantı Hatası**: Veritabanı bağlantı parametrelerinin doğru olduğundan emin olun.
- **Karakter Kodlaması Sorunları**: Türkçe karakterlerin düzgün görüntülenmediğini fark ederseniz, veritabanının ve tablolarının UTF-8 karakter kodlamasını kullandığından emin olun.
- **Yetki Sorunları**: MySQL kullanıcısının veritabanına tam erişimi olduğundan emin olun (SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, DROP).
