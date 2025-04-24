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
            // Markdown dosyalarının bulunduğu klasör
            $blogDir = dirname(__DIR__) . '/content/blog/';

            // Hata ayıklama için - Klasör bulunabildi mi?
            if (!is_dir($blogDir)) {
              echo '<div class="alert alert-warning">Blog klasörü bulunamadı. Lütfen yöneticiyle iletişime geçin.</div>';
              $blogPosts = array();
            } else {
              // Dosyaları tarihe göre sıralayacak dizi
              $blogPosts = array();

              // Markdown dosyalarını okuma
              if ($dh = opendir($blogDir)) {
                while (($file = readdir($dh)) !== false) {
                  if (pathinfo($file, PATHINFO_EXTENSION) === 'md') {
                    $filePath = $blogDir . $file;
                    $content = file_get_contents($filePath);

                    // Markdown meta verilerini çıkarma (YAML front matter)
                    preg_match('/---\s*(.*?)\s*---\s*(.*)/s', $content, $matches);

                    if (count($matches) >= 3) {
                      $frontMatter = $matches[1];
                      $markdownContent = $matches[2];

                      // Meta verileri ayrıştırma
                      preg_match('/title:\s*"(.+?)"/s', $frontMatter, $titleMatch);
                      preg_match('/date:\s*"(.+?)"/s', $frontMatter, $dateMatch);
                      preg_match('/author:\s*"(.+?)"/s', $frontMatter, $authorMatch);
                      preg_match('/category:\s*"(.+?)"/s', $frontMatter, $categoryMatch);
                      preg_match('/image:\s*"(.+?)"/s', $frontMatter, $imageMatch);

                      $title = isset($titleMatch[1]) ? $titleMatch[1] : '';
                      $date = isset($dateMatch[1]) ? $dateMatch[1] : '';
                      $author = isset($authorMatch[1]) ? $authorMatch[1] : '';
                      $category = isset($categoryMatch[1]) ? $categoryMatch[1] : '';
                      $image = isset($imageMatch[1]) ? $imageMatch[1] : '';

                      // İçeriği kısaltma (özet olarak)
                      $summary = strip_tags(substr($markdownContent, 0, 150)) . '...';

                      // Blog yazısını diziye ekleme
                      $blogPosts[] = array(
                        'id' => pathinfo($file, PATHINFO_FILENAME),
                        'title' => $title,
                        'date' => $date,
                        'author' => $author,
                        'category' => $category,
                        'image' => $image,
                        'summary' => $summary,
                        'file' => pathinfo($file, PATHINFO_FILENAME)
                      );
                    }
                  }
                }
                closedir($dh);
              }
            }

            // Blog yazılarını tarihe göre sıralama (en yeni üstte)
            if (!empty($blogPosts)) {
              usort($blogPosts, function ($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
              });

              // Blog yazılarını gösterme
              foreach ($blogPosts as $post) {
                // Varsayılan blog görseli
                $imagePath = "../public/assets/image/blog/blog-image-1.png";

                $dateFormatted = date('d M Y', strtotime($post['date']));

                echo '
                <div class="blog-card-large mb-4">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="blog-card-large__image">
                        <img src="' . $imagePath . '" alt="' . $post['title'] . '" class="img-fluid">
                      </div>
                    </div>
                    <div class="col-md-8">
                      <div class="blog-card-large__body">
                        <div class="blog-card-large__meta mb-2">
                          <span class="blog-card-large__category badge bg-light text-dark me-2">' . $post['category'] . '</span>
                          <span class="blog-card-large__time text-muted"><i class="fa-regular fa-calendar-days me-1"></i>' . $dateFormatted . '</span>
                          <span class="blog-card-large__user text-muted ms-2"><i class="fa-regular fa-user me-1"></i>' . $post['author'] . '</span>
                        </div>
                        <a href="blog-details?id=' . $post['id'] . '">
                        <h3 class="blog-card-large__title h4">' . $post['title'] . '</h3>
                        </a>
                        <p class="text-muted">' . $post['summary'] . '</p>
                        <a href="blog-details?id=' . $post['id'] . '"
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
                // Kategorileri sayma
                $categories = array();
                foreach ($blogPosts as $post) {
                  if (isset($post['category'])) {
                    if (!isset($categories[$post['category']])) {
                      $categories[$post['category']] = 0;
                    }
                    $categories[$post['category']]++;
                  }
                }

                // Kategorileri gösterme
                foreach ($categories as $category => $count) {
                  echo '<li class="sidebar-category-list-item">
                    <a href="#" class="sidebar-category">
                      ' . $category . ' (' . $count . ')
                    </a>
                  </li>';
                }

                if (empty($categories)) {
                  echo '<li class="sidebar-category-list-item">Henüz kategori bulunmuyor</li>';
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
              // Son 3 blog yazısını gösterme
              $recentPosts = array_slice($blogPosts, 0, 3);

              if (!empty($recentPosts)) {
                foreach ($recentPosts as $post) {
                  // Varsayılan görsel
                  $imagePath = "../public/assets/image/blog/recent-1.png";

                  $dateFormatted = date('d M Y', strtotime($post['date']));

                  echo '
                  <div class="card mb-3">
                    <div class="row g-0">
                      <div class="col-4">
                        <img src="' . $imagePath . '" alt="' . $post['title'] . '" class="img-fluid rounded-start">
                      </div>
                      <div class="col-8">
                        <div class="card-body p-2">
                          <small class="text-muted d-block mb-1"><i class="fa-regular fa-calendar-days me-1"></i>' . $dateFormatted . '</small>
                          <h5 class="card-title h6">
                            <a href="blog-details?id=' . $post['id'] . '" class="text-decoration-none">
                ' . $post['title'] . '
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