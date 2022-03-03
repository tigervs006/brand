<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2020 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

namespace app\services;

use think\facade\Db;
use core\utils\JwtAuth;
use think\facade\Config;
use think\facade\Route as Url;

/**
 * Class BaseServices
 * @package app\services
 */
abstract class BaseServices
{
    /**
     * 模型注入
     * @var object
     */
    protected object $dao;

    /**
     * 获取分页配置
     * @param bool $isPage
     * @param bool $isRelieve
     * @return int[]
     */
    public function getPageValue(bool $isPage = true, bool $isRelieve = true): array
    {
        $page = $limit = 0;
        if ($isPage) {
            $page = app()->request->param(Config::get('database.page.pageKey', 'page') . '/d', 0);
            $limit = app()->request->param(Config::get('database.page.limitKey', 'limit') . '/d', 0);
        }
        $limitMax = Config::get('database.page.limitMax');
        $defaultLimit = Config::get('database.page.defaultLimit', 10);
        if ($limit > $limitMax && $isRelieve) {
            $limit = $limitMax;
        }
        return [(int)$page, (int)$limit, (int)$defaultLimit];
    }

    /**
     * 数据库事务操作
     * @param callable $closure
     * @param bool $isTran
     * @return mixed
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