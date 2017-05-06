<?php

namespace App\Http\Middleware;

use App\Utils\ApiLogs;
use Closure;
use Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BeforeRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // checking dependencies api

        ApiLogs::writeRequest($request);

        if(Request::header("X-API-Client") !== "Latifa") throw new BadRequestHttpException("Request from unidentifed source");

        if(!Request::header("Device-ID")) throw new BadRequestHttpException("Device ID not set");



        return $next($request);
    }
}
