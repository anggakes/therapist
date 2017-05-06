<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Contracts\Events\Dispatcher;

class AuthMiddleware
{

    protected $auth;
    protected $events;

    public function __construct(JWTAuth $auth, Dispatcher $events)
    {
        $this->events = $events;
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (! $token = $this->auth->setRequest($request)->getToken()) {
            throw new BadRequestHttpException('token_not_provided');
        }

        try {
            $user = $this->auth->authenticate($token);
        } catch (TokenExpiredException $e) {
            throw new UnauthorizedHttpException("",'token_expired');
        } catch (JWTException $e) {
            throw new UnauthorizedHttpException("",'token_invailid');
        }

        if (! $user) {
            throw new UnauthorizedHttpException("",'user_not_found');
        }

        $this->events->fire('tymon.jwt.valid', $user);

        return $next($request);
    }
}
