<?php
declare (strict_types = 1);
namespace app\console\controller\user;

use think\response\Json;
use core\basic\BaseController;
use core\exceptions\ApiException;
use app\services\user\UserServices;
use think\exception\ValidateException;

class UserController extends BaseController
{
    /**
     * @var UserServices
     */
    private UserServices $services;
    private string $validator = 'app\console\validate\UserValidator.';


    /**
     * 提取字段
     * @var string
     */
    private string $field = 'id, gid, name, cname, status, email, avatar, mobile, ipaddress, last_login, create_time';

    public function initialize()
    {
        parent::initialize();
        $this->services = $this->app->make(UserServices::class);
    }

    /**
     * 获取用户信息
     * @return Json
     */
    final public function index(): Json
    {
        $info = $this->services->getOne(['id' => $this->id], $this->field);
        return null === $info ? $this->json->fail('查无此人...') : $this->json->successful(compact('info'));
    }

    final public function save(): Json
    {
        $post = $this->request->only(
            [
                'id',
                'gid',
                'name',
                'scene',
                'cname',
                'email',
                'mobile',
                'avatar',
                'password',
                'oldPassword',
                'confirmPassword'
            ], 'post', 'trim'
        );
        // 过滤空值字段
        $data = array_filter($post, function ($val) {
            // 避免过滤0、boolean值
            return !("" === $val || null === $val);
        });
        $scene = isset($data['id']) ? 'edit' : 'save';
        $message = isset($data['id']) ? '编辑' : '新增';
        if (isset($data['id']) && isset($data['scene'])) {
            $scene = $data['scene'];
            if (isset($data['oldPassword'])) {
                $initPassword = $this->services->value(['id' => $data['id']], 'password');
                !password_verify($data['oldPassword'], $initPassword) && throw new ApiException('原密码验证失败');
            }
        }
        // 验证必要数据
        try {
            $this->validate($data, $this->validator . $scene);
        } catch (ValidateException $e) {
            throw new ApiException($e->getError());
        }
        $this->services->saveUser($data, $message);
        return $this->json->successful($message . '用户成功');
    }

    /**
     * 获取用户列表
     * @return Json
     */
    final public function list(): Json
    {
        // 获得map条件
        $map = $this->request->only(['status'], 'get');
        // 获取排序字段
        $order = $this->request->only(['create_time', 'last_login'], 'get', 'strOrderFilter');
        $list = $this->services->getList($this->current, $this->pageSize, $map?: null, $this->field, $order, ['group']);
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
    final public function delete(): Json
    {
        $res = $this->services->delete($this->id);
        return !$res ? $this->json->fail('删除用户失败') : $this->json->successful('删除用户成功');
    }

    /**
     * 用户状态
     * @return Json
     */
    final public function setStatus(): Json
    {
        $post = $this->request->post(['status']);
        $this->services->updateOne($this->id, $post, 'id');
        return $this->json->successful($post['status'] ? '启用成功' : '禁用成功');
    }
}
