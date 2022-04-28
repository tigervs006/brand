<?php
declare (strict_types = 1);
namespace app\dao;

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
     * 默认状态
     * @var array|int[]
     */
    protected array $status = ['status' => 1];

    /**
     * 设置当前模型
     * @return string
     */
    abstract protected function setModel(): string;

    /**
     * 设置join链表模型
     */
    protected function setJoinModel(): string {
        return app()->make($this->setModel());
    }

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
     * 自增字段
     * @return bool
     * @param int $id id
     * @param int $incValue 自增值
     * @param string|null $field 自增字段
     */
    public function setInc(int $id, int $incValue, ?string $field = 'click'): bool
    {
        $data = $this->getModel()->find($id);
        $data->$field += $incValue;
        return $data->isAutoWriteTimestamp(false)->save();
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
     * @return array|\think\Collection
     * @param array|null $map 条件
     * @param array|null $order 排序
     * @param string|null $field 字段
     */
    public function getData(?array $map = null, ?array $order = ['id' => 'desc'], ?string $field = '*'): array|\think\Collection
    {
        if (is_null($map)) {
            return $this->getModel()->order($order)->field($field)->select();
        } else {
            return $this->getModel()->where($map)->order($order)->field($field)->select();
        }
    }

    /**
     * 计算数据总量
     * @return int
     * @param array|null $map 条件
     * @param string|null $field 字段
     */
    public function getCount(?array $map, ?string $field = 'id'): int
    {
        if (is_null($map)) {
            return $this->getModel()->count($field);
        } else {
            return $this->getModel()->where($map)->count();
        }
    }

    /**
     * 获取某个列数组
     * @return array
     * @param array|null $map 条件
     * @param string $field 字段
     * @param string|null $key 索引
     */
    public function getColumn(string $field, ?array $map = null, ?string $key = ''): array
    {
        if (is_null($map)) {
            return $this->getModel()->column($field, $key);
        } else {
            return $this->getModel()->where($map)->column($field, $key);
        }
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
     * @param int|array|string $id
     */
    public function delete(int|array|string $id, ?string $key = null): bool
    {
        if (is_array($id)) {
            $where = $id;
        } else {
            $where = [is_null($key) ? $this->getPk() : $key => $id];
        }
        // FIXME: delete方法实际返回的是int类型，ThinkPHP的bug
        return $this->getModel()::where($where)->useSoftDelete('delete_time',time())->delete() >= 1;
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
        return $this->getModel()::update($data, $where); // FIXME: update静态方法返回的是model实例，无法判断是否更新成功，ThinkPHP的bug
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

    /**
     * 获取某个字段值
     * @return mixed
     * @param string $field 字段
     * @param string $value 键值
     * @param array|null $where 条件
     * @param string|null $valueKey 键值
     */
    public function getFieldValue(string $value, string $field, ?string $valueKey, ?array $where = []): mixed
    {
        return $this->getModel()->getFieldValue($value, $field, $valueKey, $where);
    }
}
