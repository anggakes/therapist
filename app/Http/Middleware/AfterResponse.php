<?php

namespace App\Http\Middleware;

use App\Utils\ApiLogs;
use App\Utils\ResponseModel;
use Closure;

class AfterResponse
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
        /** @var \Symfony\Component\HttpFoundation\Response $response */
        $response = $next($request);


        // build response success,
        // response error has handled by dingo
        if($response->getStatusCode() < 400){
            $data    = new ResponseModel(json_decode($response->getContent()) ?: $response->getContent());
            $response->setContent(json_encode($data));
        }

        ApiLogs::writeResponse(json_decode($response->getContent()));

        return $response;
    }
}
