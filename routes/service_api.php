<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/4/17
 * Time: 10:14 PM
 */


$api->group(['prefix'=>'web_service', "middleware" => []], function ($api){

//    $api->post("/register", 'App\Http\Controllers\Auth\AuthController@register');
//

    $api->post('user/add', 'App\Http\Controllers\Auth\UserController@add');

    $api->post('order/find_therapist','App\Http\Controllers\Order\OrderWebServiceController@findTherapist' );





});