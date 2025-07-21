-- ================================================================
-- Blog Yorumları Migration
-- Tarih: 2024-01-05
-- Açıklama: Blog yazıları için yorum sistemi tablosu
-- ================================================================

-- Blog yorumları tablosu
CREATE TABLE IF NOT EXISTS blog_comments (
    id VARCHAR(191) NOT NULL PRIMARY KEY,
    post_id VARCHAR(191) NOT NULL,
    parent_id VARCHAR(191) DEFAULT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    website VARCHAR(255) DEFAULT NULL,
    comment TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent TEXT DEFAULT NULL,
    created_at DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
    updated_at DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
    
    KEY idx_post_id (post_id),
    KEY idx_parent_id (parent_id),
    KEY idx_status (status),
    KEY idx_created_at (created_at),
    KEY idx_email (email),
    
    CONSTRAINT blog_comments_post_id_fkey FOREIGN KEY (post_id) REFERENCES blog_posts(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT blog_comments_parent_id_fkey FOREIGN KEY (parent_id) REFERENCES blog_comments(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Yorum sayısı için view
CREATE OR REPLACE VIEW blog_post_comment_stats AS
SELECT 
    bp.id as post_id,
    bp.title as post_title,
    COUNT(bc.id) as total_comments,
    COUNT(CASE WHEN bc.status = 'approved' THEN 1 END) as approved_comments,
    COUNT(CASE WHEN bc.status = 'pending' THEN 1 END) as pending_comments,
    COUNT(CASE WHEN bc.status = 'rejected' THEN 1 END) as rejected_comments
FROM blog_posts bp
LEFT JOIN blog_comments bc ON bp.id = bc.post_id
GROUP BY bp.id, bp.title;

-- Örnek yorum verileri (test için)
INSERT INTO blog_comments (id, post_id, name, email, comment, status, created_at) VALUES
('comment-1', 'clmz7x8y00010w8jm8h5j8k3s', 'Ahmet Yılmaz', 'ahmet@example.com', 'Çok güzel bir yazı olmuş, teşekkürler.', 'approved', NOW()),
('comment-2', 'clmz7x8y00010w8jm8h5j8k3s', 'Fatma Demir', 'fatma@example.com', 'Bu konu hakkında daha detaylı bilgi verebilir misiniz?', 'approved', NOW()),
('comment-3', 'clmz7x8y00011w8jm8h5j8k3t', 'Mehmet Kaya', 'mehmet@example.com', 'Çok faydalı bilgiler, ellerinize sağlık.', 'approved', NOW())
ON DUPLICATE KEY UPDATE comment = VALUES(comment);

-- Yorum tablosu hazır
SELECT 'Blog yorumları tablosu başarıyla oluşturuldu.' as message; 