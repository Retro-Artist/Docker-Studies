<?php
declare(strict_types=1);

namespace App\Core;

use App\Core\Request;
use App\Core\Response;
use App\Exceptions\NotFoundException;

/**
 * Router Class
 */
class Router
{
    protected array $routes = [];
    public Request $request;
    public Response $response;
    
    /**
     * Constructor
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
    
    /**
     * Add a GET route
     */
    public function get(string $path, $callback): self
    {
        $this->routes['get'][$path] = $callback;
        return $this;
    }
    
    /**
     * Add a POST route
     */
    public function post(string $path, $callback): self
    {
        $this->routes['post'][$path] = $callback;
        return $this;
    }
    
    /**
     * Add a PUT route
     */
    public function put(string $path, $callback): self
    {
        $this->routes['put'][$path] = $callback;
        return $this;
    }
    
    /**
     * Add a DELETE route
     */
    public function delete(string $path, $callback): self
    {
        $this->routes['delete'][$path] = $callback;
        return $this;
    }
    
    /**
     * Resolve the current route
     */
    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $callback = $this->routes[$method][$path] ?? false;
        
        // If route not found
        if (!$callback) {
            // Try to match routes with parameters
            $result = $this->matchRouteWithParams($method, $path);
            
            if (!$result) {
                throw new NotFoundException();
            }
            
            [$callback, $params] = $result;
            
            // Set route parameters in request object
            $this->request->setRouteParams($params);
        }
        
        // If callback is string (e.g., 'controller@method')
        if (is_string($callback)) {
            return $this->resolveControllerMethod($callback);
        }
        
        // If callback is array [Controller::class, 'method']
        if (is_array($callback) && count($callback) === 2 && is_string($callback[0]) && class_exists($callback[0])) {
            $controller = new $callback[0]();
            $method = $callback[1];
            
            return call_user_func([$controller, $method], $this->request, $this->response);
        }
        
        // If callback is closure
        return call_user_func($callback, $this->request, $this->response);
    }
    
    /**
     * Match route with parameters
     */
    protected function matchRouteWithParams(string $method, string $path)
    {
        if (!isset($this->routes[$method])) {
            return false;
        }
        
        // Loop through defined routes
        foreach ($this->routes[$method] as $route => $callback) {
            // Convert route parameters to regex pattern
            $pattern = preg_replace('/{([^}]+)}/', '(?P<$1>[^/]+)', $route);
            $pattern = "@^" . $pattern . "$@D";
            
            if (preg_match($pattern, $path, $matches)) {
                // Extract route parameters (non-numeric keys are named parameters)
                $params = array_filter($matches, fn($key) => !is_numeric($key), ARRAY_FILTER_USE_KEY);
                
                return [$callback, $params];
            }
        }
        
        return false;
    }
    
    /**
     * Resolve controller method from string
     */
    protected function resolveControllerMethod(string $callback)
    {
        [$controller, $method] = explode('@', $callback);
        
        $controllerClass = "App\\Controllers\\{$controller}";
        
        if (!class_exists($controllerClass)) {
            throw new NotFoundException("Controller {$controllerClass} not found");
        }
        
        $controllerInstance = new $controllerClass();
        
        if (!method_exists($controllerInstance, $method)) {
            throw new NotFoundException("Method {$method} not found in controller {$controllerClass}");
        }
        
        return call_user_func([$controllerInstance, $method], $this->request, $this->response);
    }
}