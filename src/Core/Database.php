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
            throw new PDOException($e->getMessage(), (int)$e->getCode());
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