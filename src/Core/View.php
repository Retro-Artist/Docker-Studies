<?php
declare(strict_types=1);

namespace App\Core;

/**
 * View Handler Class
 */
class View
{
    /**
     * Render a view template
     */
    public static function render(string $view, array $data = []): string
    {
        $viewPath = ROOT_DIR . '/src/Views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View {$view} not found");
        }
        
        // Extract data for easy use in view
        extract($data);
        
        // Start output buffering
        ob_start();
        
        // Include the view file
        include $viewPath;
        
        // Return the contents of the output buffer
        return ob_get_clean();
    }
    
    /**
     * Render a layout with a view
     */
    public static function renderWithLayout(string $view, string $layout = 'main', array $data = []): string
    {
        $content = self::render($view, $data);
        $data['content'] = $content;
        
        return self::render("layouts/{$layout}", $data);
    }
}