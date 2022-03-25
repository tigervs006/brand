<?php
declare (strict_types = 1);
namespace app\dao\system;

use app\dao\BaseDao;
use think\Collection;
use app\model\system\Config;
use think\db\exception\DbException;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;

class ConfigDao extends BaseDao
{
    protected function setModel(): string
    {
        return Config::class;
    }

    /**
     * 获取系统配置项
     * @param array $map
     * @param string|null $field
     * @return array|Collection
     * @throws DbException
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     */
    public function getConfig(array $map, ?string $field): array|Collection
    {
        return $this->getData($map, [], $field);
    }
}
