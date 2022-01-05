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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('user', ['as' => 'user.store', 'uses' => 'UserController@store']);
$router->post('login', ['as' => 'login', 'uses' => 'UserController@login']);

$router->group(['middleware' => 'auth'], function () use ($router) {

    $router->post('customers/create', ['as' => 'customers.store', 'uses' => 'CustomerController@store']);
    $router->post('customers/show', ['as' => 'customers.show', 'uses' => 'CustomerController@show']);
    $router->post('customers/delete', ['as' => 'customers.delete', 'uses' => 'CustomerController@delete']);

    $router->post('logout', ['as' => 'logout', 'uses' => 'UserController@logout']);

    $router->get('user', function () use ($router) {
        return auth()->user();
    });
});