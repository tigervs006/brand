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
        return null === $info ? $this->json->fail('查无此人...') : $this->json->successful(compact('info'));
    }

    /**
     * 获取用户列表
     * @return Json
     */
    public function lists(): Json
    {
        // 获得map条件
        $map = $this->request->only(['status'], 'get');
        // 获取排序字段
        $order = $this->request->only(['create_time', 'last_login'], 'get', 'strOrderFilter');
        $list = $this->services->getList($this->current, $this->pageSize, $map?: null, $this->field, $order);
        if ($list->isEmpty()) {
            return $this->json->fail('There is nothing...');
        } else {
            // 计算数据总量
            $total = $this->services->getCount($map ?: null);
            return $this->json->successful(compact('total', 'list'));
        }
    }

    /**
     * 单个/批量删除
     * @return Json
     */
    public function delete(): Json
    {
        $data = $this->services->delete($this->id);
        return !$data ? $this->json->fail('删除用户失败') : $this->json->successful('删除用户成功');
    }

    /**
     * 用户状态
     * @return Json
     */
    final public function setStatus(): Json
    {
        $data = $this->request->post(['status']);
        $this->services->updateOne($this->id, $data, 'id');
        return $this->json->successful($data['status'] ? '用户启用成功' : '用户禁用成功');
    }
}
