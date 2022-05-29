<?php
declare (strict_types = 1);
namespace app\console\controller\user;

use think\response\Json;
use core\basic\BaseController;
use app\services\user\UserServices;

class UserController extends BaseController
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
     * 获取用户信息
     * @return Json
     */
    public function index(): Json
    {
        $info = $this->services->getOne(['id' => $this->id], $this->field);
        if (null === $info) {
            return $this->json->fail('查无此人...');
        } else {
            // 取出并解析Int格式的ip地址
            $info['ipaddress'] = long2ip($info['ipaddress']);
            return $this->json->successful('请求成功', compact('info'));
        }
    }

    /**
     * 获取用户列表
     * @return Json
     */
    public function lists(): Json
    {
        $list = $this->services->getData(null, $this->order, $this->field);
        if ($list->isEmpty()) {
            return $this->json->fail('There is nothing...');
        } else {
            return $this->json->successful('请求成功', compact('list'));
        }
    }

    /**
     * 单个/批量删除
     * @return Json
     */
    public function delete(): Json
    {
        $data = $this->services->delete($this->id);
        if (!$data) {
            return $this->json->fail('删除用户失败');
        } else {
            return $this->json->successful('删除用户成功');
        }
    }
}
