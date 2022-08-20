<?php
declare (strict_types = 1);
namespace app\http\middleware;

use think\Request;
use think\Response;
use core\utils\JwtAuth;
use core\exceptions\AuthException;
use core\interfaces\MiddlewareInterface;

/**
 * AuthToken中间件
 * Class AuthTokenMiddleware
 * @package app\http\middleware
 */
class AuthTokenMiddleware implements MiddlewareInterface
{
    /**
     * 是否校验token
     * @var int
     */
    private int $isCheckToken;

    /**
     * JwtAuth
     * @var JwtAuth
     */
    private JwtAuth $jwtService;

    public function __construct(JwtAuth $jwtService) {
        $this->jwtService = $jwtService;
        $this->isCheckToken = (int) sys_config('access_token_check');
    }

    /**
     * @return Response
     * @param \Closure $next
     * @param Request $request
     */
    public function handle(Request $request, \Closure $next): Response
    {
        if ($this->isCheckToken) {
            $token = $request->header('Authorization');
            !$token && throw new AuthException('Token have been losted!');
            $this->jwtService->verifyToken(trim($token));
        }

        return $next($request);
    }
}
