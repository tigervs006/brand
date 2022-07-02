<?php
declare (strict_types = 1);
namespace app\services\auth;

use app\dao\auth\GroupDao;
use app\services\BaseServices;
use core\exceptions\ApiException;

class GroupServices extends BaseServices
{
    /**
     * @param GroupDao $dao
     */
    public function __construct(GroupDao $dao)
    {
        $this->dao = $dao;
    }

    public function saveGroup(array $data, string $message): void
    {
        $id = $data['id'] ?? 0;
        unset($data['id']); // 释放$data中的id
        $this->transaction(function () use ($id, $data, $message) {
            $res = $id ? $this->dao->updateOne($id, $data, 'id') : $this->dao->saveOne($data);
            !$res && throw new ApiException($message . '用户组失败');
        });
    }
}
