<?php

namespace app\http\middleware;

use think\Request;
use core\utils\JwtAuth;
use app\services\auth\AuthServices;
use core\interfaces\MiddlewareInterface;

class AuthCheckMiddleware implements MiddlewareInterface
{
    private JwtAuth $jwtServices;

    public function __construct()
    {
        $this->jwtServices = app()->make(JwtAuth::class);
    }

    public function handle(Request $request, \Closure $next)
    {
        /* 获取当前请求的token */
        $token = $request->header('Authorization');
        /* 解析当前请求的token */
        $tokenInfo = $this->jwtServices->parseToken($token);
        /** @var AuthServices $authServices */
        $authServices = app()->make(AuthServices::class);
        $authServices->verifyAuthority($request, $tokenInfo['gid']);
        return $next($request);
    }
}
