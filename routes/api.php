<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');



$api->version('v1', function ($api) {

    $api->get("/phpinfo", function(){
        phpinfo();
    });

    /**
     *  MOBILE API
     */
    include_once __DIR__."/mobile_api.php";



    /** SERVICE API  */

    include_once __DIR__."/service_api.php";



});