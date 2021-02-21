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
    return $router->app->version();
});

$router->group(["prefix" => "api/v1"], function () use ($router) {

    $router->group(["prefix" => "category"], function () use ($router) {
        $router->get("/", ["as" => "list_categories", "uses" => "CategoryController@index"]);
        $router->get("/{id}", ["as" => "show_category", "uses" => "CategoryController@show"]);
    });

    $router->group(["prefix" => "product"], function () use ($router) {
        $router->get("/", ["as" => "list_products", "uses" => "ProductController@index"]);
        $router->get("/{id}", ["as" => "show_product", "uses" => "ProductController@show"]);
    });

    $router->group(["middleware" => "auth"], function () use ($router) {

        $router->group(["prefix" => "category"], function () use ($router) {
            $router->post("/", ["as" => "store_category", "uses" => "CategoryController@store"]);
            $router->put("/{id}", ["as" => "update_category", "uses" => "CategoryController@update"]);
            $router->delete("/{id}", ["as" => "destroy_category", "uses" => "CategoryController@destroy"]);
        });

        $router->group(["prefix" => "product"], function () use ($router) {
            $router->post("/", ["as" => "store_product", "uses" => "ProductController@store"]);
            $router->put("/{id}", ["as" => "update_product", "uses" => "ProductController@update"]);
            $router->delete("/{id}", ["as" => "destroy_product", "uses" => "ProductController@destroy"]);
        });

    });
});