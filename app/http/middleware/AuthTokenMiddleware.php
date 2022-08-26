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
     * JwtAuth
     * @var JwtAuth
     */
    private JwtAuth $jwtService;

    public function __construct(JwtAuth $jwtService) {
        $this->jwtService = $jwtService;
    }

    /**
     * @return Response
     * @param \Closure $next
     * @param Request $request
     */
    public function handle(Request $request, \Closure $next): Response
    {
        if (config('index.access_token_check')) {
            $token = $request->header('Authorization');
            !$token && throw new AuthException('Token is missing or incorrect');
            $this->jwtService->verifyToken(trim($token));
        }

        return $next($request);
    }
}
