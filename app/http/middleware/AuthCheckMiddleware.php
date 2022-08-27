<?php

namespace app\http\middleware;

use app\Request;
use core\utils\JwtAuth;
use app\services\auth\AuthServices;
use core\interfaces\MiddlewareInterface;

class AuthCheckMiddleware implements MiddlewareInterface
{
    private AuthServices $authServices;

    public function __construct(AuthServices $authServices)
    {
        $this->authServices = $authServices;
    }

    public function handle(Request $request, \Closure $next)
    {
        /* 获取当前请求的token */
        $token = $request->tokenInfo();
        $this->authServices->verifyAuthority($token);
        return $next($request);
    }
}
