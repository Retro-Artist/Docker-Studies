<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;

/**
 * Home Controller
 */
class HomeController extends Controller
{
    /**
     * Display the home page
     */
    public function index(Request $request, Response $response): string
    {
        return $this->renderWithLayout('home/index', 'main', [
            'title' => 'Home Page',
            'message' => 'Welcome to our Clean MVC CRUD Application'
        ]);
    }
}