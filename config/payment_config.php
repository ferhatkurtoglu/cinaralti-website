<?php
/**
 * Ödeme Sistemi Yapılandırması
 * Bu dosya ödeme sistemi ayarlarını içerir
 */

// Ödeme sistemi ayarları
define('PAYMENT_MERCHANT_ID', 'your_merchant_id_here');
define('PAYMENT_API_KEY', 'your_api_key_here');
define('PAYMENT_DEBUG', false);

// Kuveyt Türk API ayarları
define('PAYMENT_API_URL', 'https://sanalpos.kuveytturk.com.tr/v1/vpos/nonThreeDPayment');
define('PAYMENT_CURRENCY_CODE', '0949'); // TRY
define('PAYMENT_TRANSACTION_TYPE', '1'); // Satış

// Güvenlik ayarları
define('PAYMENT_FORCE_HTTPS', true);
define('PAYMENT_CSRF_PROTECTION', true);

// Hata mesajları
define('PAYMENT_ERROR_MESSAGES', [
    'security' => 'Güvenlik doğrulaması başarısız oldu. Lütfen tekrar deneyiniz.',
    'database' => 'Bağış bilgileriniz kaydedilirken bir sorun oluştu. Lütfen tekrar deneyiniz.',
    'configuration' => 'Ödeme sistemi yapılandırması tamamlanmamış. Lütfen site yöneticisiyle iletişime geçin.',
    'payment_gateway' => 'Ödeme sistemiyle iletişim sırasında bir sorun oluştu. Lütfen tekrar deneyiniz.',
    'invalid_amount' => 'Geçersiz bağış tutarı. Lütfen tutarı kontrol ediniz.',
    'invalid_card' => 'Geçersiz kart bilgileri. Lütfen kart bilgilerinizi kontrol ediniz.',
    'network_error' => 'Ağ bağlantısı sorunu. Lütfen internet bağlantınızı kontrol edip tekrar deneyiniz.'
]); 