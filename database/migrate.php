<?php
declare(strict_types=1);

// Define the application root directory
define('ROOT_DIR', dirname(__DIR__));

// Include bootstrap file to get database connection
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/bootstrap.php';

// Get database instance
try {
    $db = new \App\Core\Database();
    $pdo = $db->pdo;
    
    echo "Database connection established successfully!\n";
    
    // Execute each statement individually without transactions
    
    // Drop existing tables if they exist
    $pdo->exec("DROP TABLE IF EXISTS users");
    echo "- Dropped existing tables if they existed\n";
    
    // Create users table
    $pdo->exec("
        CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "- Created users table\n";
    
    // Insert sample data
    $stmt = $pdo->prepare("
        INSERT INTO users (name, email, password) VALUES
        (?, ?, ?),
        (?, ?, ?),
        (?, ?, ?)
    ");
    
    $stmt->execute([
        'Admin User', 'admin@example.com', password_hash('password', PASSWORD_DEFAULT),
        'John Doe', 'john@example.com', password_hash('password', PASSWORD_DEFAULT),
        'Jane Smith', 'jane@example.com', password_hash('password', PASSWORD_DEFAULT)
    ]);
    
    echo "- Inserted sample data\n";
    echo "Database migration completed successfully!\n";
    
} catch (\PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
    echo "Migration failed.\n";
    exit(1);
} catch (\Exception $e) {
    echo "General error: " . $e->getMessage() . "\n";
    exit(1);
}

