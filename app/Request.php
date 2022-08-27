<?php
namespace app;

use core\utils\JwtAuth;
use core\exceptions\AuthException;

/**
 * Class Request
 * @package app
 */
class Request extends \think\Request
{
    /**
     * 获取并解析token
     * @return array
     */
    public function tokenInfo(): array
    {
        $token = $this->header('Authorization');
        $jwtServices = app()->make(JwtAuth::class);
        return $token
            ? $jwtServices->parseToken($token)
            : throw new AuthException('Token is missing or incorrect');
    }
}
