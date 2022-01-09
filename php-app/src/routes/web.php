<?php

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

// $router->get('/api', function () use ($router) {
//     return "Welcome to the Frisbo Takeathome project. Good luck!";
// });


$router->group(['prefix' => 'api'], function() use ($router) {
    $router->get('/', function () {});

    $router->get('/organizations', 'OrganizationsController@index');

    $router->get('/orders[/{organizationId}]', 'OrdersController@index');
});
