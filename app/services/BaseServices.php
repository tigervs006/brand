<?php
declare (strict_types = 1);
namespace app\services;

use think\facade\Db;
use core\utils\JwtAuth;
use think\facade\Route as Url;
use core\traits\ServicesTrait;

/**
 * Class BaseServices
 * @package app\services
 */
abstract class BaseServices
{
    use ServicesTrait;

    /**
     * 模型注入
     * @var object
     */
    protected object $dao;

    /**
     * 数据库事务操作
     * @return mixed
     * @param bool $isTran
     * @param callable $closure
     */
    public function transaction(callable $closure, bool $isTran = true): mixed
    {
        return $isTran ? Db::transaction($closure) : $closure();
    }

    /**
     * 创建token
     * @param int $id
     * @param $type
     * @return array
     */
    public function createToken(int $id, $type): array
    {
        /** @var JwtAuth $jwtAuth */
        $jwtAuth = app()->make(JwtAuth::class);
        return $jwtAuth->createToken($id, $type);
    }

    /**
     * 获取路由地址
     * @return string
     * @param string $path
     * @param bool $suffix
     * @param array $params
     * @param bool $isDomain
     */
    public function url(string $path, array $params = [], bool $suffix = false, bool $isDomain = false): string
    {
        return Url::buildUrl($path, $params)->suffix($suffix)->domain($isDomain)->build();
    }

    /**
     * 密码hash加密
     * @param string $password
     * @return false|string|null
     */
    public function passwordHash(string $password): bool|string|null
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        return call_user_func_array([$this->dao, $name], $arguments);
    }
}
