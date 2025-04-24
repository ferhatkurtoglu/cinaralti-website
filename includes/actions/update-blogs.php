<?php
require_once __DIR__ . '/../../config/config.php';

// Blogs.json dosyasını oku
$blogsFile = dirname(__DIR__) . '/../public/data/blogs.json';
$blogsData = ['blogs' => []];

if (file_exists($blogsFile)) {
    $blogsJson = file_get_contents($blogsFile);
    $blogsData = json_decode($blogsJson, true) ?: ['blogs' => []];
}

// Content/blog klasöründeki .md dosyalarını oku
$blogDir = dirname(__DIR__) . '/../content/blog/';
$existingBlogs = [];

if (is_dir($blogDir)) {
    if ($dh = opendir($blogDir)) {
        while (($file = readdir($dh)) !== false) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'md') {
                $filePath = $blogDir . $file;
                $content = file_get_contents($filePath);
                
                // Front matter'ı oku
                preg_match('/---\s*(.*?)\s*---\s*(.*)/s', $content, $matches);
                
                if (count($matches) >= 3) {
                    $frontMatter = $matches[1];
                    
                    // Meta verileri al
                    preg_match('/title:\s*"(.+?)"/s', $frontMatter, $titleMatch);
                    preg_match('/date:\s*"(.+?)"/s', $frontMatter, $dateMatch);
                    preg_match('/author:\s*"(.+?)"/s', $frontMatter, $authorMatch);
                    preg_match('/category:\s*"(.+?)"/s', $frontMatter, $categoryMatch);
                    preg_match('/image:\s*"(.+?)"/s', $frontMatter, $imageMatch);
                    
                    $blogId = pathinfo($file, PATHINFO_FILENAME);
                    $existingBlogs[$blogId] = [
                        'id' => $blogId,
                        'path' => 'content/blog/' . $file,
                        'title' => isset($titleMatch[1]) ? $titleMatch[1] : '',
                        'date' => isset($dateMatch[1]) ? $dateMatch[1] : '',
                        'author' => isset($authorMatch[1]) ? $authorMatch[1] : '',
                        'category' => isset($categoryMatch[1]) ? $categoryMatch[1] : '',
                        'image' => isset($imageMatch[1]) ? $imageMatch[1] : 'blog-image-1.png',
                        'comments' => []
                    ];
                }
            }
        }
        closedir($dh);
    }
}

// Mevcut yorumları koru
foreach ($blogsData['blogs'] as $blog) {
    if (isset($existingBlogs[$blog['id']])) {
        $existingBlogs[$blog['id']]['comments'] = $blog['comments'];
    }
}

// Blogs.json dosyasını güncelle
$blogsData['blogs'] = array_values($existingBlogs);

if (!is_dir(dirname($blogsFile))) {
    mkdir(dirname($blogsFile), 0777, true);
}

file_put_contents($blogsFile, json_encode($blogsData, JSON_PRETTY_PRINT));

echo "Blogs.json dosyası güncellendi.\n"; 