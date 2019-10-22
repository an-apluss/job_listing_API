<?php

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
    return 'Welcome to job listing API';
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('/signup', [
            'uses' => 'AuthController@signUp',
            'as' => 'user.signup'
        ]);

        $router->post('/signin', [
            'uses' => 'AuthController@signIn',
            'as' => 'user.signin'
        ]);
    });

    $router->post('/job', [
        'uses' => 'JobController@create',
        'as' => 'job.create'
    ]);
});