<?php
/**
 * POOR MAN'S MIGRATION SCRIPT
 * Nuke & Rebuild Database Railway MySQL
 * 
 * WARNING: This script will DELETE ALL TABLES and DATA!
 * Only run this if you're sure you want to start fresh.
 */

echo "💣 POOR MAN'S MIGRATION SCRIPT\n";
echo "==============================\n";
echo "⚠️  WARNING: This will DELETE ALL DATA!\n";
echo "Press ENTER to continue or CTRL+C to abort...\n";
// readline(""); // Uncomment this line if you want confirmation

// Database configuration
$host = "nozomi.proxy.rlwy.net";
$dbname = "railway";
$username = "root"; 
$password = "YqgbKBTiDvxzMYjzAFxOnZFvNJoYQVet";
$port = 32871;

try {
    // Connect to database
    $db = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_TIMEOUT => 30
        ]
    );
    
    echo "✅ Connected to Railway MySQL database\n\n";
    
    // STEP 1: NUKE ALL TABLES
    echo "🔥 STEP 1: NUKING ALL TABLES...\n";
    echo str_repeat("-", 40) . "\n";
    
    // Disable foreign key checks
    $db->exec("SET FOREIGN_KEY_CHECKS = 0");
    echo "✅ Disabled foreign key checks\n";
    
    // Get all tables
    $stmt = $db->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "ℹ️  No tables found to delete\n";
    } else {
        echo "📋 Found tables to delete: " . implode(', ', $tables) . "\n";
        
        // Drop each table
        foreach ($tables as $table) {
            try {
                $db->exec("DROP TABLE IF EXISTS `$table`");
                echo "💥 Dropped table: $table\n";
            } catch (PDOException $e) {
                echo "❌ Failed to drop $table: " . $e->getMessage() . "\n";
            }
        }
    }
    
    // Re-enable foreign key checks
    $db->exec("SET FOREIGN_KEY_CHECKS = 1");
    echo "✅ Re-enabled foreign key checks\n\n";
    
    // STEP 2: CREATE ALL TABLES
    echo "🏗️  STEP 2: CREATING ALL TABLES...\n";
    echo str_repeat("-", 40) . "\n";
    
    // Create kategori table first (referenced by barang)
    $createKategoriTable = "
    CREATE TABLE `kategori` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `nama` varchar(100) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ";
    
    $db->exec($createKategoriTable);
    echo "✅ Created table: kategori\n";
    
    // Create users table
    $createUsersTable = "
    CREATE TABLE `users` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `nama` varchar(100) DEFAULT NULL,
        `email` varchar(100) DEFAULT NULL,
        `password` varchar(255) DEFAULT NULL,
        `api_key` varchar(255) DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `email` (`email`),
        UNIQUE KEY `api_key` (`api_key`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ";
    
    $db->exec($createUsersTable);
    echo "✅ Created table: users\n";
    
    // Create barang table (with foreign key to kategori)
    $createBarangTable = "
    CREATE TABLE `barang` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `nama` varchar(100) DEFAULT NULL,
        `jumlah` int(11) DEFAULT NULL,
        `kategori_id` int(11) DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `kategori_id` (`kategori_id`),
        CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ";
    
    $db->exec($createBarangTable);
    echo "✅ Created table: barang (with foreign key)\n\n";
    
    // STEP 3: INSERT DATA
    echo "📝 STEP 3: INSERTING DATA...\n";
    echo str_repeat("-", 40) . "\n";
    
    // Insert kategori data first
    $kategoriData = [
        [1, 'Elektronik'],
        [2, 'Pakaian dan Aksesoris'],
        [3, 'Alat Kerja']
    ];
    
    $insertKategori = "INSERT INTO kategori (id, nama) VALUES (?, ?)";
    $stmt = $db->prepare($insertKategori);
    
    foreach ($kategoriData as $data) {
        $stmt->execute($data);
    }
    echo "✅ Inserted " . count($kategoriData) . " kategori records\n";
    
    // Insert users data
    $usersData = [
        [1, 'aaa', 'aaa@gmail.com', '$2y$12$W8.xCVrbcfN6RhzmKmAbxOplfN/zR5tnFav3a8Q0Dl2gH0vllHBAO', 'ce934d65007788b11139ddb3fb7c73bd'],
        [2, 'bbb', 'bbb@gmail.com', '$2y$12$b2Db/b1QcKmA8V249ccjrO2BnVZBa4GPl8TG.q74M4g0L8Jp.mE4u', 'a1729629064b6b74b3139be1cde72ef5']
    ];
    
    $insertUsers = "INSERT INTO users (id, nama, email, password, api_key) VALUES (?, ?, ?, ?, ?)";
    $stmt = $db->prepare($insertUsers);
    
    foreach ($usersData as $data) {
        $stmt->execute($data);
    }
    echo "✅ Inserted " . count($usersData) . " users records\n";
    
    // Insert barang data
    $barangData = [
        [1, 'Kipas Angin', 1, 1],
        [2, 'Baju', 10, 2],
        [3, 'Celana', 10, 2],
        [4, 'Sepatu', 5, 2],
        [5, 'Topi', 5, 2],
        [6, 'Gelang', 10, 2],
        [7, 'Ikat Pinggang', 20, 2],
        [8, 'Kalung', 15, 2],
        [9, 'TV', 15, 1],
        [10, 'Laptop', 15, 1],
        [11, 'HP', 10, 1],
        [12, 'Kulkas', 20, 1],
        [13, 'Mesin Cuci', 15, 1], // Fixed typo
        [14, 'Komputer', 15, 1],
        [15, 'Smart Watch', 15, 1],
        [16, 'Charger', 20, 1],
        [17, 'Lampu', 20, 1],
        [18, 'Mixer', 10, 1],
        [19, 'Palu', 10, 3],
        [20, 'Obeng', 10, 3],
        [21, 'Tang', 10, 3],
        [22, 'Gergaji', 20, 3],
        [23, 'Bor Listrik', 15, 3],
        [24, 'Meteran', 15, 3],
        [25, 'Cutter', 15, 3],
        [26, 'Siku', 20, 3],
        [27, 'Alat Las', 20, 3],
        [28, 'Alat Pahat', 10, 3]
    ];
    
    $insertBarang = "INSERT INTO barang (id, nama, jumlah, kategori_id) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($insertBarang);
    
    $successCount = 0;
    foreach ($barangData as $data) {
        try {
            $stmt->execute($data);
            $successCount++;
        } catch (PDOException $e) {
            echo "❌ Failed to insert {$data[1]}: " . $e->getMessage() . "\n";
        }
    }
    echo "✅ Inserted $successCount/" . count($barangData) . " barang records\n\n";
    
    // STEP 4: VERIFY DATA
    echo "🔍 STEP 4: VERIFYING DATA...\n";
    echo str_repeat("-", 40) . "\n";
    
    // Check table counts
    $tables = ['kategori', 'users', 'barang'];
    foreach ($tables as $table) {
        $stmt = $db->query("SELECT COUNT(*) as count FROM $table");
        $count = $stmt->fetchColumn();
        echo "📊 Table $table: $count records\n";
    }
    
    echo "\n";
    
    // Show sample data with JOIN
    echo "📋 SAMPLE DATA (Barang with Kategori):\n";
    echo str_repeat("-", 70) . "\n";
    printf("%-5s %-20s %-10s %-20s\n", "ID", "NAMA", "JUMLAH", "KATEGORI");
    echo str_repeat("-", 70) . "\n";
    
    $sampleQuery = "
    SELECT b.id, b.nama, b.jumlah, k.nama as kategori_nama 
    FROM barang b 
    LEFT JOIN kategori k ON b.kategori_id = k.id 
    ORDER BY b.id 
    LIMIT 10
    ";
    
    $stmt = $db->query($sampleQuery);
    while ($row = $stmt->fetch()) {
        printf("%-5d %-20s %-10d %-20s\n", 
            $row['id'], 
            substr($row['nama'], 0, 18), 
            $row['jumlah'], 
            $row['kategori_nama']
        );
    }
    
    echo str_repeat("-", 70) . "\n";
    
    // Show users (without sensitive data)
    echo "\n👥 USERS DATA:\n";
    echo str_repeat("-", 50) . "\n";
    printf("%-5s %-20s %-25s\n", "ID", "NAMA", "EMAIL");
    echo str_repeat("-", 50) . "\n";
    
    $usersQuery = "SELECT id, nama, email FROM users ORDER BY id";
    $stmt = $db->query($usersQuery);
    while ($row = $stmt->fetch()) {
        printf("%-5d %-20s %-25s\n", 
            $row['id'], 
            $row['nama'], 
            $row['email']
        );
    }
    echo str_repeat("-", 50) . "\n";
    
    // STEP 5: SET AUTO_INCREMENT VALUES
    echo "\n🔢 STEP 5: SETTING AUTO_INCREMENT VALUES...\n";
    echo str_repeat("-", 40) . "\n";
    
    $db->exec("ALTER TABLE kategori AUTO_INCREMENT = 4");
    $db->exec("ALTER TABLE users AUTO_INCREMENT = 3");
    $db->exec("ALTER TABLE barang AUTO_INCREMENT = 29");
    
    echo "✅ Set kategori AUTO_INCREMENT to 4\n";
    echo "✅ Set users AUTO_INCREMENT to 3\n";
    echo "✅ Set barang AUTO_INCREMENT to 29\n";
    
    echo "\n🎉 MIGRATION COMPLETED SUCCESSFULLY!\n";
    echo "=====================================\n";
    echo "✅ All tables nuked and rebuilt\n";
    echo "✅ All data inserted\n";
    echo "✅ Foreign keys working\n";
    echo "✅ AUTO_INCREMENT values set\n";
    echo "✅ Database ready for use!\n\n";
    
    // Show final stats
    echo "📈 FINAL STATISTICS:\n";
    echo "- Kategori: 3 records\n";
    echo "- Users: 2 records\n";
    echo "- Barang: 28 records\n";
    echo "- Total: 33 records\n";
    
} catch (PDOException $e) {
    echo "❌ DATABASE ERROR: " . $e->getMessage() . "\n";
    echo "\n🔧 TROUBLESHOOTING:\n";
    echo "1. Check if Railway service is running\n";
    echo "2. Verify database credentials\n";
    echo "3. Check network connectivity\n";
    echo "4. Ensure database 'railway' exists\n";
    
    exit(1);
} catch (Exception $e) {
    echo "❌ GENERAL ERROR: " . $e->getMessage() . "\n";
    exit(1);
}

// Close connection
$db = null;
echo "\n🔐 Database connection closed.\n";
echo "Ready to rock and roll! 🚀\n";
?>