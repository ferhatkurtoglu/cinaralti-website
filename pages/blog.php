<!-- Blog -->

<div class="inner_banner-section">
    <h3 class="inner_banner-title">Makaleler</h3>
</div>

<!-- ~~~~~~~~~~~~~~~~~~~~~
 BLog Page : main section
~~~~~~~~~~~~~~~~~~~~~~~-->
<div class="blog_main-section section-padding-120" style="margin-top: 50px;">
    <div class="container">
        <div class="row">
            <div class="col-xl-8">
                <div class="blog_content">
                    <div class="blog-card-large-row">
                        <?php
                        // Blog servisini dahil et
                        require_once dirname(__DIR__) . '/includes/blog-service.php';
                        
                        try {
                            $blogService = getBlogService();
                            
                            // Blog yazılarını veritabanından getir
                            $blogPosts = $blogService->getPublishedPosts();
                            
                            // Blog yazılarını göster
                            if (!empty($blogPosts)) {
                                foreach ($blogPosts as $post) {
                                    // Görsel yolunu al
                                    $imagePath = $blogService->getImagePath($post['cover_image']);
                                    
                                    // Tarihi formatla
                                    $dateFormatted = $blogService->formatDate($post['created_at']);
                                    
                                    // Özet oluştur
                                    $excerpt = !empty($post['excerpt']) 
                                        ? $post['excerpt'] 
                                        : $blogService->generateExcerpt($post['content']);
                                    
                                    // Kategori adını al
                                    $categoryName = $post['category_name'] ?? 'Genel';
                                    
                                    // Yazar adını al
                                    $authorName = $post['author_name'] ?? 'Çınaraltı Vakfı';
                                    
                                    echo '
                                    <div class="blog-card-large mb-4">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="blog-card-large__image">
                                                    <img src="' . $imagePath . '" alt="' . htmlspecialchars($post['title']) . '" class="img-fluid">
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="blog-card-large__body">
                                                    <div class="blog-card-large__meta mb-2">
                                                        <span class="blog-card-large__category badge bg-light text-dark me-2">' . htmlspecialchars($categoryName) . '</span>
                                                        <span class="blog-card-large__time text-muted"><i class="fa-regular fa-calendar-days me-1"></i>' . $dateFormatted . '</span>
                                                        <span class="blog-card-large__user text-muted ms-2"><i class="fa-regular fa-user me-1"></i>' . htmlspecialchars($authorName) . '</span>
                                                    </div>
                                                    <a href="blog-details?slug=' . htmlspecialchars($post['slug']) . '">
                                                        <h3 class="blog-card-large__title h4">' . htmlspecialchars($post['title']) . '</h3>
                                                    </a>
                                                    <p class="text-muted">' . htmlspecialchars($excerpt) . '</p>
                                                    <a href="blog-details?slug=' . htmlspecialchars($post['slug']) . '"
                                                        class="btn btn-sm btn-outline-primary">
                                                        Devamını Oku <i class="fa-solid fa-arrow-right ms-1"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
                                }
                            } else {
                                echo '<div class="alert alert-info">Henüz blog yazısı bulunmuyor.</div>';
                            }
                        } catch (Exception $e) {
                            echo '<div class="alert alert-danger">Blog yazıları yüklenirken bir hata oluştu.</div>';
                        }
                        ?>
                    </div>
                </div>
                <div class="pagination mt-4">
                    <!-- Pagination -->
                    <div class="pagination-wrapper">
                        <button class="btn btn--arrow">
                            <i class="fa fa-angle-left"></i>
                        </button>
                        <button class="btn btn-main active">1</button>
                        <button class="btn btn-main">2</button>
                        <button class="btn btn-main">3</button>
                        <button class="btn btn--arrow">
                            <i class="fa fa-angle-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <aside class="sidebar-wrapper">
                    <div class="sidebar-search-input">
                        <form action="#" class="input-wrapper">
                            <span class="input-icon">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>
                            <input class="form-control" type="text" placeholder="Aramak için yazın...">
                        </form>
                    </div>
                    <div class="sidebar-single">
                        <div class="sidebar-list">
                            <div class="sidebar-title-block">
                                <h3 class="sidebar-title">Makale Kategorileri</h3>
                            </div>
                            <ul class="sidebar-category-list">
                                <?php
                                try {
                                    // Kategorileri veritabanından getir
                                    $categories = $blogService->getCategories();
                                    
                                    if (!empty($categories)) {
                                        foreach ($categories as $category) {
                                            echo '<li class="sidebar-category-list-item">
                                                <a href="#" class="sidebar-category">
                                                    ' . htmlspecialchars($category['name']) . ' (' . $category['post_count'] . ')
                                                </a>
                                            </li>';
                                        }
                                    } else {
                                        echo '<li class="sidebar-category-list-item">Henüz kategori bulunmuyor</li>';
                                    }
                                } catch (Exception $e) {
                                    echo '<li class="sidebar-category-list-item">Kategoriler yüklenirken hata oluştu</li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <div class="sidebar-single">
                        <div class="sidebar-title-block">
                            <h3 class="sidebar-title">Son Gönderiler</h3>
                        </div>
                        <div class="sidebar-blog-widget-wrapper">
                            <?php
                            try {
                                // Son 3 blog yazısını getir
                                $recentPosts = $blogService->getRecentPosts(3);
                                
                                if (!empty($recentPosts)) {
                                    foreach ($recentPosts as $post) {
                                        // Görsel yolunu al
                                        $imagePath = $blogService->getImagePath($post['cover_image']);
                                        
                                        // Tarihi formatla
                                        $dateFormatted = $blogService->formatDate($post['created_at']);
                                        
                                        echo '
                                        <div class="card mb-3">
                                            <div class="row g-0">
                                                <div class="col-4">
                                                    <img src="' . $imagePath . '" alt="' . htmlspecialchars($post['title']) . '" class="img-fluid rounded-start">
                                                </div>
                                                <div class="col-8">
                                                    <div class="card-body p-2">
                                                        <small class="text-muted d-block mb-1"><i class="fa-regular fa-calendar-days me-1"></i>' . $dateFormatted . '</small>
                                                        <h5 class="card-title h6">
                                                            <a href="blog-details?slug=' . htmlspecialchars($post['slug']) . '" class="text-decoration-none">
                                                                ' . htmlspecialchars($post['title']) . '
                                                            </a>
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>';
                                    }
                                } else {
                                    echo '<div class="card card-body">Henüz blog yazısı bulunmuyor</div>';
                                }
                            } catch (Exception $e) {
                                echo '<div class="card card-body">Son gönderiler yüklenirken hata oluştu</div>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="sidebar-single">
                        <div class="sidebar-title-block">
                            <h3 class="sidebar-title">Etiketler</h3>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="#" class="badge bg-light text-dark text-decoration-none p-2">İman</a>
                            <a href="#" class="badge bg-light text-dark text-decoration-none p-2">Ahiret</a>
                            <a href="#" class="badge bg-light text-dark text-decoration-none p-2">Namaz</a>
                            <a href="#" class="badge bg-light text-dark text-decoration-none p-2">Kur'an</a>
                            <a href="#" class="badge bg-light text-dark text-decoration-none p-2">Tevekkül</a>
                            <a href="#" class="badge bg-light text-dark text-decoration-none p-2">Sabır</a>
                            <a href="#" class="badge bg-light text-dark text-decoration-none p-2">Risale-i Nur</a>
                            <a href="#" class="badge bg-light text-dark text-decoration-none p-2">Haşir</a>
                            <a href="#" class="badge bg-light text-dark text-decoration-none p-2">İslam</a>
                            <a href="#" class="badge bg-light text-dark text-decoration-none p-2">Mucize</a>
                        </div>
                    </div>
                    <div class="sidebar-single">
                        <div class="sidebar-title-block">
                            <h3 class="sidebar-title">Bülten</h3>
                        </div>
                        <div class="card card-body bg-light">
                            <p class="mb-3">
                                Bültenimize abone olun ve son haberlerden anında haberdar olun
                            </p>
                            <form action="#" class="input-wrapper">
                                <input class="form-control mb-2" type="text" placeholder="E-posta adresinizi girin">
                                <button type="submit" class="btn btn-primary w-100">Abone Ol</button>
                            </form>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</div>