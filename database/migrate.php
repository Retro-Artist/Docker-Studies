<?php
declare(strict_types=1);

// Include bootstrap file to get database connection
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/bootstrap.php';

// Get database instance
$db = new \App\Core\Database();
$pdo = $db->pdo;

try {
    // Start transaction
    $pdo->beginTransaction();
    
    // Drop existing tables if they exist
    $pdo->exec("DROP TABLE IF EXISTS users");
    
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
    
    // Insert sample data
    $pdo->exec("
        INSERT INTO users (name, email, password) VALUES
        ('Admin User', 'admin@example.com', '" . password_hash('password', PASSWORD_DEFAULT) . "'),
        ('John Doe', 'john@example.com', '" . password_hash('password', PASSWORD_DEFAULT) . "'),
        ('Jane Smith', 'jane@example.com', '" . password_hash('password', PASSWORD_DEFAULT) . "')
    ");
    
    // Commit changes
    $pdo->commit();
    
    echo "Database migration completed successfully!\n";
    
} catch (\PDOException $e) {
    // Rollback changes
    $pdo->rollBack();
    
    echo "Database migration failed: " . $e->getMessage() . "\n";
}