<?php

namespace core\traits;

trait ModelTrait
{
    /**
     * 获取某个字段值
     * @return mixed
     * @param int $id id
     * @param string $filed 字段
     * @param string $valueKey 键值
     * @param array|null $where 条件
     */
    public function getFieldValue(int $id, string $filed, string $valueKey, ?array $where = []): mixed
    {
        $model = $this->where($filed, $id);
        $where && $model->where(...$where);
        return $model->value($valueKey);
    }
}