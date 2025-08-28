# Kuveyt Türk İletişim Checklist

## 📞 Banka ile görüşürken sorunuz gerekenler:

### 1. Production Credential'ları
- [ ] Production Merchant ID
- [ ] Production API Key
- [ ] Production API Base URL
- [ ] Test merchant ID'den production'a geçiş onayı

### 2. Teknik Gereksinimler
- [ ] Sunucu IP adresi whitelist işlemi
- [ ] SSL sertifika gereksinimleri
- [ ] Webhook URL'leri (eğer varsa)
- [ ] İşlem callback URL'leri

### 3. İş Gereksinimleri
- [ ] Günlük/aylık işlem limitleri
- [ ] Minimum/maksimum işlem tutarları
- [ ] Komisyon oranları
- [ ] Settlement süreçleri (para transferi)

### 4. Güvenlik Ayarları
- [ ] 3D Secure zorunluluğu
- [ ] Fraud detection ayarları
- [ ] Risk analizi parametreleri
- [ ] İşlem bildirimleri

### 5. Test Süreci
- [ ] Test kartları listesi
- [ ] Test senaryoları
- [ ] Go-live onay süreci
- [ ] İlk canlı işlem testleri

## 📋 Hazırlamanız gereken bilgiler:

1. **Şirket Bilgileri:**
   - Vergi numarası
   - Mersis numarası  
   - İletişim bilgileri

2. **Teknik Bilgiler:**
   - Domain adı
   - Sunucu IP adresi
   - SSL sertifika durumu

3. **İş Bilgileri:**
   - Beklenen işlem hacmi
   - Ortalama işlem tutarı
   - İş modeli açıklaması

## ⚠️ Dikkat Edilecekler:

- Production ortamında ilk test işlemlerini küçük tutarlarla yapın
- Tüm hata senaryolarını test edin
- İade/iptal işlemlerini de test edin
- Güvenlik testlerini mutlaka yapın
