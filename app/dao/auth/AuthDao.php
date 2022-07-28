<?php
declare (strict_types = 1);
namespace app\dao\auth;

use app\dao\BaseDao;
use app\model\auth\AuthModel;

class AuthDao extends BaseDao
{

    protected function setModel(): string
    {
        return AuthModel::class;
    }

    /**
     * 查询用户菜单
     * @param string $ids ids
     * @return \think\Collection
     */
    public function queryMenu(string $ids): \think\Collection
    {
        return $this->getModel()->whereIn('id', $ids)->order('id', 'asc')->select();
    }
}
