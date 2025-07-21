<?php

// CLI ortamında çalışacak şekilde basitleştirilmiş migration runner
define('DB_HOST', 'localhost');
define('DB_NAME', 'cinaralti_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_SOCKET', '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock');

class MigrationRunner {
    private $pdo;
    
    public function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4;unix_socket=" . DB_SOCKET;
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            echo "Database bağlantı hatası: " . $e->getMessage() . "\n";
            exit(1);
        }
    }
    
    public function runMigration($migrationFile) {
        try {
            $sql = file_get_contents($migrationFile);
            
            if ($sql === false) {
                throw new Exception("Migration dosyası okunamadı: $migrationFile");
            }
            
            // SQL komutlarını ayır ve çalıştır
            $statements = explode(';', $sql);
            
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (empty($statement)) continue;
                
                // DELIMITER komutlarını atla
                if (strpos($statement, 'DELIMITER') !== false) continue;
                
                $this->pdo->exec($statement);
            }
            
            echo "✓ Migration başarıyla çalıştırıldı: " . basename($migrationFile) . "\n";
            return true;
            
        } catch (Exception $e) {
            echo "✗ Migration hatası: " . basename($migrationFile) . " - " . $e->getMessage() . "\n";
            return false;
        }
    }
    
    public function runSeed($seedFile) {
        try {
            $sql = file_get_contents($seedFile);
            
            if ($sql === false) {
                throw new Exception("Seed dosyası okunamadı: $seedFile");
            }
            
            // SQL komutlarını ayır ve çalıştır
            $statements = explode(';', $sql);
            
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (empty($statement)) continue;
                
                $this->pdo->exec($statement);
            }
            
            echo "✓ Seed başarıyla çalıştırıldı: " . basename($seedFile) . "\n";
            return true;
            
        } catch (Exception $e) {
            echo "✗ Seed hatası: " . basename($seedFile) . " - " . $e->getMessage() . "\n";
            return false;
        }
    }
}

// Migration runner'ı başlat
$runner = new MigrationRunner();

echo "=== Blog Sistemi Migration ve Seed İşlemi ===\n\n";

// Migration dosyasını çalıştır
$migrationFile = __DIR__ . '/migrations/2024_01_04_create_blog_system.sql';
if (file_exists($migrationFile)) {
    echo "Migration çalıştırılıyor...\n";
    $runner->runMigration($migrationFile);
} else {
    echo "Migration dosyası bulunamadı: $migrationFile\n";
}

// Seed dosyasını çalıştır
$seedFile = __DIR__ . '/seeds/02_blog_data.sql';
if (file_exists($seedFile)) {
    echo "\nSeed çalıştırılıyor...\n";
    $runner->runSeed($seedFile);
} else {
    echo "Seed dosyası bulunamadı: $seedFile\n";
}

echo "\n=== İşlem Tamamlandı ===\n"; 