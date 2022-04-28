<?php
declare (strict_types = 1);
namespace app\console\controller;

use core\basic\BaseController;
use app\services\user\UserServices;

class User extends BaseController
{
    /**
     * @var UserServices
     */
    private UserServices $services;

    /**
     * 提取字段
     * @var string
     */
    private string $field = 'id, gid, name, cname, status, email, avatar, ipaddress, last_login, create_time';

    public function initialize()
    {
        parent::initialize();
        $this->services = $this->app->make(UserServices::class);
    }

    /**
     * 获取用户列表
     * @return mixed
     */
    public function lists(): mixed
    {
        $data = $this->services->getData(null, $this->order, $this->field);
        return $this->json->successful('请求成功', compact('data'));
    }
}