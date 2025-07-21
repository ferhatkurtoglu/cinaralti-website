<?php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../services/video-service.php';

// Video servisini başlat
$videoService = new VideoService();

// Video ID'sini al
$videoId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($videoId === 0) {
    header('Location: video.php');
    exit;
}

// Videoyu getir
$video = $videoService->getVideoById($videoId);

if (!$video) {
    header('Location: video.php');
    exit;
}

// İlgili videoları getir (aynı kategoriden)
$relatedVideos = [];
if ($video['category_id']) {
    $relatedVideos = $videoService->getVideosByCategory($video['category_slug'], 1, 3);
    // Mevcut videoyu listeden çıkar
    $relatedVideos = array_filter($relatedVideos, function($v) use ($videoId) {
        return $v['id'] != $videoId;
    });
    $relatedVideos = array_slice($relatedVideos, 0, 2);
}

// Öne çıkan videoları getir
$featuredVideos = $videoService->getFeaturedVideos(3);

// Video kategorilerini getir
$categories = $videoService->getVideoCategories();

?>

<div class="inner_banner-section">
    <h3 class="inner_banner-title"><?php echo htmlspecialchars($video['title']); ?></h3>
</div>

<!-- Video Detay : main section -->
<div class="blog_main-section section-padding-120">
    <div class="container">
        <div class="row">
            <div class="col-xl-8">
                <div class="video-detail-content">
                    <!-- Video Player -->
                    <div class="video-detail__player">
                        <iframe width="100%" height="500"
                            src="<?php echo $videoService->getYouTubeEmbedUrl($video['url']); ?>"
                            title="<?php echo htmlspecialchars($video['title']); ?>" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen>
                        </iframe>
                    </div>

                    <!-- Video Bilgileri -->
                    <div class="video-detail__info">
                        <div class="video-detail__meta">
                            <span class="video-detail__date">
                                <img src="../public/assets/image/blog/blog-card-icon.svg" alt="calendar icon">
                                <?php echo $videoService->formatDateTurkish($video['created_at']); ?>
                            </span>
                            <?php if ($video['category_name']): ?>
                            <span class="video-detail__category">
                                <a
                                    href="<?php echo BASE_URL; ?>/video?category=<?php echo urlencode($video['category_slug']); ?>">
                                    <?php echo htmlspecialchars($video['category_name']); ?>
                                </a>
                            </span>
                            <?php endif; ?>
                        </div>

                        <h1 class="video-detail__title"><?php echo htmlspecialchars($video['title']); ?></h1>

                        <?php if ($video['description']): ?>
                        <div class="video-detail__description">
                            <p><?php echo nl2br(htmlspecialchars($video['description'])); ?></p>
                        </div>
                        <?php endif; ?>

                        <!-- Sosyal Paylaşım -->
                        <div class="video-detail__share">
                            <h4>Bu videoyu paylaş:</h4>
                            <div class="share-buttons">
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($video['url']); ?>"
                                    target="_blank" class="share-btn share-btn--facebook">
                                    <i class="fab fa-facebook-f"></i> Facebook
                                </a>
                                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($video['url']); ?>&text=<?php echo urlencode($video['title']); ?>"
                                    target="_blank" class="share-btn share-btn--twitter">
                                    <i class="fab fa-twitter"></i> Twitter
                                </a>
                                <a href="https://wa.me/?text=<?php echo urlencode($video['title'] . ' - ' . $video['url']); ?>"
                                    target="_blank" class="share-btn share-btn--whatsapp">
                                    <i class="fab fa-whatsapp"></i> WhatsApp
                                </a>
                                <a href="<?php echo $video['url']; ?>" target="_blank"
                                    class="share-btn share-btn--youtube">
                                    <i class="fab fa-youtube"></i> YouTube'da İzle
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- İlgili Videolar -->
                    <?php if (!empty($relatedVideos)): ?>
                    <div class="related-videos">
                        <h3 class="related-videos__title">İlgili Videolar</h3>
                        <div class="related-videos__list">
                            <?php foreach ($relatedVideos as $relatedVideo): ?>
                            <div class="related-video-item">
                                <a href="<?php echo BASE_URL; ?>/video-details?id=<?php echo $relatedVideo['id']; ?>"
                                    class="related-video-item__thumbnail">
                                    <img src="<?php echo $videoService->getYouTubeThumbnail($relatedVideo['url']); ?>"
                                        alt="<?php echo htmlspecialchars($relatedVideo['title']); ?>">
                                    <div class="play-overlay">
                                        <i class="fas fa-play"></i>
                                    </div>
                                </a>
                                <div class="related-video-item__info">
                                    <h4 class="related-video-item__title">
                                        <a
                                            href="<?php echo BASE_URL; ?>/video-details?id=<?php echo $relatedVideo['id']; ?>">
                                            <?php echo htmlspecialchars($relatedVideo['title']); ?>
                                        </a>
                                    </h4>
                                    <span class="related-video-item__date">
                                        <?php echo $videoService->formatDateTurkish($relatedVideo['created_at']); ?>
                                    </span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-xl-4">
                <aside class="sidebar-wrapper">
                    <!-- Video Kategorileri -->
                    <?php if (!empty($categories)): ?>
                    <div class="sidebar-single">
                        <div class="sidebar-list">
                            <div class="sidebar-title-block">
                                <h3 class="sidebar-title">Video Kategorileri</h3>
                            </div>
                            <ul class="sidebar-category-list">
                                <?php foreach ($categories as $category): ?>
                                <li class="sidebar-category-list-item">
                                    <a href="<?php echo BASE_URL; ?>/video?category=<?php echo urlencode($category['slug']); ?>"
                                        class="sidebar-category">
                                        <?php echo htmlspecialchars($category['name']); ?>
                                        (<?php echo $category['video_count']; ?>)
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Öne Çıkan Videolar -->
                    <?php if (!empty($featuredVideos)): ?>
                    <div class="sidebar-single">
                        <div class="sidebar-blog-suggestion">
                            <div class="sidebar-title-block">
                                <h3 class="sidebar-title">Öne Çıkan Videolar</h3>
                            </div>
                            <div class="sidebar-blog-widget-wrapper">
                                <?php foreach ($featuredVideos as $featuredVideo): ?>
                                <div class="sidebar-blog-widget">
                                    <a href="<?php echo BASE_URL; ?>/video-details?id=<?php echo $featuredVideo['id']; ?>"
                                        class="sidebar-blog-widget__image">
                                        <img src="<?php echo $videoService->getYouTubeThumbnail($featuredVideo['url']); ?>"
                                            alt="<?php echo htmlspecialchars($featuredVideo['title']); ?>">
                                    </a>
                                    <div class="sidebar-blog-widget__body">
                                        <a href="<?php echo BASE_URL; ?>/video-details?id=<?php echo $featuredVideo['id']; ?>"
                                            class="sidebar-blog-widget__date">
                                            <img src="../public/assets/image/blog/calendar.svg" alt="takvim">
                                            <?php echo $videoService->formatDateTurkish($featuredVideo['created_at']); ?>
                                        </a>
                                        <h3 class="sidebar-blog-widget__title">
                                            <a
                                                href="<?php echo BASE_URL; ?>/video-details?id=<?php echo $featuredVideo['id']; ?>">
                                                <?php echo htmlspecialchars($featuredVideo['title']); ?>
                                            </a>
                                        </h3>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- YouTube Kanalımız -->
                    <div class="sidebar-single">
                        <div class="sidebar-title-block">
                            <h3 class="sidebar-title">YouTube Kanalımız</h3>
                        </div>
                        <div class="sidebar-newsletter">
                            <p>
                                YouTube kanalımıza abone olarak tüm yeni videolardan haberdar olabilirsiniz
                            </p>
                            <div class="sidebar-button">
                                <a href="https://www.youtube.com/@cinaraltindayiz" target="_blank"
                                    class="btn-masco btn-primary rounded-pill w-100">
                                    Kanalımıza Abone Ol
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Tüm Videolar -->
                    <div class="sidebar-single">
                        <div class="sidebar-title-block">
                            <h3 class="sidebar-title">Tüm Videolar</h3>
                        </div>
                        <div class="sidebar-newsletter">
                            <p>
                                Tüm videolarımızı görüntülemek için video sayfamızı ziyaret edin
                            </p>
                            <div class="sidebar-button">
                                <a href="<?php echo BASE_URL; ?>/video"
                                    class="btn-masco btn-outline-primary rounded-pill w-100">
                                    Tüm Videoları Gör
                                </a>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</div>