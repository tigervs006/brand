<?php
declare (strict_types = 1);

namespace app\dao\channel;

use app\dao\BaseDao;
use think\Collection;
use app\model\channel\Channel;
use think\db\exception\DbException;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;

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
     * @throws DbException
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     */
    public function getChananel(array $map, ?array $order, ?string $field): array|Collection
    {
        return $this->getData($map, $order, $field);
    }
}