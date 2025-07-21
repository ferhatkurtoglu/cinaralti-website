<?php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../services/video-service.php';

// Video servisini başlat
$videoService = new VideoService();

// Sayfalama parametreleri
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 6;
$search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';
$category = isset($_GET['category']) ? sanitize_input($_GET['category']) : '';

// Videoları getir
if (!empty($search)) {
    $videos = $videoService->searchVideos($search, $page, $limit);
    $totalVideos = count($videos); // Arama için toplam sayı farklı hesaplanabilir
} elseif (!empty($category)) {
    $videos = $videoService->getVideosByCategory($category, $page, $limit);
    $totalVideos = $videoService->getTotalVideoCountByCategory($category);
} else {
    $videos = $videoService->getAllVideos($page, $limit);
    $totalVideos = $videoService->getTotalVideoCount();
}

// Öne çıkan videoları getir
$featuredVideos = $videoService->getFeaturedVideos(3);

// Video kategorilerini getir
$categories = $videoService->getVideoCategories();

// Sayfa bilgileri
$totalPages = ceil($totalVideos / $limit);
$currentPage = $page;

?>

<div class="inner_banner-section">
    <h3 class="inner_banner-title">Videolar</h3>
</div>

<!-- Video Page : main section -->
<div class="blog_main-section section-padding-120">
    <div class="container">
        <div class="row">
            <div class="col-xl-8">
                <div class="blog_content">
                    <h2 class="section-title mb-4">
                        <?php if (!empty($search)): ?>
                        "<?php echo htmlspecialchars($search); ?>" için arama sonuçları
                        <?php else: ?>
                        Son Videolarımız
                        <?php endif; ?>
                    </h2>

                    <?php if (empty($videos)): ?>
                    <div class="no-results">
                        <p>
                            <?php if (!empty($search)): ?>
                            Arama kriterlerinize uygun video bulunamadı.
                            <?php else: ?>
                            Henüz yayınlanmış video bulunmamaktadır.
                            <?php endif; ?>
                        </p>
                    </div>
                    <?php else: ?>
                    <div class="video-cards-row">
                        <?php foreach ($videos as $video): ?>
                        <div class="video-card">
                            <div class="video-card__frame">
                                <iframe width="100%" height="315"
                                    src="<?php echo $videoService->getYouTubeEmbedUrl($video['url']); ?>"
                                    title="<?php echo htmlspecialchars($video['title']); ?>" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen>
                                </iframe>
                            </div>
                            <div class="video-card__body">
                                <div class="video-card__meta">
                                    <span class="video-card__date">
                                        <i class="fa-regular fa-calendar-days" style="color: #059669; font-size: 16px; margin-right: 6px;"></i>
                                        <?php echo $videoService->formatDateTurkish($video['created_at']); ?>
                                    </span>
                                    <?php if ($video['category_name']): ?>
                                    <span
                                        class="video-card__category"><?php echo htmlspecialchars($video['category_name']); ?></span>
                                    <?php endif; ?>
                                </div>
                                <h2 class="video-card__title">
                                    <a href="<?php echo BASE_URL; ?>/video-details?id=<?php echo $video['id']; ?>"
                                        style="text-decoration: none; color: inherit;">
                                        <?php echo htmlspecialchars($video['title']); ?>
                                    </a>
                                </h2>
                                <?php if ($video['description']): ?>
                                <p><?php echo htmlspecialchars(substr($video['description'], 0, 150)); ?><?php echo strlen($video['description']) > 150 ? '...' : ''; ?>
                                </p>
                                <?php endif; ?>

                                <!-- Detaya Git Butonu -->
                                <div class="video-card__actions" style="margin-top: 20px;">
                                    <a href="<?php echo BASE_URL; ?>/video-details?id=<?php echo $video['id']; ?>"
                                        class="btn btn-primary btn-sm">
                                        <i class="fas fa-play"></i> Detaya Git
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <div class="pagination-wrapper">
                            <?php 
                                     // URL parametrelerini oluştur
                                     $urlParams = '';
                                     if (!empty($search)) $urlParams .= '&search=' . urlencode($search);
                                     if (!empty($category)) $urlParams .= '&category=' . urlencode($category);
                                     ?>

                            <?php if ($currentPage > 1): ?>
                            <a href="?page=<?php echo $currentPage - 1; ?><?php echo $urlParams; ?>"
                                class="btn btn--arrow">
                                <i class="fa fa-angle-left"></i>
                            </a>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <?php if ($i == $currentPage): ?>
                            <button class="btn btn-main active"><?php echo $i; ?></button>
                            <?php else: ?>
                            <a href="?page=<?php echo $i; ?><?php echo $urlParams; ?>"
                                class="btn btn-main"><?php echo $i; ?></a>
                            <?php endif; ?>
                            <?php endfor; ?>

                            <?php if ($currentPage < $totalPages): ?>
                            <a href="?page=<?php echo $currentPage + 1; ?><?php echo $urlParams; ?>"
                                class="btn btn--arrow">
                                <i class="fa fa-angle-right"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-xl-4">
                <aside class="sidebar-wrapper">
                    <!-- Arama formu -->
                    <div class="sidebar-search-input">
                        <form action="" method="GET" class="input-wrapper">
                            <span class="input-icon">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>
                            <input class="form-control" type="text" name="search" placeholder="Video ara..."
                                value="<?php echo htmlspecialchars($search); ?>">
                            <?php if (isset($_GET['page']) && $_GET['page'] > 1): ?>
                            <input type="hidden" name="page" value="1">
                            <?php endif; ?>
                        </form>
                    </div>

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
                                    <a href="?category=<?php echo urlencode($category['slug']); ?>"
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

                    <!-- Popüler Videolar -->
                    <?php if (!empty($featuredVideos)): ?>
                    <div class="sidebar-single">
                        <div class="sidebar-blog-suggestion">
                            <div class="sidebar-title-block">
                                <h3 class="sidebar-title">Öne Çıkan Videolar</h3>
                            </div>
                            <div class="sidebar-blog-widget-wrapper">
                                <?php foreach ($featuredVideos as $featuredVideo): ?>
                                <div class="sidebar-blog-widget">
                                    <a href="<?php echo $featuredVideo['url']; ?>" target="_blank"
                                        class="sidebar-blog-widget__image">
                                        <img src="<?php echo $videoService->getYouTubeThumbnail($featuredVideo['url']); ?>"
                                            alt="<?php echo htmlspecialchars($featuredVideo['title']); ?>" 
                                            style="width: 100%; height: 60px; object-fit: cover; border-radius: 8px;">
                                    </a>
                                    <div class="sidebar-blog-widget__body">
                                        <div class="sidebar-blog-widget__date" style="display: flex; align-items: center; margin-bottom: 8px;">
                                            <i class="fa-regular fa-calendar-days" style="color: #059669; font-size: 14px; margin-right: 6px;"></i>
                                            <span style="color: #666; font-size: 13px;"><?php echo $videoService->formatDateTurkish($featuredVideo['created_at']); ?></span>
                                        </div>
                                        <h3 class="sidebar-blog-widget__title">
                                            <a href="<?php echo $featuredVideo['url']; ?>" target="_blank" 
                                               style="color: #333; text-decoration: none; font-size: 14px; line-height: 1.4;">
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

                    <!-- Etiketler -->
                    <div class="sidebar-single">
                        <div class="sidebar-title-block">
                            <h3 class="sidebar-title">Etiketler</h3>
                        </div>
                        <ul class="sidebar-tag-list">
                            <li class="sidebar-tag-list-item"><a href="?search=eğitim">Eğitim</a></li>
                            <li class="sidebar-tag-list-item"><a href="?search=tanıtım">Tanıtım</a></li>
                            <li class="sidebar-tag-list-item"><a href="?search=etkinlik">Etkinlik</a></li>
                            <li class="sidebar-tag-list-item"><a href="?search=röportaj">Röportaj</a></li>
                            <li class="sidebar-tag-list-item"><a href="?search=webinar">Webinar</a></li>
                            <li class="sidebar-tag-list-item"><a href="?search=sunum">Sunum</a></li>
                            <li class="sidebar-tag-list-item"><a href="?search=workshop">Workshop</a></li>
                            <li class="sidebar-tag-list-item"><a href="?search=seminer">Seminer</a></li>
                            <li class="sidebar-tag-list-item"><a href="?search=konferans">Konferans</a></li>
                            <li class="sidebar-tag-list-item"><a href="?search=belgesel">Belgesel</a></li>
                        </ul>
                    </div>

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
                </aside>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>