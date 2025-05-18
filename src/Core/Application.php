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
    public Database $database;
    public string $rootPath;
    
    public function __construct()
    {
        self::$app = $this;
        $this->rootPath = ROOT_DIR;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->database = new Database();
        
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
            echo $this->router->resolve();
        } catch (\Exception $e) {
            // Handle exceptions
            $this->response->setStatusCode(500);
            echo $this->renderError($e);
        }
    }
    
    /**
     * Render error messages
     */
    private function renderError(\Exception $e): string
    {
        if (Env::get('APP_ENV') === 'development') {
            return sprintf(
                '<h1>Error</h1><p>%s</p><p>%s</p>',
                $e->getMessage(),
                $e->getTraceAsString()
            );
        }
        
        return '<h1>An error occurred</h1><p>Sorry, an unexpected error occurred. Please try again later.</p>';
    }
}