<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Response Handler Class
 */
class Response
{
    /**
     * Set response status code
     */
    public function setStatusCode(int $code): void
    {
        http_response_code($code);
    }
    
    /**
     * Redirect to a specific URL
     */
    public function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
    
    /**
     * Send JSON response
     */
    public function json(array $data, int $statusCode = 200): string
    {
        $this->setStatusCode($statusCode);
        header('Content-Type: application/json');
        return json_encode($data);
    }
    
    /**
     * Send a file download
     */
    public function download(string $filePath, string $fileName = null): void
    {
        if (!file_exists($filePath)) {
            $this->setStatusCode(404);
            echo 'File not found';
            exit;
        }
        
        $fileName = $fileName ?? basename($filePath);
        
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }
}