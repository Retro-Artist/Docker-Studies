<?php
declare(strict_types=1);

use App\Core\Env;

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load environment variables from .env file
Env::load(ROOT_DIR);

// Set error reporting based on environment
if (Env::get('APP_ENV') === 'development') {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    error_reporting(0);
}

// Set timezone
date_default_timezone_set(Env::get('APP_TIMEZONE', 'UTC'));

