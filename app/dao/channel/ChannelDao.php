<?php
declare (strict_types = 1);

namespace app\dao\channel;

use app\dao\BaseDao;
use think\Collection;
use app\model\channel\Channel;

class ChannelDao extends BaseDao
{
    protected function setModel(): string
    {
        return Channel::class;
    }

    /**
     * @return array|Collection
     * @param array $map 条件
     * @param array|null $order 排序
     * @param string|null $field 字段
     */
    public function getChananel(array $map, ?array $order, ?string $field): array|Collection
    {
        return $this->getData($map, $order, $field);
    }

    /**
     * 获取栏目ID
     * @return mixed
     * @param string $value
     * @param string $filed
     * @param string $valueKey
     */
    public function getCid(string $value, string $filed, string $valueKey): mixed
    {
        return $this->getModel()->getFieldValue($value, $filed, $valueKey);
    }
}