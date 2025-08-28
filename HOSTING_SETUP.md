# HOSTİNG KURULUM TALİMATLARI

## Problem

Domain'i açtığınızda 404 hatası alıyorsunuz çünkü web server ana klasöre bakarken, site dosyaları `public/` klasöründe.

## Çözüm: Domain'i Public Klasörüne Yönlendirme

### 1. cPanel Kullanıyorsanız:

1. cPanel'e giriş yapın
2. **"Subdomains"** veya **"Addon Domains"** bölümüne gidin
3. `demo.cinaralti.org` domain'ini bulun
4. **Document Root** kısmını değiştirin:
   - Eski: `/public_html/` veya `/httpdocs/`
   - Yeni: `/public_html/public/` veya `/httpdocs/public/`

### 2. DirectAdmin Kullanıyorsanız:

1. DirectAdmin panel'e giriş yapın
2. **"Domain Setup"** > **"Pointers"** bölümüne gidin
3. Domain'inizi seçin ve **"Domain Path"** kısmını değiştirin
4. Path'i `public` klasörüne yönlendirin

### 3. Plesk Kullanıyorsanız:

1. Plesk panel'e giriş yapın
2. **"Hosting & DNS"** > **"Hosting Settings"** bölümüne gidin
3. **"Document root"** kısmını `/httpdocs/public` olarak değiştirin

### 4. Manuel Konfigürasyon (Apache):

Eğer sunucu erişiminiz varsa, Apache virtual host konfigürasyonunu güncelleyin:

```apache
<VirtualHost *:80>
    ServerName demo.cinaralti.org
    DocumentRoot /path/to/your/website/public

    <Directory /path/to/your/website/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 5. Nginx Konfigürasyonu:

```nginx
server {
    listen 80;
    server_name demo.cinaralti.org;
    root /path/to/your/website/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## Test Etme

Domain konfigürasyonu değiştirildikten sonra:

1. 5-10 dakika bekleyin (DNS cache temizlenmesi için)
2. `demo.cinaralti.org` adresini açın
3. Artık 404 hatası almamalısınız

## Alternatif Geçici Çözüm

Eğer hosting panel'inde değişiklik yapamazsanız, hosting sağlayıcınızla iletişime geçin ve şu talebi yapın:

> "Merhaba, demo.cinaralti.org domain'imin document root'unu /public_html/ yerine /public_html/public/ klasörüne yönlendirmenizi istiyorum."

## Önemli Notlar

- Bu değişiklik hosting seviyesinde yapılmalıdır
- .htaccess dosyaları yardımcı olabilir ama asıl çözüm document root değişikliğidir
- Değişiklik sonrası DNS cache temizlenmesi gerekebilir

