<?php

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
     * 设置当前模型
     * @return string
     */
    abstract protected function setModel(): string;

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
     * @param array $data
     * @throws \Exception
     * @return \think\Collection
     */
    public function saveAll(array $data): \think\Collection
    {
        return $this->getModel()->saveAll($data);
    }

    /**
     * 删除一条或多条数据
     * @return boolean
     * @param int|array|string $id
     */
    public function delete(int|array|string $id): bool
    {
        return $this->getModel()::destroy($id);
    }

    /**
     * 更新一条数据
     * @return BaseModel
     * @param array $data
     * @param string|null $key
     * @param array|int|string $id
     */
    public function updateOne(array|int|string $id, array $data, ?string $key): BaseModel
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
