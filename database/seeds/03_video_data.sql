-- ================================================================
-- Video Sistemi Seed Data
-- Tarih: 2025-01-14
-- Açıklama: Video kategorileri ve örnek video verileri
-- ================================================================

-- Video kategorileri ekle
INSERT INTO blog_categories (id, name, slug, description, type) VALUES 
('video-cat-1', 'Eğitim Videoları', 'egitim-videolari', 'İslami eğitim videoları', 'video'),
('video-cat-2', 'Etkinlik Kayıtları', 'etkinlik-kayitlari', 'Vakıf etkinliklerinin video kayıtları', 'video'),
('video-cat-3', 'Tanıtım Videoları', 'tanitim-videolari', 'Vakıf tanıtım ve proje videoları', 'video'),
('video-cat-4', 'Röportajlar', 'roportajlar', 'Röportaj ve söyleşi videoları', 'video'),
('video-cat-5', 'Dini Sohbetler', 'dini-sohbetler', 'Dini sohbet ve vaaz videoları', 'video'),
('video-cat-6', 'Belgeseller', 'belgeseller', 'Belgesel ve tanıtım filmleri', 'video')
ON DUPLICATE KEY UPDATE name=VALUES(name), description=VALUES(description), type=VALUES(type);

-- Örnek video verileri
INSERT INTO videos (id, title, description, url, thumbnail, status, featured, author_id, category_id, tags) VALUES 
(
    'video-1',
    'İslam\'da Kardeşlik ve Dayanışma',
    'İslam dininde kardeşlik kavramının önemi ve toplumsal dayanışmanın gerekliliği hakkında eğitici bir video.',
    'https://www.youtube.com/watch?v=b1ngjS28NWs',
    'https://img.youtube.com/vi/b1ngjS28NWs/mqdefault.jpg',
    'published',
    1,
    1,
    'video-cat-1',
    'İslam,kardeşlik,dayanışma,eğitim'
),
(
    'video-2',
    'Çınaraltı Vakfı 2024 Yardım Kampanyası',
    'Vakfımızın 2024 yılında gerçekleştirdiği yardım kampanyalarının özetini sunan tanıtım videosu.',
    'https://www.youtube.com/watch?v=XXYUTY9h1aw',
    'https://img.youtube.com/vi/XXYUTY9h1aw/mqdefault.jpg',
    'published',
    1,
    1,
    'video-cat-3',
    'kampanya,yardım,vakıf,tanıtım'
),
(
    'video-3',
    'Ramazan İftar Programımız',
    'Ramazan ayında düzenlediğimiz iftar programının canlı yayın kaydı.',
    'https://www.youtube.com/watch?v=E1IZ_OGcT-0',
    'https://img.youtube.com/vi/E1IZ_OGcT-0/mqdefault.jpg',
    'published',
    1,
    1,
    'video-cat-2',
    'ramazan,iftar,etkinlik,program'
),
(
    'video-4',
    'Gençlerle İslam Üzerine Sohbet',
    'Gençlerle İslam dini, ahlak ve değerler üzerine yapılan samimi sohbet videosu.',
    'https://www.youtube.com/watch?v=LGwFy9HAl0g',
    'https://img.youtube.com/vi/LGwFy9HAl0g/mqdefault.jpg',
    'published',
    0,
    1,
    'video-cat-5',
    'gençlik,sohbet,değerler,ahlak'
),
(
    'video-5',
    'Vakıf Başkanı ile Röportaj',
    'Vakıf başkanımız ile vakfın kuruluşu, amaçları ve gelecek projeleri hakkında röportaj.',
    'https://www.youtube.com/watch?v=lOTeBj1iJVs',
    'https://img.youtube.com/vi/lOTeBj1iJVs/mqdefault.jpg',
    'published',
    0,
    1,
    'video-cat-4',
    'röportaj,başkan,kuruluş,gelecek'
),
(
    'video-6',
    'Yetim Çocuklarımızla Buluşma',
    'Vakfımızın desteklediği yetim çocuklarla yapılan güzel bir buluşmanın videosu.',
    'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
    'https://img.youtube.com/vi/dQw4w9WgXcQ/mqdefault.jpg',
    'published',
    0,
    1,
    'video-cat-2',
    'yetim,çocuk,buluşma,destek'
),
(
    'video-7',
    'Zekât ve Sadaka Hakkında',
    'İslam\'da zekât ve sadakanın önemi, hesaplanması ve verilmesi gereken durumlar.',
    'https://www.youtube.com/watch?v=jNQXAC9IVRw',
    'https://img.youtube.com/vi/jNQXAC9IVRw/mqdefault.jpg',
    'published',
    0,
    1,
    'video-cat-1',
    'zekât,sadaka,İslam,fıkıh'
),
(
    'video-8',
    'Çınaraltı Vakfı Tanıtım Filmi',
    'Vakfımızın kuruluşundan bugüne kadar olan yolculuğunu anlatan tanıtım filmi.',
    'https://www.youtube.com/watch?v=1y6smkh6c-0',
    'https://img.youtube.com/vi/1y6smkh6c-0/mqdefault.jpg',
    'published',
    0,
    1,
    'video-cat-6',
    'tanıtım,film,vakıf,tarihçe'
),
(
    'video-9',
    'Kurban Bayramı Etkinlikleri',
    'Kurban bayramında düzenlediğimiz et dağıtımı ve bayram etkinliklerinin kaydı.',
    'https://www.youtube.com/watch?v=QH2-TGUlwu4',
    'https://img.youtube.com/vi/QH2-TGUlwu4/mqdefault.jpg',
    'draft',
    0,
    1,
    'video-cat-2',
    'kurban,bayram,et,dağıtım'
),
(
    'video-10',
    'Kadın ve İslam Konferansı',
    'İslam\'da kadının yeri ve önemi hakkında düzenlenen konferansın video kaydı.',
    'https://www.youtube.com/watch?v=nLRL_NcnK-I',
    'https://img.youtube.com/vi/nLRL_NcnK-I/mqdefault.jpg',
    'published',
    0,
    1,
    'video-cat-5',
    'kadın,konferans,İslam,toplum'
)
ON DUPLICATE KEY UPDATE 
    title=VALUES(title),
    description=VALUES(description),
    url=VALUES(url),
    thumbnail=VALUES(thumbnail),
    status=VALUES(status),
    featured=VALUES(featured),
    updated_at=NOW(); 