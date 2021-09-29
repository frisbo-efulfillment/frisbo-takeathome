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

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get("/", function (){return "adasdasdasd";});

    $router->group(['prefix' => 'organizations'], function () use ($router) {
        $router->get("/", ['uses' => 'OrganizationController@index']);

        $router->group(['prefix' => '{organizationId}'], function () use ($router) {
            $router->get("/orders", ['uses' => 'OrganizationController@orders']);

        });
    });

    $router->post("/login", ['uses' => 'AuthController@login']);

});
