<?php
declare (strict_types = 1);
namespace app\dao\auth;

use app\dao\BaseDao;
use app\model\auth\AuthModel;
use core\exceptions\ApiException;
use think\db\exception\DbException;
use think\db\exception\DataNotFoundException;

class AuthDao extends BaseDao
{

    protected function setModel(): string
    {
        return AuthModel::class;
    }

    /**
     * 查询用户菜单
     * @param string $ids ids
     * @param ?array $where 条件
     * @return \think\Collection
     */
    public function queryMenu(string $ids, ?array $where = []): \think\Collection
    {
        try {
            return $this->getModel()->whereIn('id', $ids)->when(count($where), function ($query) use ($where) {
                $query->where($where);
            })->order('id', 'asc')->select();
        } catch (DataNotFoundException|DbException $e) {
            throw new ApiException($e->getMessage());
        }
    }
}
