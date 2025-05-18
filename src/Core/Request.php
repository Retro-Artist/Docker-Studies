<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Request Handler Class
 */
class Request
{
    /**
     * Get the current request path
     */
    public function getPath(): string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        
        if ($position === false) {
            return $path;
        }
        
        return substr($path, 0, $position);
    }
    
    /**
     * Get the current request method
     */
    public function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
    
    /**
     * Get all request data
     */
    public function getBody(): array
    {
        $body = [];
        
        if ($this->getMethod() === 'get') {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        
        if ($this->getMethod() === 'post') {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        
        return $body;
    }
    
    /**
     * Check if request is AJAX
     */
    public function isAjax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Get a specific input value
     */
    public function input(string $key, $default = null)
    {
        $body = $this->getBody();
        return $body[$key] ?? $default;
    }
    
    /**
     * Get JSON request body
     */
    public function getJson(): ?array
    {
        if (strpos($_SERVER["CONTENT_TYPE"] ?? '', 'application/json') !== false) {
            $content = file_get_contents('php://input');
            return json_decode($content, true);
        }
        
        return null;
    }
}