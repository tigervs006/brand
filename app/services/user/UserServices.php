<?php
declare (strict_types = 1);
namespace app\services\user;

use app\dao\user\UserDao;
use app\services\BaseServices;
use core\exceptions\ApiException;

class UserServices extends BaseServices
{
    public function __construct(UserDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * 编辑/新增用户
     * @return mixed
     * @param array $data data
     * @param string $message message
     */
    public function saveUser(array $data, string $message): mixed
    {
        $id = $data['id'] ?? 0;
        unset($data['id']); // 释放$data中的id
        return $this->transaction(function () use ($id, $data, $message) {
            $res = $id ? $this->dao->updateOne($id, $data, 'id') : $this->dao->saveOne($data);
            !$res && throw new ApiException($message . '用户失败');
        });
    }
}
