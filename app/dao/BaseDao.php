<?php
namespace app\dao;

use think\Collection;
use core\basic\BaseModel;

abstract class BaseDao
{
    /**
     * 当前表别名
     * @var string
     */
    protected string $alias;

    /**
     * join表名
     * @var string
     */
    protected string $joinAlias;

    /**
     * 设置当前模型
     * @return string
     */
    abstract protected function setModel(): string;

    /**
     * 设置join链表模型
     */
    protected function setJoinModel() {}

    /**
     * 获取模型
     * @return BaseModel
     */
    protected function getModel(): BaseModel
    {
        return app()->make($this->setModel());
    }

    /**
     * 获取当前模型主键
     * @return string
     */
    protected function getPK(): string
    {
        return $this->getModel()->getPk();
    }

    /**
     * 根据条件获取单条数据
     * @return mixed
     * @param array $map 条件
     * @param string|null $field 字段
     */
    public function getOne(array $map, ?string $field = '*'): mixed
    {
        return $this->getModel()->where($map)->field($field)->find();
    }

    /**
     * 根据条件获取数据
     * @return array|Collection
     * @param array $map 条件
     * @param array|null $order 排序
     * @param string|null $field 字段
     */
    public function getData(array $map, ?array $order, ?string $field = '*'): array|Collection
    {
        if ($order) {
            return $this->getModel()->where($map)->order($order)->field($field)->select();
        } else {
            return $this->getModel()->where($map)->field($field)->select();
        }
    }

    /**
     * 计算数据总量
     * @return int
     * @param string|null $field
     */
    public function getCount(?string $field = 'id'): int
    {
        return $this->getModel()->count($field);
    }

    /**
     * 新增一条数据
     * @return BaseModel
     * @param array $data
     */
    public function saveOne(array $data): BaseModel
    {
        return $this->getModel()::create($data);
    }

    /**
     * 批量新增数据
     * @return int
     * @param array $data
     */
    public function saveAll(array $data): int
    {
        return $this->getModel()->insertAll($data);
    }

    /**
     * 删除一条或多条数据
     * @return boolean
     * @param int|array $id
     */
    public function delete(int|array $id, ?string $key = null): bool
    {
        if (is_array($id)) {
            $where = $id;
        } else {
            $where = [is_null($key) ? $this->getPk() : $key => $id];
        }
        return $this->getModel()::where($where)->useSoftDelete('delete_time',time())->delete();
    }

    /**
     * 更新一条数据
     * @return BaseModel
     * @param array $data
     * @param string|null $key
     * @param array|int|string $id
     */
    public function updateOne(int|array|string $id, array $data, ?string $key = null): BaseModel
    {
        if (is_array($id)) {
            $where = $id;
        } else {
            $where =[is_null($key) ? $this->getPk() : $key => $id];
        }
        return $this->getModel()::update($data, $where);
    }

    /**
     * 批量更新数据
     * @return BaseModel
     * @param array $ids
     * @param array $data
     * @param string|null $key
     */
    public function batchUpdate(array $ids, array $data, ?string $key): BaseModel
    {
        return $this->getModel()->whereIn(is_null($key) ? $this->getPK() : $key, $ids)->update($data);
    }
}
