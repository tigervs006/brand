<?php

namespace app\http\middleware;

use think\Request;
use core\interfaces\MiddlewareInterface;
use app\services\system\SystemLogServices;

class ActionLogMiddleware implements MiddlewareInterface
{
    private SystemLogServices $logServices;

    public function __construct(SystemLogServices $logServices)
    {
        $this->logServices = $logServices;
    }

    public function handle(Request $request, \Closure $next)
    {
        /* 获取当前请求的token */
        $token = $request->header('Authorization');
        $this->logServices->actionLogRecord($request, $token);
        return $next($request);
    }
}
