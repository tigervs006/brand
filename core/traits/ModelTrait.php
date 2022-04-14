<?php

namespace core\traits;

trait ModelTrait
{
    /**
     * 获取某个字段值
     * @return mixed
     * @param string $value 值
     * @param string $field 字段
     * @param string $valueKey 键值
     * @param array|null $where 条件
     */
    public function getFieldValue(string $value, string $field, string $valueKey, ?array $where = []): mixed
    {
        $model = $this->where($field, $value);
        $where && $model->where(...$where);
        return $model->value($valueKey);
    }
}