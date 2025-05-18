<?php
declare(strict_types=1);

// Define the application root directory
define('ROOT_DIR', dirname(__DIR__));

// Autoload classes
require_once ROOT_DIR . '/vendor/autoload.php';

// Load environment variables
require_once ROOT_DIR . '/config/bootstrap.php';

// Initialize and run the application
$app = new \App\Core\Application();
$app->run();


