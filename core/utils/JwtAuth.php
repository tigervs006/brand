<?php

namespace core\utils;

use think\facade\Env;

class JwtAuth
{
    /**
     * @var string token
     */
    protected string $token;

    /**
     * 获取token
     * @param int $id
     * @param string $type
     * @param array $params
     * @return array
     */
    public function getToekn(int $id, string $type, array $params=[]): array
    {
        $time = time();
        $host = app()->request->host();
        $exp_time = strtotime('+ 30day');

        $params += [
            'iss' => $host,
            'aud' => $host,
            'iat' => $time,
            'nbf' => $time,
            'exp' => $exp_time,
        ];

        $params['jti'] = compact('id', 'type');

        $token = JWT::encode($params, Env::get('app.app_key', 'default'));
        return compact('token', 'params');
    }

    /**
     * 解析token
     * @return array
     * @param string $jwt
     */
    public function parseToken(string $jwt): array
    {
        return [];
    }

    /**
     * 验证token
     * @return void
     */
    public function verifyToken():void
    {

    }

}