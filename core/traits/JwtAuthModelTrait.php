<?php

namespace core\traits;

use Firebase\JWT\JWT;
use think\facade\Env;
use think\db\exception\DbException;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;

trait JwtAuthModelTrait
{
    /**
     * @param string $type
     * @param array $params
     * @return array
     */
    public function getToken(string $type, array $params = []): array
    {
        $id = $this->{$this->getPk()};
        $host = app()->request->host();
        $time = time();

        $params += [
            'iss' => $host,
            'aud' => $host,
            'iat' => $time,
            'nbf' => $time,
            'exp' => strtotime('+30 days'),
        ];

        $params['jti'] = compact('id', 'type');
        $token = JWT::encode($params, Env::get('app.app_key', 'default'));

        return compact('token', 'params');
    }

    /**
     * @return array
     * @param string $jwt
     * @throws DbException
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     */
    public static function parseToken(string $jwt): array
    {
        JWT::$leeway = 60;

        $data = JWT::decode($jwt, Env::get('app.app_key', 'default'), array('HS256'));

        $model = new self();
        return [$model->where($model->getPk(), $data->jti->id)->find(), $data->jti->type];
    }
}
