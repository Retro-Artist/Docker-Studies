<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use App\Core\Env;

/**
 * Database connection handler
 */
class Database
{
    public PDO $pdo;
    
    /**
     * Initialize database connection
     */
    public function __construct()
    {
        $host = Env::get('DB_HOST', 'localhost');
        $port = Env::get('DB_PORT', '3306');
        $database = Env::get('DB_DATABASE', 'mvc_crud');
        $username = Env::get('DB_USERNAME', 'root');
        $password = Env::get('DB_PASSWORD', '');
        
        $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";
        
        try {
            $this->pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            // Check if this is a "Connection refused" error
            if (strpos($e->getMessage(), 'Connection refused') !== false) {
                throw new \PDOException(
                    "Database connection failed: MySQL server is not running or not accessible. " .
                    "Please ensure MySQL container is up by running 'docker-compose up -d'.", 
                    (int)$e->getCode(), $e
                );
            }
            // Check if this is an "Unknown database" error
            else if (strpos($e->getMessage(), 'Unknown database') !== false) {
                throw new \PDOException(
                    "Database '{$database}' does not exist. " .
                    "Please run the database migration script with: " .
                    "'docker-compose exec app php database/migrate.php'", 
                    (int)$e->getCode(), $e
                );
            }
            
            // For other PDO errors, throw the original exception
            throw $e;
        }
    }
    
    /**
     * Prepare SQL statement
     */
    public function prepare(string $sql): \PDOStatement
    {
        return $this->pdo->prepare($sql);
    }
    
    /**
     * Begin a transaction
     */
    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }
    
    /**
     * Commit a transaction
     */
    public function commit(): bool
    {
        return $this->pdo->commit();
    }
    
    /**
     * Rollback a transaction
     */
    public function rollBack(): bool
    {
        return $this->pdo->rollBack();
    }
    
    /**
     * Get the last inserted ID
     */
    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }
}