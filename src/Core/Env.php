<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Environment Variable Handler
 * 
 * A simple class to load environment variables from .env file
 */
class Env
{
    /**
     * Loaded environment variables
     */
    private static array $variables = [];
    
    /**
     * Flag to track if env file has been loaded
     */
    private static bool $loaded = false;
    
    /**
     * Load environment variables from .env file
     */
    public static function load(string $path): void
    {
        if (self::$loaded) {
            return;
        }
        
        $filePath = rtrim($path, '/') . '/.env';
        
        if (!file_exists($filePath)) {
            throw new \RuntimeException(".env file not found at: {$filePath}");
        }
        
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            // Skip comments
            if (str_starts_with(trim($line), '#')) {
                continue;
            }
            
            // Parse line
            if (str_contains($line, '=')) {
                [$name, $value] = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);
                
                // Remove quotes if present
                if (str_starts_with($value, '"') && str_ends_with($value, '"')) {
                    $value = substr($value, 1, -1);
                } elseif (str_starts_with($value, "'") && str_ends_with($value, "'")) {
                    $value = substr($value, 1, -1);
                }
                
                self::$variables[$name] = $value;
                
                // Set as environment variable
                if (!array_key_exists($name, $_ENV)) {
                    $_ENV[$name] = $value;
                }
                
                if (!array_key_exists($name, $_SERVER)) {
                    $_SERVER[$name] = $value;
                }
            }
        }
        
        self::$loaded = true;
    }
    
    /**
     * Get an environment variable
     */
    public static function get(string $key, $default = null)
    {
        return self::$variables[$key] ?? $_ENV[$key] ?? $_SERVER[$key] ?? $default;
    }
    
    /**
     * Check if an environment variable exists
     */
    public static function has(string $key): bool
    {
        return isset(self::$variables[$key]) || isset($_ENV[$key]) || isset($_SERVER[$key]);
    }
}