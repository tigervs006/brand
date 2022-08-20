<?php

namespace app\http\middleware;

use think\Request;
use core\utils\JwtAuth;
use app\services\auth\AuthServices;
use core\interfaces\MiddlewareInterface;

class AuthCheckMiddleware implements MiddlewareInterface
{
    private JwtAuth $jwtServices;

    private AuthServices $authServices;

    public function __construct(JwtAuth $jwtAuth, AuthServices $authServices)
    {
        $this->jwtServices = $jwtAuth;
        $this->authServices = $authServices;
    }

    public function handle(Request $request, \Closure $next)
    {
        /* 获取当前请求的token */
        $token = $request->header('Authorization');
        /* 解析当前请求的token */
        $tokenInfo = $this->jwtServices->parseToken($token);
        $this->authServices->verifyAuthority($request, $tokenInfo['gid']);
        return $next($request);
    }
}
