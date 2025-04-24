<?php
// Footer legal links - Bu dosyayı diğer sayfalarda dahil etmek için

$legal_links = [
  [
    'title' => 'Gizlilik Sözleşmesi',
    'url' => 'privacy-policy.php',
    'icon' => 'fas fa-user-shield'
  ],
  [
    'title' => 'Mesafeli Satış Sözleşmesi',
    'url' => 'distance-selling.php',
    'icon' => 'fas fa-file-contract'
  ],
  [
    'title' => 'İade Sözleşmesi',
    'url' => 'return-policy.php',
    'icon' => 'fas fa-undo'
  ],
  [
    'title' => 'SSL Sertifikası',
    'url' => 'ssl-info.php',
    'icon' => 'fas fa-lock'
  ],
  [
    'title' => 'İletişim',
    'url' => 'contact.php',
    'icon' => 'fas fa-envelope'
  ]
];

// Footer links HTML oluşturma fonksiyonu
function generate_footer_legal_links() {
  global $legal_links;
  
  echo '<div class="footer-legal-links">';
  echo '<h5 class="footer-widget__title">Yasal</h5>';
  echo '<ul class="list-unstyled footer-widget__links">';
  
  foreach ($legal_links as $link) {
    echo '<li>';
    echo '<a href="' . $link['url'] . '">';
    echo '<i class="' . $link['icon'] . ' me-2"></i>' . $link['title'];
    echo '</a>';
    echo '</li>';
  }
  
  echo '</ul>';
  echo '</div>';
}
?>