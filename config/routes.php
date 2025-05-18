<?php
declare(strict_types=1);

/**
 * Application Routes
 */

use App\Core\Application;

$router = Application::$app->router;

// Home page
$router->get('/', 'HomeController@index');

// Users CRUD routes
$router->get('/users', 'UserController@index');
$router->get('/users/create', 'UserController@create');
$router->post('/users', 'UserController@store');
$router->get('/users/{id}', 'UserController@show');
$router->get('/users/{id}/edit', 'UserController@edit');
$router->post('/users/{id}', 'UserController@update'); // Using POST with _method=PUT for HTML forms
$router->get('/users/{id}/delete', 'UserController@confirmDelete');
$router->post('/users/{id}/delete', 'UserController@destroy'); // Using POST with _method=DELETE for HTML forms

// API routes example
$router->get('/api/users', 'Api\\UserController@index');
$router->post('/api/users', 'Api\\UserController@store');
$router->get('/api/users/{id}', 'Api\\UserController@show');
$router->put('/api/users/{id}', 'Api\\UserController@update');
$router->delete('/api/users/{id}', 'Api\\UserController@destroy');