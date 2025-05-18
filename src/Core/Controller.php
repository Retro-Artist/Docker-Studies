<?php
declare(strict_types=1);

namespace App\Core;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;

/**
 * Base Controller Class
 */
abstract class Controller
{
    /**
     * Middleware to be applied
     */
    protected array $middleware = [];
    
    /**
     * Add middleware to controller
     */
    protected function registerMiddleware(string $middlewareClass): void
    {
        $this->middleware[] = new $middlewareClass();
    }
    
    /**
     * Get all registered middleware
     */
    public function getMiddleware(): array
    {
        return $this->middleware;
    }
    
    /**
     * Render a view
     */
    protected function render(string $view, array $data = []): string
    {
        return View::render($view, $data);
    }
    
    /**
     * Render a view with layout
     */
    protected function renderWithLayout(string $view, string $layout = 'main', array $data = []): string
    {
        return View::renderWithLayout($view, $layout, $data);
    }
    
    /**
     * Return JSON response
     */
    protected function json(array $data, int $statusCode = 200): string
    {
        $response = new Response();
        return $response->json($data, $statusCode);
    }
    
    /**
     * Redirect to URL
     */
    protected function redirect(string $url): void
    {
        $response = new Response();
        $response->redirect($url);
    }
}