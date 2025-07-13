#!/bin/bash

# Çınaraltı Vakfı - Veritabanı Kurulum Scripti
# Bu script veritabanını otomatik olarak kurar

echo "🚀 Çınaraltı Vakfı Veritabanı Kurulum Scripti"
echo "=============================================="

# Kullanıcı bilgileri
read -p "MySQL kullanıcı adı: " DB_USER
read -s -p "MySQL şifre: " DB_PASS
echo ""
read -p "Veritabanı adı (varsayılan: cinaralti_db): " DB_NAME
DB_NAME=${DB_NAME:-cinaralti_db}

echo ""
echo "📋 Kurulum Özeti:"
echo "- Kullanıcı: $DB_USER"
echo "- Veritabanı: $DB_NAME"
echo ""

# Onay
read -p "Kuruluma devam etmek istiyor musunuz? (y/n): " CONFIRM
if [ "$CONFIRM" != "y" ]; then
    echo "❌ Kurulum iptal edildi."
    exit 1
fi

echo ""
echo "🗄️ Veritabanı şeması oluşturuluyor..."

# Ana şema kurulumu
if mysql -u "$DB_USER" -p"$DB_PASS" < schema/01_main_schema.sql; then
    echo "✅ Ana şema başarıyla oluşturuldu."
else
    echo "❌ Ana şema kurulumunda hata oluştu."
    exit 1
fi

# Örnek veri yükleme
echo ""
read -p "Örnek verileri yüklemek istiyor musunuz? (y/n): " LOAD_SAMPLE
if [ "$LOAD_SAMPLE" == "y" ]; then
    echo "📊 Örnek veriler yükleniyor..."
    if mysql -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" < seeds/01_sample_data.sql; then
        echo "✅ Örnek veriler başarıyla yüklendi."
    else
        echo "❌ Örnek veri yüklemesinde hata oluştu."
    fi
fi

echo ""
echo "🎉 Kurulum tamamlandı!"
echo ""
echo "📝 Önemli bilgiler:"
echo "- Admin kullanıcı: admin@cinaralti.org"
echo "- Admin şifre: admin123"
echo "- Editör kullanıcı: editor@cinaralti.org"
echo "- Editör şifre: editor123"
echo ""
echo "📚 Dokümantasyon: database/README.md"
echo "🔧 Bakım: database/docs/ klasöründe daha fazla bilgi"
echo ""
echo "⚠️  Güvenlik: Canlı ortamda şifreleri mutlaka değiştirin!" 