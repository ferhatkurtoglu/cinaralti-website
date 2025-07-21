<?php
// Config dosyasını dahil et
require_once dirname(__DIR__) . '/config/config.php';

// Güvenli oturum başlat
secure_session_start();

// Blog servisini dahil et
require_once dirname(__DIR__) . '/includes/blog-service.php';

// İstenen blog yazısını al
$requestedSlug = isset($_GET['slug']) ? $_GET['slug'] : (isset($_GET['id']) ? $_GET['id'] : null);
$blogData = null;

if ($requestedSlug) {
    try {
        $blogService = getBlogService();
        
        // Slug ile blog yazısını getir
        $blogData = $blogService->getPostBySlug($requestedSlug);
        
        // Eğer slug ile bulunamazsa, ID ile dene (eski URL'ler için)
        if (!$blogData) {
            $blogData = $blogService->getPostById($requestedSlug);
        }
    } catch (Exception $e) {
        // Hata durumunda blog bulunamadı olarak işle
        $blogData = null;
    }
}

// Blog yazısı bulunamadıysa yönlendir
if (!$blogData) {
    echo '<div class="alert alert-warning">Blog yazısı bulunamadı.</div>';
    echo '<div class="text-center mt-3 mb-5"><a href="blog" class="btn btn-primary">Blog Sayfasına Dön</a></div>';
    return;
}

// Blog verilerini hazırla
$imagePath = $blogService->getImagePath($blogData['cover_image']);
$dateFormatted = $blogService->formatDate($blogData['created_at']);
$categoryName = $blogData['category_name'] ?? 'Genel';
$authorName = $blogData['author_name'] ?? 'Çınaraltı Vakfı';

// Onaylanmış yorumları veritabanından getir
$approvedComments = $blogService->getComments($blogData['id'], 'approved');

// Yorum sayısını al
$commentCount = $blogService->getCommentCount($blogData['id'], 'approved');
?>

<div class="blog-details_main-section">
    <div class="inner_banner-section text-center">
        <div class="container">
            <h3 class="inner_banner-title"><?php echo htmlspecialchars($blogData['title']); ?></h3>
        </div>
    </div>
    <div class="container">
        <div class="blog-details_main-wrapper section-padding-120">
            <div class="row">
                <div class="col-xl-8">
                    <div class="blog-content">
                        <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($blogData['title']); ?>"
                            class="mb-30 w-100" style="max-height: 400px; object-fit: cover;">
                        <div class="blog-content-meta">
                            <a href="#">
                                <span class="blog-content__user">
                                    <i class="fa-solid fa-user-tie"
                                        style="color: #2563eb; font-size: 18px; margin-right: 8px;"></i>
                                    <?php echo htmlspecialchars($authorName); ?>
                                </span>
                            </a>
                            <a href="#">
                                <span class="blog-content__time">
                                    <i class="fa-regular fa-calendar-days"
                                        style="color: #059669; font-size: 18px; margin-right: 8px;"></i>
                                    <?php echo $dateFormatted; ?>
                                </span>
                            </a>
                            <a href="#">
                                <span
                                    class="blog-content__category"><?php echo htmlspecialchars($categoryName); ?></span>
                            </a>
                        </div>
                        <div class="blog-content-wrapper">
                            <?php echo $blogData['content']; ?>
                            <div class="blog-content__social-options">
                                <div class="social-options-left">
                                    <span title="Beğen" style="cursor: pointer;"
                                        onclick="handleLike(this, '<?php echo $blogData['id']; ?>')">
                                        <i class="far fa-heart like-icon"></i>
                                        <span class="like-count">0</span>
                                    </span>
                                    <span title="Yorum Yap" style="cursor: pointer;" onclick="showMainCommentForm()">
                                        <i class="far fa-comment"></i>
                                        <span><?php echo $commentCount; ?></span>
                                    </span>
                                </div>
                                <div class="social-options-right">
                                    <span title="Paylaş" style="cursor: pointer; position: relative;"
                                        onclick="toggleShareMenu(this)">
                                        <i class="fa-solid fa-share-nodes"></i>
                                        <div class="share-menu" style="display: none;">
                                            <a href="#"
                                                onclick="shareOnWhatsApp('<?php echo BASE_URL; ?>/blog-details?slug=<?php echo $blogData['slug']; ?>', '<?php echo htmlspecialchars($blogData['title']); ?>'); return false;"
                                                title="WhatsApp'ta Paylaş">
                                                <i class="fab fa-whatsapp" style="color: #25D366; font-size: 20px;"></i>
                                            </a>
                                            <a href="#"
                                                onclick="shareOnFacebook('<?php echo BASE_URL; ?>/blog-details?slug=<?php echo $blogData['slug']; ?>'); return false;"
                                                title="Facebook'ta Paylaş">
                                                <i class="fab fa-facebook" style="color: #1877F2; font-size: 20px;"></i>
                                            </a>
                                            <a href="#"
                                                onclick="shareOnTwitter('<?php echo BASE_URL; ?>/blog-details?slug=<?php echo $blogData['slug']; ?>', '<?php echo htmlspecialchars($blogData['title']); ?>'); return false;"
                                                title="X'te Paylaş">
                                                <i class="fab fa-x-twitter"
                                                    style="color: #000000; font-size: 20px;"></i>
                                            </a>
                                            <a href="#"
                                                onclick="shareOnTelegram('<?php echo BASE_URL; ?>/blog-details?slug=<?php echo $blogData['slug']; ?>', '<?php echo htmlspecialchars($blogData['title']); ?>'); return false;"
                                                title="Telegram'da Paylaş">
                                                <i class="fab fa-telegram" style="color: #0088cc; font-size: 20px;"></i>
                                            </a>
                                            <a href="#"
                                                onclick="copyLink('<?php echo BASE_URL; ?>/blog-details?slug=<?php echo $blogData['slug']; ?>'); return false;"
                                                title="Bağlantıyı Kopyala">
                                                <i class="fas fa-link" style="color: #666666; font-size: 20px;"></i>
                                            </a>
                                        </div>
                                    </span>
                                    <span title="Kaydet" style="cursor: pointer;"
                                        onclick="handleSave(this, '<?php echo $blogData['id']; ?>')">
                                        <i class="far fa-bookmark save-icon"></i>
                                    </span>
                                </div>
                            </div>

                            <!-- Font Awesome CDN -->
                            <link rel="stylesheet"
                                href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

                            <style>
                            .blog-content__social-options span {
                                position: relative;
                                display: inline-block;
                            }

                            .blog-content__social-options span:hover::after {
                                content: attr(title);
                                position: absolute;
                                bottom: 100%;
                                left: 50%;
                                transform: translateX(-50%);
                                padding: 4px 8px;
                                background-color: rgba(0, 0, 0, 0.8);
                                color: white;
                                font-size: 12px;
                                border-radius: 4px;
                                white-space: nowrap;
                                margin-bottom: 5px;
                                z-index: 1000;
                            }

                            .blog-content__social-options span:hover::before {
                                content: '';
                                position: absolute;
                                bottom: 100%;
                                left: 50%;
                                transform: translateX(-50%);
                                border: 5px solid transparent;
                                border-top-color: rgba(0, 0, 0, 0.8);
                                margin-bottom: -5px;
                                z-index: 1000;
                            }

                            .blog-content__social-options span.active::after {
                                display: none;
                            }

                            .like-icon.active {
                                color: #ff4b4b !important;
                                font-weight: 900;
                            }

                            .save-icon.active {
                                color: #0066ff !important;
                                font-weight: 900;
                            }

                            .share-menu {
                                position: absolute;
                                top: -50px;
                                right: 0;
                                background: white;
                                border-radius: 8px;
                                padding: 10px;
                                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                                display: flex;
                                gap: 12px;
                                z-index: 1000;
                            }

                            .share-menu::after {
                                content: '';
                                position: absolute;
                                bottom: -5px;
                                right: 10px;
                                width: 10px;
                                height: 10px;
                                background: white;
                                transform: rotate(45deg);
                                box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.05);
                            }

                            .share-menu a {
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                width: 32px;
                                height: 32px;
                                border-radius: 50%;
                                transition: all 0.2s ease;
                            }

                            .share-menu a:hover {
                                background: #f5f5f5;
                                transform: scale(1.1);
                            }

                            .social-options-right i {
                                font-size: 18px;
                                color: #666;
                            }

                            .social-options-left i {
                                font-size: 18px;
                                color: #666;
                                margin-right: 4px;
                            }

                            /* Toast bildirim stili */
                            .toast {
                                position: fixed;
                                bottom: 20px;
                                right: 20px;
                                padding: 12px 24px;
                                border-radius: 4px;
                                color: white;
                                z-index: 1000;
                                animation: slideIn 0.3s ease-out;
                            }

                            .toast.success {
                                background-color: #4CAF50;
                            }

                            .toast.error {
                                background-color: #f44336;
                            }

                            @keyframes slideIn {
                                from {
                                    transform: translateX(100%);
                                    opacity: 0;
                                }

                                to {
                                    transform: translateX(0);
                                    opacity: 1;
                                }
                            }
                            </style>

                            <!-- Toast bildirim elementi -->
                            <div id="toast" class="toast"></div>

                            <script>
                            // Toast bildirimi gösterme fonksiyonu
                            function showToast(message, type = 'success') {
                                const toast = document.getElementById('toast');
                                toast.className = `toast ${type}`;
                                toast.textContent = message;
                                toast.style.display = 'block';

                                setTimeout(() => {
                                    toast.style.display = 'none';
                                }, 3000);
                            }

                            // Beğenme işlemi
                            function handleLike(element, postId) {
                                const likeIcon = element.querySelector('.like-icon');
                                const likeCount = element.querySelector('.like-count');
                                const isLiked = likeIcon.classList.contains('active');

                                // Local storage'da beğeni durumunu kontrol et
                                let likedPosts = JSON.parse(localStorage.getItem('likedPosts') || '[]');

                                if (!isLiked) {
                                    // Beğeni ekle
                                    likeIcon.classList.remove('far');
                                    likeIcon.classList.add('fas', 'active');
                                    likedPosts.push(postId);
                                    let count = parseInt(likeCount.textContent);
                                    likeCount.textContent = isNaN(count) ? '1' : (count + 1).toString();
                                    showToast('Gönderi beğenildi!');
                                } else {
                                    // Beğeniyi kaldır
                                    likeIcon.classList.remove('fas', 'active');
                                    likeIcon.classList.add('far');
                                    likedPosts = likedPosts.filter(id => id !== postId);
                                    let count = parseInt(likeCount.textContent);
                                    likeCount.textContent = isNaN(count) ? '0' : (count - 1).toString();
                                    showToast('Beğeni kaldırıldı');
                                }

                                localStorage.setItem('likedPosts', JSON.stringify(likedPosts));
                            }

                            // Kaydetme işlemi
                            function handleSave(element, postId) {
                                const saveIcon = element.querySelector('.save-icon');
                                const isSaved = saveIcon.classList.contains('active');

                                // Local storage'da kayıtlı gönderileri kontrol et
                                let savedPosts = JSON.parse(localStorage.getItem('savedPosts') || '[]');

                                if (!isSaved) {
                                    // Kaydet
                                    saveIcon.classList.remove('far');
                                    saveIcon.classList.add('fas', 'active');
                                    savedPosts.push(postId);
                                    showToast('Gönderi kaydedildi!');
                                } else {
                                    // Kayıttan kaldır
                                    saveIcon.classList.remove('fas', 'active');
                                    saveIcon.classList.add('far');
                                    savedPosts = savedPosts.filter(id => id !== postId);
                                    showToast('Gönderi kayıtlardan kaldırıldı');
                                }

                                localStorage.setItem('savedPosts', JSON.stringify(savedPosts));
                            }

                            // Bağlantı kopyalama
                            function copyLink(url) {
                                const tempInput = document.createElement('input');
                                document.body.appendChild(tempInput);
                                tempInput.value = url;
                                tempInput.select();
                                document.execCommand('copy');
                                document.body.removeChild(tempInput);
                                showToast('Bağlantı panoya kopyalandı!');
                            }

                            // Sayfa yüklendiğinde beğeni ve kayıt durumlarını kontrol et
                            document.addEventListener('DOMContentLoaded', function() {
                                const postId = '<?php echo $blogData['id']; ?>';

                                // Beğeni durumunu kontrol et
                                const likedPosts = JSON.parse(localStorage.getItem('likedPosts') || '[]');
                                if (likedPosts.includes(postId)) {
                                    const likeIcon = document.querySelector('.like-icon');
                                    likeIcon.classList.remove('far');
                                    likeIcon.classList.add('fas', 'active');
                                }

                                // Kayıt durumunu kontrol et
                                const savedPosts = JSON.parse(localStorage.getItem('savedPosts') || '[]');
                                if (savedPosts.includes(postId)) {
                                    const saveIcon = document.querySelector('.save-icon');
                                    saveIcon.classList.remove('far');
                                    saveIcon.classList.add('fas', 'active');
                                }
                            });

                            function toggleShareMenu(element) {
                                const menu = element.querySelector('.share-menu');
                                const isVisible = menu.style.display === 'flex';

                                // Tüm açık menüleri kapat
                                document.querySelectorAll('.share-menu').forEach(m => {
                                    m.style.display = 'none';
                                });

                                // Bu menüyü aç/kapat
                                menu.style.display = isVisible ? 'none' : 'flex';

                                // Paylaş butonunun tooltip'ini kapat
                                element.classList.toggle('active');

                                // Dışarı tıklandığında menüyü kapat
                                if (!isVisible) {
                                    document.addEventListener('click', function closeMenu(e) {
                                        if (!element.contains(e.target)) {
                                            menu.style.display = 'none';
                                            element.classList.remove('active');
                                            document.removeEventListener('click', closeMenu);
                                        }
                                    });
                                }
                            }

                            function shareOnWhatsApp(url, title) {
                                const text = encodeURIComponent(title + '\n\n' + url);
                                window.open(`https://wa.me/?text=${text}`, '_blank');
                            }

                            function shareOnFacebook(url) {
                                window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`,
                                    '_blank');
                            }

                            function shareOnTwitter(url, title) {
                                const text = encodeURIComponent(title + '\n\n');
                                window.open(
                                    `https://twitter.com/intent/tweet?text=${text}&url=${encodeURIComponent(url)}`,
                                    '_blank');
                            }

                            function shareOnTelegram(url, title) {
                                const text = encodeURIComponent(title + '\n\n' + url);
                                window.open(`https://t.me/share/url?url=${encodeURIComponent(url)}&text=${text}`,
                                    '_blank');
                            }

                            function submitCommentForm(form) {
                                event.preventDefault();

                                const formData = new FormData(form);

                                fetch('<?php echo BASE_URL; ?>/includes/actions/post-comment.php', {
                                        method: 'POST',
                                        body: formData
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            // Başarılı mesajı göster
                                            showToast(data.message);

                                            // Formu temizle
                                            form.reset();

                                            // Yorum listesini yenile
                                            setTimeout(() => {
                                                location.reload();
                                            }, 2000);
                                        } else {
                                            // Hata mesajı göster
                                            showToast(data.message, 'error');
                                        }
                                    })
                                    .catch(error => {
                                        showToast('Bir hata oluştu. Lütfen tekrar deneyin.', 'error');
                                    });

                                return false;
                            }
                            </script>
                        </div>
                    </div>

                    <!-- Ana Yorum Formu Container -->
                    <div id="main-comment-container" class="mt-4" style="display: none;"></div>

                    <!-- Yorumlar -->
                    <div class="comment-widget__wrapper">
                        <h3 class="heading-xs-2 mb-40">Bu yazıya yapılan yorumlar:</h3>
                        <?php if (empty($approvedComments)): ?>
                        <p class="text-muted">Henüz yorum yapılmamış.</p>
                        <?php else: ?>
                        <?php foreach ($approvedComments as $comment): ?>
                        <div class="comment-widget">
                            <div class="comment-widget__inner">
                                <div class="comment-widget__image">
                                    <div
                                        style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                                        <i class="fa-solid fa-user" style="color: white; font-size: 20px;"></i>
                                    </div>
                                </div>
                                <div class="comment-widget__body">
                                    <div class="comment-widget__meta">
                                        <div class="comment-widget__user">
                                            <h3 class="comment-widget__user-name">
                                                <?php echo htmlspecialchars($comment['name']); ?></h3>
                                            <span
                                                class="comment-widget__date"><?php echo $blogService->formatDate($comment['created_at']); ?></span>
                                        </div>
                                        <div class="comment-widget__button">
                                            <a href="#" class="nav-btn"
                                                onclick="showReplyForm(this, '<?php echo $comment['id']; ?>'); return false;">Yanıtla</a>
                                        </div>
                                    </div>
                                    <p><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                                    <div class="reply-form-container" id="reply-form-<?php echo $comment['id']; ?>"
                                        style="display: none;"></div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Ana Yorum Formu Template -->
                    <template id="comment-form-template">
                        <form class="blog-content-comment-box" onsubmit="return submitCommentForm(this)">
                            <p style="color: #666; font-size: 14px; margin-bottom: 20px;">E-posta hesabınız
                                yayımlanmayacak. Gerekli alanlar * ile işaretlenmişlerdir</p>

                            <div class="mb-4">
                                <label class="d-block mb-2">Yorum</label>
                                <textarea name="comment" class="form-control"
                                    style="width: 100%; min-height: 150px; border: 1px solid #ddd; border-radius: 4px; padding: 10px;"
                                    placeholder="Yorum"></textarea>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label class="d-block mb-2">İsim*</label>
                                    <input type="text" name="name" class="form-control"
                                        style="width: 100%; height: 40px; border: 1px solid #ddd; border-radius: 4px; padding: 8px;"
                                        placeholder="İsim*" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="d-block mb-2">E-posta*</label>
                                    <input type="email" name="email" class="form-control"
                                        style="width: 100%; height: 40px; border: 1px solid #ddd; border-radius: 4px; padding: 8px;"
                                        placeholder="E-Posta*" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="d-block mb-2">İnternet sitesi</label>
                                    <input type="url" name="website" class="form-control"
                                        style="width: 100%; height: 40px; border: 1px solid #ddd; border-radius: 4px; padding: 8px;"
                                        placeholder="İnternet sitesi">
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="form-check" style="margin-left: 0;">
                                    <input type="checkbox" name="save_info" id="save_info"
                                        style="margin-right: 8px; width: 16px; height: 16px; vertical-align: middle;">
                                    <label for="save_info" style="color: #666; font-size: 14px;">
                                        Bir dahaki sefere yorum yaptığımda kullanılmak üzere adımı, e-posta adresimi ve
                                        web site adresimi bu tarayıcıya kaydet.
                                    </label>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="form-check" style="margin-left: 0; margin-bottom: 8px;">
                                    <input type="checkbox" name="notify_comment" id="notify_comment"
                                        style="margin-right: 8px; width: 16px; height: 16px; vertical-align: middle;">
                                    <label for="notify_comment" style="color: #666; font-size: 14px;">
                                        Beni sonraki yorumlar için e-posta ile bilgilendir.
                                    </label>
                                </div>
                                <div class="form-check" style="margin-left: 0;">
                                    <input type="checkbox" name="notify_post" id="notify_post"
                                        style="margin-right: 8px; width: 16px; height: 16px; vertical-align: middle;">
                                    <label for="notify_post" style="color: #666; font-size: 14px;">
                                        Beni yeni yazılarda e-posta ile bilgilendir.
                                    </label>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn" onclick="hideReplyForm(this)"
                                    style="background-color: #f5f5f5; color: #666; padding: 8px 24px; border: none; border-radius: 4px; font-size: 14px; cursor: pointer;">
                                    İptal
                                </button>
                                <button type="submit" class="btn"
                                    style="background-color: #0066ff; color: white; padding: 8px 24px; border: none; border-radius: 4px; font-size: 14px; cursor: pointer;">
                                    Gönder
                                </button>
                            </div>

                            <input type="hidden" name="post" value="<?php echo $blogData['id']; ?>">
                            <input type="hidden" name="parent_id" value="">
                        </form>
                    </template>

                    <script>
                    function showMainCommentForm() {
                        // Ana yorum container'ını bul
                        const container = document.getElementById('main-comment-container');

                        // Eğer form zaten açıksa, kapat
                        if (container.style.display === 'block') {
                            container.style.display = 'none';
                            container.innerHTML = '';
                            return;
                        }

                        // Tüm açık yanıt formlarını kapat
                        document.querySelectorAll('.reply-form-container').forEach(replyContainer => {
                            replyContainer.style.display = 'none';
                            replyContainer.innerHTML = '';
                        });

                        // Container'ı göster
                        container.style.display = 'block';

                        // Form template'ini kopyala
                        const template = document.getElementById('comment-form-template');
                        const form = template.content.cloneNode(true);

                        // Parent ID'yi temizle (ana yorum olduğu için)
                        form.querySelector('input[name="parent_id"]').value = '';

                        // İptal butonunun onclick fonksiyonunu değiştir
                        const cancelButton = form.querySelector('button[onclick="hideReplyForm(this)"]');
                        cancelButton.setAttribute('onclick', 'hideMainCommentForm()');

                        // Formu container'a ekle
                        container.appendChild(form);

                        // Forma scroll yap
                        container.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }

                    function hideMainCommentForm() {
                        const container = document.getElementById('main-comment-container');
                        container.style.display = 'none';
                        container.innerHTML = '';
                    }

                    function showReplyForm(button, commentId) {
                        // Ana yorum formunu kapat
                        hideMainCommentForm();

                        // Tüm açık formları kapat
                        document.querySelectorAll('.reply-form-container').forEach(container => {
                            container.style.display = 'none';
                            container.innerHTML = '';
                        });

                        // Hedef form container'ı bul ve göster
                        const container = document.getElementById('reply-form-' + commentId);
                        container.style.display = 'block';

                        // Form template'ini kopyala
                        const template = document.getElementById('comment-form-template');
                        const form = template.content.cloneNode(true);

                        // Parent ID'yi ayarla
                        form.querySelector('input[name="parent_id"]').value = commentId;

                        // Formu container'a ekle
                        container.appendChild(form);

                        // Forma scroll yap
                        container.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }

                    function hideReplyForm(button) {
                        const container = button.closest('.reply-form-container');
                        container.style.display = 'none';
                        container.innerHTML = '';
                    }
                    </script>
                </div>

                <!-- Yan Panel -->
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

                        <!-- Kategoriler -->
                        <div class="sidebar-single">
                            <div class="sidebar-list">
                                <div class="sidebar-title-block">
                                    <h3 class="sidebar-title">Kategoriler</h3>
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

                        <!-- Son Yazılar -->
                        <div class="sidebar-single">
                            <div class="sidebar-blog-suggestion">
                                <div class="sidebar-title-block">
                                    <h3 class="sidebar-title">Son Yazılar</h3>
                                </div>
                                <div class="sidebar-blog-widget-wrapper">
                                    <?php
                                    try {
                                        // Son yazıları getir (mevcut yazıyı hariç tut)
                                        $recentPosts = $blogService->getRecentPosts(3, $blogData['id']);
                                        
                                        if (!empty($recentPosts)) {
                                            foreach ($recentPosts as $post) {
                                                // Görsel yolunu al
                                                $imagePath = $blogService->getImagePath($post['cover_image']);
                                                
                                                // Tarihi formatla
                                                $dateFormatted = $blogService->formatDate($post['created_at']);
                                                
                                                echo '
                                                <div class="sidebar-blog-widget">
                                                    <a href="blog-details?slug=' . htmlspecialchars($post['slug']) . '" class="sidebar-blog-widget__image">
                                                        <img src="' . $imagePath . '" alt="' . htmlspecialchars($post['title']) . '" style="width: 100%; height: 60px; object-fit: cover; border-radius: 8px;">
                                                    </a>
                                                    <div class="sidebar-blog-widget__body">
                                                        <div class="sidebar-blog-widget__date" style="display: flex; align-items: center; margin-bottom: 8px;">
                                                            <i class="fa-regular fa-calendar-days" style="color: #059669; font-size: 14px; margin-right: 6px;"></i>
                                                            <span style="color: #666; font-size: 13px;">' . $dateFormatted . '</span>
                                                        </div>
                                                        <h3 class="sidebar-blog-widget__title">
                                                            <a href="blog-details?slug=' . htmlspecialchars($post['slug']) . '" style="color: #333; text-decoration: none; font-size: 14px; line-height: 1.4;">
                                                                ' . htmlspecialchars($post['title']) . '
                                                            </a>
                                                        </h3>
                                                    </div>
                                                </div>';
                                            }
                                        } else {
                                            echo '<div class="card card-body">Başka yazı bulunmuyor</div>';
                                        }
                                    } catch (Exception $e) {
                                        echo '<div class="card card-body">Son yazılar yüklenirken hata oluştu</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <!-- Etiketler -->
                        <div class="sidebar-single">
                            <div class="sidebar-title-block">
                                <h3 class="sidebar-title">Etiketler</h3>
                            </div>
                            <ul class="sidebar-tag-list">
                                <li class="sidebar-tag-list-item"><a href="#">İman</a></li>
                                <li class="sidebar-tag-list-item"><a href="#">Ahiret</a></li>
                                <li class="sidebar-tag-list-item"><a href="#">Namaz</a></li>
                                <li class="sidebar-tag-list-item"><a href="#">Kur'an</a></li>
                                <li class="sidebar-tag-list-item"><a href="#">Tevekkül</a></li>
                                <li class="sidebar-tag-list-item"><a href="#">Sabır</a></li>
                                <li class="sidebar-tag-list-item"><a href="#">Risale-i Nur</a></li>
                            </ul>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>