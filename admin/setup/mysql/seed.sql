-- Çınaraltı Vakfı Örnek Veriler

-- Admin kullanıcısı (Şifre: admin123)
INSERT INTO users (name, email, password, role)
VALUES ('Admin Kullanıcı', 'admin@cinaralti.org', '$2a$10$jXImkXxvhJZ3wTDDBRuJnuZ4ZlCH1Jg6KQQyRYUEh18EZR2/Hto0.', 'admin');

-- Editör kullanıcısı (Şifre: editor123)
INSERT INTO users (name, email, password, role)
VALUES ('Mehmet Editör', 'editor@cinaralti.org', '$2a$10$9Ztlc6O8XdA8e/ykrWVNkeTasfPIpE2Kn0yiXlJn/UzNRsC8k4gEW', 'editor');

-- İzleyici kullanıcısı (Şifre: viewer123)
INSERT INTO users (name, email, password, role)
VALUES ('Ayşe İzleyici', 'viewer@cinaralti.org', '$2a$10$g5cVfpB2MvTXwBuVnL4x8eo/qs9PXKJgJdR1Oa4/FDhEJ6YVLCnLm', 'viewer');

-- Bağış kategorileri
INSERT INTO donation_categories (name, slug, description)
VALUES 
('Acil Yardım', 'acil-yardim', 'Acil durumlarda ihtiyaç sahiplerine yardım'),
('Yetim', 'yetim', 'Yetim çocuklara yönelik yardımlar'),
('Genel', 'genel', 'Genel amaçlı bağışlar'),
('Projeler', 'projeler', 'Vakıf projeleri için bağışlar'),
('Eğitim', 'egitim', 'Eğitim amaçlı bağışlar'),
('Kurban', 'kurban', 'Kurban bağışları');

-- Bağış türleri
INSERT INTO donation_types (name, slug, image, description, is_active)
VALUES 
('Genel Bağış', 'genel-bagis', 'donate1.jpg', 'Genel amaçlı bağış', 1),
('Zekat', 'zekat', 'donate1.jpg', 'Zekat bağışları', 1),
('Bina Satın Alma', 'bina-satin-alma', 'donate1.jpg', 'Bina satın alma projesi için bağış', 1),
('Kuran Talebelerinin İhtiyaçları', 'kuran-talebelerinin-ihtiyaclari', 'donate1.jpg', 'Kuran talebelerinin eğitim ihtiyaçları için bağış', 1),
('Afrika Bağışı', 'afrika-bagisi', 'donate1.jpg', 'Afrika yardım projesi için bağış', 1),
('Filistin Yardımı', 'filistin-yardimi', 'donate1.jpg', 'Filistin için acil yardım', 1),
('Yetim Projesi', 'yetim-projesi', 'donate1.jpg', 'Yetim çocuklara destek projesi', 1),
('Kurban Bağışı', 'kurban-bagisi', 'donate1.jpg', 'Kurban bağışı', 1);

-- Bağış türü - kategori ilişkileri
-- Genel Bağış: Genel, Acil Yardım
INSERT INTO donation_type_categories (donation_type_id, category_id) VALUES (1, 3), (1, 1);

-- Zekat: Genel, Acil Yardım
INSERT INTO donation_type_categories (donation_type_id, category_id) VALUES (2, 3), (2, 1);

-- Bina Satın Alma: Projeler, Genel
INSERT INTO donation_type_categories (donation_type_id, category_id) VALUES (3, 4), (3, 3);

-- Kuran Talebelerinin İhtiyaçları: Eğitim, Genel
INSERT INTO donation_type_categories (donation_type_id, category_id) VALUES (4, 5), (4, 3);

-- Afrika Bağışı: Acil Yardım, Genel
INSERT INTO donation_type_categories (donation_type_id, category_id) VALUES (5, 1), (5, 3);

-- Filistin Yardımı: Acil Yardım, Genel
INSERT INTO donation_type_categories (donation_type_id, category_id) VALUES (6, 1), (6, 3);

-- Yetim Projesi: Yetim, Genel
INSERT INTO donation_type_categories (donation_type_id, category_id) VALUES (7, 2), (7, 3);

-- Kurban Bağışı: Kurban, Genel
INSERT INTO donation_type_categories (donation_type_id, category_id) VALUES (8, 6), (8, 3);

-- Örnek bağışlar
INSERT INTO donations (donation_type_id, amount, donor_name, donor_email, donor_phone, city, payment_method, payment_status, donation_date, note)
VALUES
(1, 250.00, 'Ahmet Yılmaz', 'ahmet@example.com', '05551234567', 'İstanbul', 'Banka', 'Tamamlandı', DATE_SUB(NOW(), INTERVAL 2 DAY), 'Genel bağış'),
(2, 750.00, 'Mehmet Demir', 'mehmet@example.com', '05551234568', 'Ankara', 'Banka', 'Tamamlandı', DATE_SUB(NOW(), INTERVAL 3 DAY), 'Zekat'),
(3, 5000.00, 'Ayşe Kara', 'ayse@example.com', '05551234569', 'İzmir', 'Banka', 'Tamamlandı', DATE_SUB(NOW(), INTERVAL 5 DAY), 'Bina projesi için'),
(4, 300.00, 'Fatma Ak', 'fatma@example.com', '05551234570', 'Bursa', 'Banka', 'Tamamlandı', DATE_SUB(NOW(), INTERVAL 7 DAY), 'Kuran talebeleri için'),
(5, 200.00, 'Ali Veli', 'ali@example.com', '05551234571', 'Antalya', 'Banka', 'Tamamlandı', DATE_SUB(NOW(), INTERVAL 10 DAY), 'Afrika için'),
(6, 1000.00, 'Hasan Uzun', 'hasan@example.com', '05551234572', 'İstanbul', 'Banka', 'Tamamlandı', DATE_SUB(NOW(), INTERVAL 12 DAY), 'Filistin için'),
(7, 500.00, 'Zeynep Can', 'zeynep@example.com', '05551234573', 'Konya', 'Banka', 'Tamamlandı', DATE_SUB(NOW(), INTERVAL 15 DAY), 'Yetimlere yardım'),
(8, 2000.00, 'İbrahim Yıldız', 'ibrahim@example.com', '05551234574', 'Ankara', 'Banka', 'Tamamlandı', DATE_SUB(NOW(), INTERVAL 20 DAY), 'Kurban bağışı');

-- Daha fazla örnek bağış verisi
INSERT INTO donations (donation_type_id, amount, donor_name, donor_email, donor_phone, city, payment_method, payment_status, donation_date, note)
VALUES
(1, 350.00, 'Mustafa Çelik', 'mustafa@example.com', '05551234575', 'İstanbul', 'Banka', 'Tamamlandı', DATE_SUB(NOW(), INTERVAL 4 DAY), 'Genel bağış'),
(2, 500.00, 'Elif Yıldız', 'elif@example.com', '05551234576', 'İzmir', 'Banka', 'Tamamlandı', DATE_SUB(NOW(), INTERVAL 6 DAY), 'Zekat'),
(6, 1500.00, 'Osman Kaya', 'osman@example.com', '05551234577', 'Gaziantep', 'Banka', 'Tamamlandı', DATE_SUB(NOW(), INTERVAL 8 DAY), 'Filistin için'),
(7, 400.00, 'Leyla Demir', 'leyla@example.com', '05551234578', 'Adana', 'Banka', 'Tamamlandı', DATE_SUB(NOW(), INTERVAL 9 DAY), 'Yetim için'),
(5, 150.00, 'Mehmet Yılmaz', 'mehmet2@example.com', '05551234579', 'İstanbul', 'Banka', 'Tamamlandı', DATE_SUB(NOW(), INTERVAL 11 DAY), 'Afrika için'),
(1, 280.00, 'Ayşe Nur', 'aysenur@example.com', '05551234580', 'Trabzon', 'Banka', 'Tamamlandı', DATE_SUB(NOW(), INTERVAL 13 DAY), 'Genel bağış'),
(4, 450.00, 'Hüseyin Kara', 'huseyin@example.com', '05551234581', 'Kayseri', 'Banka', 'Tamamlandı', DATE_SUB(NOW(), INTERVAL 14 DAY), 'Kuran talebeleri için'),
(2, 850.00, 'Fatih Çetin', 'fatih@example.com', '05551234582', 'Ankara', 'Banka', 'Tamamlandı', DATE_SUB(NOW(), INTERVAL 16 DAY), 'Zekat');

-- Site ayarları
INSERT INTO settings (setting_key, setting_value, setting_group)
VALUES 
('site_title', 'Çınaraltı Vakfı', 'general'),
('site_description', 'Çınaraltı Vakfı Resmi Web Sitesi', 'general'),
('contact_email', 'info@cinaralti.org', 'contact'),
('contact_phone', '0212 123 45 67', 'contact'),
('contact_address', 'İstanbul, Türkiye', 'contact'),
('donation_account_details', 'Banka: Ziraat Bankası\nŞube: İstanbul\nHesap No: 12345678\nIBAN: TR12 3456 7890 1234 5678 9012 34', 'donation'),
('social_facebook', 'https://facebook.com/cinaralti', 'social'),
('social_twitter', 'https://twitter.com/cinaralti', 'social'),
('social_instagram', 'https://instagram.com/cinaralti', 'social'),
('social_youtube', 'https://youtube.com/cinaralti', 'social'); 