<?php

namespace core\traits;

trait ModelTrait
{
    /**
     * 获取某个字段值
     * @return mixed
     * @param $value
     * @param string $filed
     * @param array|null $where
     * @param string|null $valueKey
     */
    public function getFieldValue($value, string $filed, ?string $valueKey = '', ?array $where = []): mixed
    {
        $model = $this->where($filed, $value);
        if ($where) {
            $model->where(...$where);
        }
        return $model->value($valueKey ?: $filed);
    }
}