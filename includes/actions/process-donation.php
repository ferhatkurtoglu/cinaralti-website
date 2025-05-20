<?php
/**
 * Bağış İşleme Dosyası
 * Bu dosya bağış verilerini işleyip veritabanına kaydeder
 */

// Güvenlik ve giriş nokta kontrolü
if (!defined('BASE_URL')) {
    // Doğrudan erişimi engelle
    header('HTTP/1.0 403 Forbidden');
    exit('Bu dosyaya doğrudan erişim izni yoktur.');
}

/**
 * Bağış işlemini veritabanına kaydeder
 * 
 * @param array $donationData Bağış verileri
 * @return int|bool Başarılı ise kaydedilen ID, hata durumunda false
 */
function save_donation($donationData) {
    try {
        // Veritabanı bağlantısı
        $db = db_connect();
        
        // SQL sorgusu hazırlama
        $sql = "INSERT INTO donations_made (
                    donation_option_id, 
                    donor_name, 
                    donor_email, 
                    donor_phone, 
                    city, 
                    amount, 
                    donation_option, 
                    donor_type, 
                    payment_status
                ) VALUES (
                    :donation_option_id, 
                    :donor_name, 
                    :donor_email, 
                    :donor_phone, 
                    :city, 
                    :amount, 
                    :donation_option, 
                    :donor_type, 
                    :payment_status
                )";
        
        // Sorguyu hazırla
        $stmt = $db->prepare($sql);
        
        // Parametreleri bağla
        $stmt->bindParam(':donation_option_id', $donationData['donation_option_id'], PDO::PARAM_INT);
        $stmt->bindParam(':donor_name', $donationData['donor_name'], PDO::PARAM_STR);
        $stmt->bindParam(':donor_email', $donationData['donor_email'], PDO::PARAM_STR);
        $stmt->bindParam(':donor_phone', $donationData['donor_phone'], PDO::PARAM_STR);
        $stmt->bindParam(':city', $donationData['city'], PDO::PARAM_STR);
        $stmt->bindParam(':amount', $donationData['amount'], PDO::PARAM_STR);
        $stmt->bindParam(':donation_option', $donationData['donation_option'], PDO::PARAM_STR);
        $stmt->bindParam(':donor_type', $donationData['donor_type'], PDO::PARAM_STR);
        $stmt->bindParam(':payment_status', $donationData['payment_status'], PDO::PARAM_STR);
        
        // Sorguyu çalıştır
        $stmt->execute();
        
        // Eklenen kaydın ID'sini döndür
        return $db->lastInsertId();
        
    } catch (Exception $e) {
        // Hata durumunda günlüğe kaydet
        error_log("Bağış kaydetme hatası: " . $e->getMessage());
        if (DEBUG_MODE) {
            error_log("Hata detayları: " . print_r($donationData, true));
        }
        return false;
    }
}

/**
 * Bağış durumunu günceller
 * 
 * @param int $donationId Bağış ID
 * @param string $status Yeni durum
 * @return bool İşlem başarılı ise true, değilse false
 */
function update_donation_status($donationId, $status) {
    try {
        // Veritabanı bağlantısı
        $db = db_connect();
        
        // SQL sorgusu hazırlama
        $sql = "UPDATE donations_made 
                SET payment_status = :status, 
                    updated_at = CURRENT_TIMESTAMP 
                WHERE id = :id";
        
        // Sorguyu hazırla
        $stmt = $db->prepare($sql);
        
        // Parametreleri bağla
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':id', $donationId, PDO::PARAM_INT);
        
        // Sorguyu çalıştır
        return $stmt->execute();
        
    } catch (Exception $e) {
        // Hata durumunda günlüğe kaydet
        error_log("Bağış durumu güncelleme hatası: " . $e->getMessage());
        if (DEBUG_MODE) {
            error_log("Hata detayları: ID=$donationId, Status=$status");
        }
        return false;
    }
}

/**
 * Bağış kaydını ID'ye göre getirir
 * 
 * @param int $donationId Bağış ID
 * @return array|bool Bağış verisi veya bulunamazsa false
 */
function get_donation_by_id($donationId) {
    try {
        // Veritabanı bağlantısı
        $db = db_connect();
        
        // SQL sorgusu hazırlama
        $sql = "SELECT * FROM donations_made WHERE id = :id";
        
        // Sorguyu hazırla
        $stmt = $db->prepare($sql);
        
        // Parametreleri bağla
        $stmt->bindParam(':id', $donationId, PDO::PARAM_INT);
        
        // Sorguyu çalıştır
        $stmt->execute();
        
        // Sonucu döndür
        return $stmt->fetch();
        
    } catch (Exception $e) {
        // Hata durumunda günlüğe kaydet
        error_log("Bağış getirme hatası: " . $e->getMessage());
        return false;
    }
} 