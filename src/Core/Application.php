<?php
declare(strict_types=1);

namespace App\Core;

use App\Core\Router;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Core\Env;

/**
 * Main Application Class
 */
class Application
{
    public static Application $app;
    public Router $router;
    public Request $request;
    public Response $response;
    public ?Database $database = null;
    public string $rootPath;
    
    public function __construct()
    {
        self::$app = $this;
        $this->rootPath = ROOT_DIR;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        
        try {
            $this->database = new Database();
        } catch (\PDOException $e) {
            // We'll handle database connection errors in the run method
            $this->database = null;
        }
        
        // Initialize routes
        $this->initializeRoutes();
    }
    
    /**
     * Initialize application routes
     */
    private function initializeRoutes(): void
    {
        // Include routes configuration
        require_once $this->rootPath . '/config/routes.php';
    }
    
    /**
     * Run the application
     */
    public function run(): void
    {
        try {
            // Check if database is connected
            if ($this->database === null) {
                echo $this->renderDatabaseConnecting();
                exit;
            }
            
            echo $this->router->resolve();
        } catch (\PDOException $e) {
            // Handle database exceptions
            $this->response->setStatusCode(500);
            echo $this->renderDatabaseError($e);
        } catch (\Exception $e) {
            // Handle other exceptions
            $this->response->setStatusCode(500);
            echo $this->renderError($e);
        }
    }
    
    /**
     * Render a loading screen when database is connecting
     */
    private function renderDatabaseConnecting(): string
    {
        return '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Starting Application...</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                body { height: 100vh; display: flex; align-items: center; justify-content: center; }
                .spinner-container { text-align: center; }
                .spinner-border { width: 3rem; height: 3rem; }
            </style>
            <meta http-equiv="refresh" content="2">
        </head>
        <body>
            <div class="container">
                <div class="spinner-container">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <h3>Starting Application...</h3>
                    <p class="text-muted">Please wait while the database connection is established...</p>
                    <div class="mt-3">
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                 role="progressbar" style="width: 100%"></div>
                        </div>
                    </div>
                    <p class="mt-3 small text-muted">This page will refresh automatically.</p>
                </div>
            </div>
        </body>
        </html>';
    }
    
    /**
     * Render database error messages with setup instructions
     */
    private function renderDatabaseError(\PDOException $e): string
    {
        // Check if it's a user-friendly message about migration
        if (strpos($e->getMessage(), 'run the database migration') !== false) {
            return sprintf(
                '<div style="max-width: 800px; margin: 50px auto; padding: 20px; border: 1px solid #dc3545; border-radius: 5px; font-family: sans-serif;">
                    <h1 style="color: #dc3545;">Database Setup Required</h1>
                    <p style="font-size: 16px; line-height: 1.5;">%s</p>
                    <div style="margin-top: 20px; padding: 15px; background-color: #f8f9fa; border-left: 4px solid #6c757d; font-family: monospace;">
                        <p><strong>To set up the database, run:</strong></p>
                        <code>docker-compose exec app php database/migrate.php</code>
                    </div>
                </div>',
                $e->getMessage()
            );
        }
        
        // For other database errors, use the general error renderer
        return $this->renderError($e);
    }
    
    /**
     * Render general error messages
     */
    private function renderError(\Exception $e): string
    {
        if (Env::get('APP_ENV') === 'development') {
            return sprintf(
                '<div style="max-width: 800px; margin: 50px auto; padding: 20px; border: 1px solid #dc3545; border-radius: 5px; font-family: sans-serif;">
                    <h1 style="color: #dc3545;">Error</h1>
                    <p style="font-size: 16px; line-height: 1.5;">%s</p>
                    <div style="margin-top: 20px; padding: 15px; background-color: #f8f9fa; border-left: 4px solid #6c757d; font-family: monospace; overflow-x: auto;">
                        <pre>%s</pre>
                    </div>
                </div>',
                $e->getMessage(),
                $e->getTraceAsString()
            );
        }
        
        return '<h1>An error occurred</h1><p>Sorry, an unexpected error occurred. Please try again later.</p>';
    }
}