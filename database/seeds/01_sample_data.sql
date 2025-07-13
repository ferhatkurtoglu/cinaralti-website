-- ================================================================
-- Çınaraltı Vakfı - Örnek Veriler
-- Bu dosya veritabanına örnek veriler ekler
-- ================================================================

-- Veritabanını seç
USE cinaralti_db;

-- ================================================================
-- 1. KULLANICI VERİLERİ
-- ================================================================

-- Admin kullanıcıları (Şifreler sırasıyla: admin123, editor123, viewer123)
INSERT INTO users (name, email, password, role, status) VALUES
('Admin Kullanıcı', 'admin@cinaralti.org', '$2a$10$jXImkXxvhJZ3wTDDBRuJnuZ4ZlCH1Jg6KQQyRYUEh18EZR2/Hto0.', 'admin', 'active'),
('Mehmet Editör', 'editor@cinaralti.org', '$2a$10$9Ztlc6O8XdA8e/ykrWVNkeTasfPIpE2Kn0yiXlJn/UzNRsC8k4gEW', 'editor', 'active'),
('Ayşe İzleyici', 'viewer@cinaralti.org', '$2a$10$g5cVfpB2MvTXwBuVnL4x8eo/qs9PXKJgJdR1Oa4/FDhEJ6YVLCnLm', 'viewer', 'active');

-- ================================================================
-- 2. BAĞIŞ SİSTEMİ VERİLERİ
-- ================================================================

-- Bağış kategorileri
INSERT INTO donation_categories (name, slug, description) VALUES
('Acil Yardım', 'acil-yardim', 'Acil durumlarda ihtiyaç sahiplerine yardım'),
('Yetim', 'yetim', 'Yetim çocuklara yönelik yardımlar'),
('Genel', 'genel', 'Genel amaçlı bağışlar'),
('Projeler', 'projeler', 'Vakıf projeleri için bağışlar'),
('Eğitim', 'egitim', 'Eğitim amaçlı bağışlar'),
('Kurban', 'kurban', 'Kurban bağışları'),
('Sağlık', 'saglik', 'Sağlık yardımları'),
('Afet', 'afet', 'Afet bölgeleri için yardım');

-- Bağış seçenekleri
INSERT INTO donation_options (name, slug, image, description, target_amount, collected_amount, position, is_active) VALUES
('Genel Bağış', 'genel-bagis', 'donate1.jpg', 'Genel amaçlı bağış yapabilirsiniz', 50000.00, 12500.00, 1, 1),
('Zekat', 'zekat', 'donate2.jpg', 'Zekat bağışınızı buradan yapabilirsiniz', 100000.00, 25000.00, 2, 1),
('Bina Satın Alma', 'bina-satin-alma', 'donate3.jpg', 'Bina satın alma projesi için bağış', 500000.00, 150000.00, 3, 1),
('Kuran Talebelerinin İhtiyaçları', 'kuran-talebelerinin-ihtiyaclari', 'donate4.jpg', 'Kuran talebelerinin eğitim ihtiyaçları için bağış', 30000.00, 8000.00, 4, 1),
('Afrika Bağışı', 'afrika-bagisi', 'donate5.jpg', 'Afrika yardım projesi için bağış', 75000.00, 18000.00, 5, 1),
('Filistin Yardımı', 'filistin-yardimi', 'donate6.jpg', 'Filistin için acil yardım', 100000.00, 45000.00, 6, 1),
('Yetim Projesi', 'yetim-projesi', 'donate7.jpg', 'Yetim çocuklara destek projesi', 60000.00, 22000.00, 7, 1),
('Kurban Bağışı', 'kurban-bagisi', 'donate8.jpg', 'Kurban bağışı yapabilirsiniz', 40000.00, 15000.00, 8, 1),
('Su Kuyusu', 'su-kuyusu', 'donate9.jpg', 'Su kuyusu açtırma projesi', 25000.00, 12000.00, 9, 1),
('Okul Yapımı', 'okul-yapimi', 'donate10.jpg', 'Okul yapım projesi', 200000.00, 50000.00, 10, 1);

-- Bağış seçeneği - kategori ilişkileri
INSERT INTO donation_option_categories (donation_option_id, category_id) VALUES
-- Genel Bağış: Genel, Projeler
(1, 3), (1, 4),
-- Zekat: Genel, Acil Yardım
(2, 3), (2, 1),
-- Bina Satın Alma: Projeler, Genel
(3, 4), (3, 3),
-- Kuran Talebelerinin İhtiyaçları: Eğitim, Genel
(4, 5), (4, 3),
-- Afrika Bağışı: Acil Yardım, Genel
(5, 1), (5, 3),
-- Filistin Yardımı: Acil Yardım, Afet
(6, 1), (6, 8),
-- Yetim Projesi: Yetim, Genel
(7, 2), (7, 3),
-- Kurban Bağışı: Kurban, Genel
(8, 6), (8, 3),
-- Su Kuyusu: Projeler, Acil Yardım
(9, 4), (9, 1),
-- Okul Yapımı: Eğitim, Projeler
(10, 5), (10, 4);

-- Örnek bağışlar (son 30 gün içinde)
INSERT INTO donations_made (donation_option_id, donor_name, donor_email, donor_phone, city, amount, donation_option, donation_category, donor_type, payment_method, payment_status, donation_date, note) VALUES
-- Genel Bağış
(1, 'Ahmet Yılmaz', 'ahmet.yilmaz@example.com', '05551234567', 'İstanbul', 250.00, 'Genel Bağış', 'Genel', 'individual', 'Banka', 'completed', DATE_SUB(NOW(), INTERVAL 1 DAY), 'Hayır için'),
(1, 'Mehmet Kara', 'mehmet.kara@example.com', '05551234568', 'Ankara', 500.00, 'Genel Bağış', 'Genel', 'individual', 'Banka', 'completed', DATE_SUB(NOW(), INTERVAL 2 DAY), 'Allah rızası için'),
(1, 'Ayşe Demir', 'ayse.demir@example.com', '05551234569', 'İzmir', 1000.00, 'Genel Bağış', 'Genel', 'individual', 'Banka', 'completed', DATE_SUB(NOW(), INTERVAL 3 DAY), 'Vakıf çalışmaları için'),

-- Zekat
(2, 'Fatma Ak', 'fatma.ak@example.com', '05551234570', 'Bursa', 2500.00, 'Zekat', 'Genel', 'individual', 'Banka', 'completed', DATE_SUB(NOW(), INTERVAL 2 DAY), 'Zekat'),
(2, 'Mustafa Çelik', 'mustafa.celik@example.com', '05551234571', 'Antalya', 1500.00, 'Zekat', 'Genel', 'individual', 'Banka', 'completed', DATE_SUB(NOW(), INTERVAL 4 DAY), 'Yıllık zekat'),

-- Bina Satın Alma
(3, 'Hasan Özkan', 'hasan.ozkan@example.com', '05551234572', 'Konya', 10000.00, 'Bina Satın Alma', 'Projeler', 'individual', 'Banka', 'completed', DATE_SUB(NOW(), INTERVAL 1 DAY), 'Bina projesi için'),
(3, 'Emine Şahin', 'emine.sahin@example.com', '05551234573', 'Adana', 5000.00, 'Bina Satın Alma', 'Projeler', 'individual', 'Banka', 'completed', DATE_SUB(NOW(), INTERVAL 3 DAY), 'Hayırsever bağış'),

-- Kuran Talebelerinin İhtiyaçları
(4, 'Ali Yıldız', 'ali.yildiz@example.com', '05551234574', 'Samsun', 750.00, 'Kuran Talebelerinin İhtiyaçları', 'Eğitim', 'individual', 'Banka', 'completed', DATE_SUB(NOW(), INTERVAL 2 DAY), 'Talebelerin eğitimi için'),
(4, 'Zehra Güneş', 'zehra.gunes@example.com', '05551234575', 'Trabzon', 1200.00, 'Kuran Talebelerinin İhtiyaçları', 'Eğitim', 'individual', 'Banka', 'completed', DATE_SUB(NOW(), INTERVAL 5 DAY), 'Eğitim desteği'),

-- Afrika Bağışı
(5, 'Osman Polat', 'osman.polat@example.com', '05551234576', 'Gaziantep', 800.00, 'Afrika Bağışı', 'Acil Yardım', 'individual', 'Banka', 'completed', DATE_SUB(NOW(), INTERVAL 1 DAY), 'Afrika yardımı'),
(5, 'Hatice Aydın', 'hatice.aydin@example.com', '05551234577', 'Kayseri', 1500.00, 'Afrika Bağışı', 'Acil Yardım', 'individual', 'Banka', 'completed', DATE_SUB(NOW(), INTERVAL 4 DAY), 'Kardeşlerimiz için'),

-- Filistin Yardımı
(6, 'Murat Öztürk', 'murat.ozturk@example.com', '05551234578', 'Eskişehir', 2000.00, 'Filistin Yardımı', 'Acil Yardım', 'individual', 'Banka', 'completed', DATE_SUB(NOW(), INTERVAL 1 DAY), 'Filistin için'),
(6, 'Sevgi Kartal', 'sevgi.kartal@example.com', '05551234579', 'Denizli', 1000.00, 'Filistin Yardımı', 'Acil Yardım', 'individual', 'Banka', 'completed', DATE_SUB(NOW(), INTERVAL 2 DAY), 'Filistinli kardeşlerimiz için'),

-- Yetim Projesi
(7, 'İbrahim Arslan', 'ibrahim.arslan@example.com', '05551234580', 'Malatya', 600.00, 'Yetim Projesi', 'Yetim', 'individual', 'Banka', 'completed', DATE_SUB(NOW(), INTERVAL 3 DAY), 'Yetim çocuklar için'),
(7, 'Aysel Koç', 'aysel.koc@example.com', '05551234581', 'Elazığ', 1200.00, 'Yetim Projesi', 'Yetim', 'individual', 'Banka', 'completed', DATE_SUB(NOW(), INTERVAL 5 DAY), 'Yetim destek'),

-- Kurban Bağışı
(8, 'Yusuf Kaya', 'yusuf.kaya@example.com', '05551234582', 'Şanlıurfa', 2500.00, 'Kurban Bağışı', 'Kurban', 'individual', 'Banka', 'completed', DATE_SUB(NOW(), INTERVAL 1 DAY), 'Kurban bağışı'),
(8, 'Cemile Eren', 'cemile.eren@example.com', '05551234583', 'Mardin', 2000.00, 'Kurban Bağışı', 'Kurban', 'individual', 'Banka', 'completed', DATE_SUB(NOW(), INTERVAL 3 DAY), 'Adak kurban'),

-- Su Kuyusu
(9, 'Recep Tan', 'recep.tan@example.com', '05551234584', 'Van', 5000.00, 'Su Kuyusu', 'Projeler', 'individual', 'Banka', 'completed', DATE_SUB(NOW(), INTERVAL 2 DAY), 'Su kuyusu için'),
(9, 'Hacer Yalçın', 'hacer.yalcin@example.com', '05551234585', 'Bitlis', 3000.00, 'Su Kuyusu', 'Projeler', 'individual', 'Banka', 'completed', DATE_SUB(NOW(), INTERVAL 4 DAY), 'Su projesi'),

-- Okul Yapımı
(10, 'Kemal Özer', 'kemal.ozer@example.com', '05551234586', 'Erzurum', 15000.00, 'Okul Yapımı', 'Eğitim', 'individual', 'Banka', 'completed', DATE_SUB(NOW(), INTERVAL 1 DAY), 'Okul projesi için'),
(10, 'Nurhan Bulut', 'nurhan.bulut@example.com', '05551234587', 'Erzincan', 8000.00, 'Okul Yapımı', 'Eğitim', 'individual', 'Banka', 'completed', DATE_SUB(NOW(), INTERVAL 3 DAY), 'Eğitim yatırımı');

-- ================================================================
-- 3. BLOG VE İÇERİK VERİLERİ
-- ================================================================

-- Blog kategorileri
INSERT INTO blog_categories (id, name, slug, description, type) VALUES
('clmz7x8y00000w8jm8h5j8k3l', 'Genel', 'genel', 'Genel blog yazıları', 'blog'),
('clmz7x8y00001w8jm8h5j8k3m', 'Duyurular', 'duyurular', 'Vakıf duyuruları', 'blog'),
('clmz7x8y00002w8jm8h5j8k3n', 'Etkinlikler', 'etkinlikler', 'Vakıf etkinlikleri', 'blog'),
('clmz7x8y00003w8jm8h5j8k3o', 'Proje Haberleri', 'proje-haberleri', 'Proje güncellemeleri', 'blog'),
('clmz7x8y00004w8jm8h5j8k3p', 'Videolar', 'videolar', 'Video içerikleri', 'video'),
('clmz7x8y00005w8jm8h5j8k3q', 'Haber', 'haber', 'Haberler', 'blog'),
('clmz7x8y00006w8jm8h5j8k3r', 'Röportaj', 'roportaj', 'Röportajlar', 'blog');

-- Örnek blog yazıları
INSERT INTO blog_posts (id, title, content, excerpt, slug, status, featured, cover_image, author_id, category_id, tags) VALUES
('clmz7x8y00010w8jm8h5j8k3s', 'Vakfımızın Yeni Projesi: Afrika Su Kuyusu', 
'<p>Çınaraltı Vakfı olarak Afrika''da temiz su sorununu çözmek için yeni bir proje başlatıyoruz. Bu proje kapsamında...</p>', 
'Afrika''da temiz su sorununu çözmek için yeni proje başlatıldı.', 
'vakfimizin-yeni-projesi-afrika-su-kuyusu', 'published', 1, 'africa-water-well.jpg', 1, 'clmz7x8y00003w8jm8h5j8k3o', 'afrika,su,proje'),

('clmz7x8y00011w8jm8h5j8k3t', 'Ramazan Ayı Etkinlikleri Başladı', 
'<p>Ramazan ayı münasebetiyle düzenlediğimiz etkinlikler başladı. Bu yıl daha kapsamlı bir program hazırladık...</p>', 
'Ramazan ayı etkinlikleri kapsamlı bir program ile başladı.', 
'ramazan-ayi-etkinlikleri-basladi', 'published', 1, 'ramadan-events.jpg', 1, 'clmz7x8y00002w8jm8h5j8k3n', 'ramazan,etkinlik,iftar'),

('clmz7x8y00012w8jm8h5j8k3u', 'Yetim Çocuklar İçin Eğitim Kampanyası', 
'<p>Yetim çocukların eğitim ihtiyaçlarını karşılamak için başlattığımız kampanya büyük ilgi görüyor...</p>', 
'Yetim çocuklar için eğitim kampanyası başlatıldı.', 
'yetim-cocuklar-icin-egitim-kampanyasi', 'published', 0, 'education-campaign.jpg', 2, 'clmz7x8y00001w8jm8h5j8k3m', 'yetim,eğitim,kampanya');

-- Örnek videolar
INSERT INTO videos (id, title, description, url, thumbnail, status, featured, author_id, category_id, tags) VALUES
('clmz7x8y00020w8jm8h5j8k3v', 'Afrika Su Kuyusu Projesi Tanıtım', 
'Afrika''da açtırdığımız su kuyularının nasıl hayat kurtardığını gösteren video', 
'https://www.youtube.com/watch?v=example1', 'water-well-video.jpg', 'published', 1, 1, 'clmz7x8y00004w8jm8h5j8k3p', 'afrika,su,video'),

('clmz7x8y00021w8jm8h5j8k3w', 'Yetim Çocuklar İle Buluşma', 
'Yetim çocuklarla gerçekleştirdiğimiz etkinliğin videosu', 
'https://www.youtube.com/watch?v=example2', 'orphan-meeting.jpg', 'published', 0, 2, 'clmz7x8y00004w8jm8h5j8k3p', 'yetim,etkinlik,video');

-- ================================================================
-- 4. İLETİŞİM MESAJLARI
-- ================================================================

-- Örnek iletişim mesajları
INSERT INTO contact_messages (name, email, phone, subject, message, status) VALUES
('Ahmet Yılmaz', 'ahmet@example.com', '05551234567', 'Bağış Hakkında Soru', 'Merhaba, Afrika projesi hakkında daha detaylı bilgi alabilir miyim?', 'Yeni'),
('Fatma Kaya', 'fatma@example.com', '05551234568', 'Gönüllülük', 'Vakfınızda gönüllü olarak çalışmak istiyorum. Nasıl başvurabilirim?', 'Okundu'),
('Mehmet Demir', 'mehmet@example.com', '05551234569', 'Kurban Bağışı', 'Kurban bağışı için hangi prosedürü takip etmeliyim?', 'Yanıtlandı');

-- ================================================================
-- 5. SİTE AYARLARI
-- ================================================================

-- Temel site ayarları
INSERT INTO settings (setting_key, setting_value, setting_group) VALUES
('site_name', 'Çınaraltı Vakfı', 'general'),
('site_description', 'Hayırseverlik ve yardımlaşma vakfı', 'general'),
('site_email', 'info@cinaralti.org', 'general'),
('site_phone', '+90 555 123 4567', 'general'),
('site_address', 'İstanbul, Türkiye', 'general'),
('facebook_url', 'https://facebook.com/cinaralti', 'social'),
('twitter_url', 'https://twitter.com/cinaralti', 'social'),
('instagram_url', 'https://instagram.com/cinaralti', 'social'),
('youtube_url', 'https://youtube.com/cinaralti', 'social'),
('donation_goal_monthly', '50000', 'donation'),
('donation_goal_yearly', '600000', 'donation'),
('payment_methods', 'Banka Transferi,Kredi Kartı,Havale', 'payment'),
('bank_account_info', 'Türkiye İş Bankası - IBAN: TR12 3456 7890 1234 5678 9012 34', 'payment');

-- ================================================================
-- TAMAMLANDI - Örnek Veriler Yüklendi
-- ================================================================ 