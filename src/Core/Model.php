<?php
declare(strict_types=1);

namespace App\Core;

use App\Core\Application;
use App\Exceptions\NotFoundException;
use PDO;
use PDOException;

/**
 * Base Model Class
 */
abstract class Model
{
    /**
     * Table name
     */
    protected string $table;
    
    /**
     * Primary key
     */
    protected string $primaryKey = 'id';
    
    /**
     * The model's attributes
     */
    protected array $attributes = [];
    
    /**
     * The model's fillable attributes
     */
    protected array $fillable = [];
    
    /**
     * Get database instance
     */
    protected function db(): PDO
    {
        return Application::$app->database->pdo;
    }
    
    /**
     * Find a record by ID
     */
    public function find(int|string|null $id): ?static
    {
        if ($id === null) {
            throw new NotFoundException("Invalid ID: Parameter is null");
        }
        
        try {
            $statement = $this->db()->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1");
            $statement->execute(['id' => $id]);
            
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            
            if (!$result) {
                throw new NotFoundException("Record with ID {$id} not found in {$this->table}");
            }
            
            return $this->mapIntoObject($result);
        } catch (PDOException $e) {
            // Check if this is a "table doesn't exist" error
            if (strpos($e->getMessage(), "Table 'mvc_crud.{$this->table}' doesn't exist") !== false) {
                throw new \PDOException(
                    "The database tables have not been created yet. " .
                    "Please run the database migration script with: " .
                    "'docker-compose exec app php database/migrate.php'", 
                    (int)$e->getCode(), $e
                );
            }
            
            throw $e;
        }
    }
    
    /**
     * Find all records
     */
    public function findAll(array $conditions = [], array $orderBy = []): array
    {
        try {
            $sql = "SELECT * FROM {$this->table}";
            $params = [];
            
            // Add WHERE conditions if any
            if (!empty($conditions)) {
                $whereClause = [];
                foreach ($conditions as $column => $value) {
                    $whereClause[] = "{$column} = :{$column}";
                    $params[$column] = $value;
                }
                $sql .= " WHERE " . implode(' AND ', $whereClause);
            }
            
            // Add ORDER BY if any
            if (!empty($orderBy)) {
                $orderClauses = [];
                foreach ($orderBy as $column => $direction) {
                    $orderClauses[] = "{$column} {$direction}";
                }
                $sql .= " ORDER BY " . implode(', ', $orderClauses);
            }
            
            $statement = $this->db()->prepare($sql);
            $statement->execute($params);
            
            return array_map(
                fn($result) => $this->mapIntoObject($result),
                $statement->fetchAll(PDO::FETCH_ASSOC)
            );
        } catch (PDOException $e) {
            // Check if this is a "table doesn't exist" error
            if (strpos($e->getMessage(), "Table 'mvc_crud.{$this->table}' doesn't exist") !== false) {
                throw new \PDOException(
                    "The database tables have not been created yet. " .
                    "Please run the database migration script with: " .
                    "'docker-compose exec app php database/migrate.php'", 
                    (int)$e->getCode(), $e
                );
            }
            
            throw $e;
        }
    }
    
    /**
     * Save a record (create or update)
     */
    public function save(): bool
    {
        $attributes = $this->getAttributes();
        
        // If primary key exists, update
        if (isset($attributes[$this->primaryKey])) {
            return $this->update($attributes);
        }
        
        // Otherwise, create
        return $this->create($attributes);
    }
    
    /**
     * Create a new record
     */
    protected function create(array $attributes): bool
    {
        try {
            // Filter only fillable attributes
            $fillableAttributes = array_intersect_key($attributes, array_flip($this->fillable));
            
            if (empty($fillableAttributes)) {
                return false;
            }
            
            $columns = implode(', ', array_keys($fillableAttributes));
            $placeholders = implode(', ', array_map(fn($col) => ":{$col}", array_keys($fillableAttributes)));
            
            $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
            $statement = $this->db()->prepare($sql);
            
            $result = $statement->execute($fillableAttributes);
            
            if ($result) {
                // Set ID of created record
                $this->attributes[$this->primaryKey] = $this->db()->lastInsertId();
            }
            
            return $result;
        } catch (PDOException $e) {
            // Check if this is a "table doesn't exist" error
            if (strpos($e->getMessage(), "Table 'mvc_crud.{$this->table}' doesn't exist") !== false) {
                throw new \PDOException(
                    "The database tables have not been created yet. " .
                    "Please run the database migration script with: " .
                    "'docker-compose exec app php database/migrate.php'", 
                    (int)$e->getCode(), $e
                );
            }
            
            throw $e;
        }
    }
    
    /**
     * Update a record
     */
    protected function update(array $attributes): bool
    {
        try {
            // Filter only fillable attributes
            $fillableAttributes = array_intersect_key($attributes, array_flip($this->fillable));
            
            if (empty($fillableAttributes)) {
                return false;
            }
            
            $id = $attributes[$this->primaryKey];
            
            $setParts = array_map(
                fn($col) => "{$col} = :{$col}",
                array_keys($fillableAttributes)
            );
            
            $sql = "UPDATE {$this->table} SET " . implode(', ', $setParts) . " WHERE {$this->primaryKey} = :id";
            
            // Add id to parameters
            $fillableAttributes['id'] = $id;
            
            $statement = $this->db()->prepare($sql);
            return $statement->execute($fillableAttributes);
        } catch (PDOException $e) {
            // Check if this is a "table doesn't exist" error
            if (strpos($e->getMessage(), "Table 'mvc_crud.{$this->table}' doesn't exist") !== false) {
                throw new \PDOException(
                    "The database tables have not been created yet. " .
                    "Please run the database migration script with: " .
                    "'docker-compose exec app php database/migrate.php'", 
                    (int)$e->getCode(), $e
                );
            }
            
            throw $e;
        }
    }
    
    /**
     * Delete a record
     */
    public function delete(): bool
    {
        try {
            $attributes = $this->getAttributes();
            
            if (!isset($attributes[$this->primaryKey])) {
                return false;
            }
            
            $id = $attributes[$this->primaryKey];
            
            $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
            $statement = $this->db()->prepare($sql);
            
            return $statement->execute(['id' => $id]);
        } catch (PDOException $e) {
            // Check if this is a "table doesn't exist" error
            if (strpos($e->getMessage(), "Table 'mvc_crud.{$this->table}' doesn't exist") !== false) {
                throw new \PDOException(
                    "The database tables have not been created yet. " .
                    "Please run the database migration script with: " .
                    "'docker-compose exec app php database/migrate.php'", 
                    (int)$e->getCode(), $e
                );
            }
            
            throw $e;
        }
    }
    
    /**
     * Map database result into model object
     */
    protected function mapIntoObject(array $result): static
    {
        $model = clone $this;
        $model->attributes = $result;
        
        return $model;
    }
    
    /**
     * Set attribute
     */
    public function setAttribute(string $key, $value): void
    {
        $this->attributes[$key] = $value;
    }
    
    /**
     * Get attribute
     */
    public function getAttribute(string $key, $default = null)
    {
        return $this->attributes[$key] ?? $default;
    }
    
    /**
     * Get all attributes
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
    
    /**
     * Magic method to set attributes
     */
    public function __set(string $name, $value): void
    {
        $this->setAttribute($name, $value);
    }
    
    /**
     * Magic method to get attributes
     */
    public function __get(string $name)
    {
        return $this->getAttribute($name);
    }
    
    /**
     * Magic method to check if attribute exists
     */
    public function __isset(string $name): bool
    {
        return isset($this->attributes[$name]);
    }
}