<?php

use Illuminate\Http\Response;

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/api/animals', function () {
    $content = json_encode([
        [
            'species' => 'Penguin',
            'name' => 'Kowalski',
        ],
        [
            'species' => 'Lion',
            'name' => 'Simba',
        ],
    ]);

    return (new Response($content, 200))
        ->header('Content-Type', 'application/json');
});
