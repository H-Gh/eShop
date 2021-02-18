<?php

/** @var Router $router */

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

use Laravel\Lumen\Routing\Router;

$router->get('/', function () use ($router) {
    throw new \App\Exceptions\FailedOperationException();
    return $router->app->version();
});

$router->group(["prefix" => "api/v1"], function () use ($router) {
    $router->group(["prefix" => "category"], function () use ($router) {
        $router->get("/", "CategoryController@index");
        $router->post("/", "CategoryController@store");
        $router->get("/{id}", "CategoryController@show");
        $router->put("/{id}", "CategoryController@update");
        $router->delete("/{id}", "CategoryController@destroy");
    });
});