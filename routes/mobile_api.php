<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/4/17
 * Time: 10:14 PM
 */
$api->get("/boot", 'App\Http\Controllers\Boot\BootController@index');

$api->group(['prefix'=>'v1', "middleware" => ['App\Http\Middleware\AfterResponse', 'App\Http\Middleware\BeforeRequest']], function ($api){

    $api->post("/register", 'App\Http\Controllers\Auth\AuthController@register');
    $api->post("/login",'App\Http\Controllers\Auth\AuthController@login' );
    $api->post("/auth/token", 'App\Http\Controllers\Auth\AuthController@refreshToken');

    $api->get("/boot", 'App\Http\Controllers\Boot\BootController@index');

    // authenticate route
    $api->group(["middleware"=> "jwt.auth"], function($api){

        $api->post('order/confirmation', 'App\Http\Controllers\Order\OrderController@confirmation');
        $api->post('current_location', 'App\Http\Controllers\CurrentLocation\CurrentLocationController@update');

    });

});