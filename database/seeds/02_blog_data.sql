-- ================================================================
-- Blog Sistemi Seed Data
-- Tarih: 2024-01-04
-- Açıklama: Blog kategorileri ve örnek blog yazıları
-- ================================================================

-- Blog kategorileri
INSERT INTO blog_categories (id, name, slug, description, type) VALUES 
('blog-cat-1', 'Genel', 'genel', 'Genel blog yazıları', 'blog'),
('blog-cat-2', 'Dini Konular', 'dini-konular', 'Dini ve manevi konular', 'blog'),
('blog-cat-3', 'Duyurular', 'duyurular', 'Vakıf duyuruları', 'blog'),
('blog-cat-4', 'Etkinlikler', 'etkinlikler', 'Vakıf etkinlikleri', 'blog'),
('blog-cat-5', 'Projeler', 'projeler', 'Vakıf projeleri', 'blog'),
('blog-cat-6', 'Yardım Kampanyaları', 'yardim-kampanyalari', 'Yardım ve bağış kampanyaları', 'blog'),
('blog-cat-7', 'Eğitim', 'egitim', 'Eğitim faaliyetleri', 'blog'),
('blog-cat-8', 'Sosyal Sorumluluk', 'sosyal-sorumluluk', 'Sosyal sorumluluk projeleri', 'blog')
ON DUPLICATE KEY UPDATE name=VALUES(name), description=VALUES(description);

-- Örnek blog yazıları
INSERT INTO blog_posts (id, title, content, excerpt, slug, status, featured, cover_image, author_id, category_id, tags) VALUES 
(
    'post-1',
    'Çınaraltı Vakfı ile Yardım Ellerinizi Uzatın',
    '<p>Çınaraltı Vakfı olarak, ihtiyaç sahibi ailelere yardım etmek için sürekli olarak çalışmaktayız. Geçtiğimiz ay düzenlediğimiz yardım kampanyasında 500 aileye ulaştık.</p>
    <p>Yardım faaliyetlerimiz kapsamında:</p>
    <ul>
        <li>Gıda yardımı</li>
        <li>Giyim yardımı</li>
        <li>Eğitim desteği</li>
        <li>Sağlık yardımı</li>
    </ul>
    <p>Sizler de bu güzel davaya katılabilir, yardım ellerinizi uzatabilirsiniz.</p>',
    'Çınaraltı Vakfı olarak, ihtiyaç sahibi ailelere yardım etmek için sürekli olarak çalışmaktayız. Geçtiğimiz ay düzenlediğimiz yardım kampanyasında 500 aileye ulaştık.',
    'cinaralti-vakfi-ile-yardim-ellerinizi-uzatin',
    'published',
    1,
    '/assets/image/blog/blog-1.png',
    1,
    'blog-cat-6',
    'yardım,kampanya,bağış,vakıf'
),
(
    'post-2',
    'Ramazan Ayı Yardım Faaliyetleri',
    '<p>Ramazan ayı bereketli bir aydır. Bu mübarek ayda ihtiyaç sahibi kardeşlerimize yardım etmek için özel programlar düzenliyoruz.</p>
    <p>Ramazan programlarımız:</p>
    <ul>
        <li>İftar yemeği dağıtımı</li>
        <li>Kumanya yardımı</li>
        <li>Fidye ve zekat toplama</li>
        <li>Çocuklara hediye dağıtımı</li>
    </ul>
    <p>Bu ayın bereketinden faydalanmak için bizimle birlikte olun.</p>',
    'Ramazan ayı bereketli bir aydır. Bu mübarek ayda ihtiyaç sahibi kardeşlerimize yardım etmek için özel programlar düzenliyoruz.',
    'ramazan-ayi-yardim-faaliyetleri',
    'published',
    1,
    '/assets/image/blog/blog-2.png',
    1,
    'blog-cat-2',
    'ramazan,iftar,zekat,fidye,din'
),
(
    'post-3',
    'Çocuk Eğitimi Projemiz Devam Ediyor',
    '<p>Çocukların eğitimi geleceğimizin teminatıdır. Bu bilinçle yola çıkarak başlattığımız çocuk eğitimi projemiz başarıyla devam ediyor.</p>
    <p>Proje kapsamında:</p>
    <ul>
        <li>Okuma-yazma kursu</li>
        <li>Matematik destekleme</li>
        <li>Dil eğitimi</li>
        <li>Sanat ve el sanatları</li>
    </ul>
    <p>Şimdiye kadar 200 çocuğumuza ulaştık. Hedefiniz 500 çocuğa ulaşmak.</p>',
    'Çocukların eğitimi geleceğimizin teminatıdır. Bu bilinçle yola çıkarak başlattığımız çocuk eğitimi projemiz başarıyla devam ediyor.',
    'cocuk-egitimi-projemiz-devam-ediyor',
    'published',
    0,
    '/assets/image/blog/blog-3.png',
    1,
    'blog-cat-7',
    'eğitim,çocuk,okuma,yazma,matematik'
),
(
    'post-4',
    'Kış Ayları İçin Sıcak Yemek Projesi',
    '<p>Kış aylarının zorluklarını yaşayan ihtiyaç sahibi vatandaşlarımız için sıcak yemek projesi başlattık.</p>
    <p>Proje detayları:</p>
    <ul>
        <li>Günlük 1000 porsiyon sıcak yemek</li>
        <li>Ücretsiz dağıtım</li>
        <li>Hijyenik koşullarda hazırlık</li>
        <li>Besleyici menü</li>
    </ul>
    <p>Soğuk kış günlerinde sıcak bir yemek paylaşmanın mutluluğunu yaşıyoruz.</p>',
    'Kış aylarının zorluklarını yaşayan ihtiyaç sahibi vatandaşlarımız için sıcak yemek projesi başlattık.',
    'kis-aylari-icin-sicak-yemek-projesi',
    'published',
    0,
    '/assets/image/blog/blog-4.png',
    1,
    'blog-cat-5',
    'yemek,kış,proje,yardım'
),
(
    'post-5',
    'Vakfımızın Yıllık Faaliyet Raporu',
    '<p>2024 yılında gerçekleştirdiğimiz faaliyetlerin özetini paylaşıyoruz.</p>
    <p>Yıllık istatistiklerimiz:</p>
    <ul>
        <li>15.000 aileye yardım</li>
        <li>50.000 porsiyon yemek dağıtımı</li>
        <li>200 çocuğa eğitim desteği</li>
        <li>1.000 aileye giyim yardımı</li>
    </ul>
    <p>Destekleriniz sayesinde bu başarılara ulaştık. Teşekkür ederiz.</p>',
    '2024 yılında gerçekleştirdiğimiz faaliyetlerin özetini paylaşıyoruz.',
    'vakfimizin-yillik-faaliyet-raporu',
    'draft',
    0,
    '/assets/image/blog/blog-5.png',
    1,
    'blog-cat-3',
    'rapor,faaliyet,yıllık,istatistik'
)
ON DUPLICATE KEY UPDATE 
    title=VALUES(title),
    content=VALUES(content),
    excerpt=VALUES(excerpt),
    status=VALUES(status),
    featured=VALUES(featured),
    updated_at=NOW(); 