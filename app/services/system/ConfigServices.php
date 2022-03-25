<?php
declare (strict_types = 1);
namespace app\services\system;

use think\Collection;
use app\dao\system\ConfigDao;
use app\services\BaseServices;
use think\db\exception\DbException;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;

class ConfigServices extends BaseServices
{
    public function __construct(ConfigDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @param array $map
     * @param string|null $field
     * @return array|Collection
     * @throws DbException
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     */
    public function index(array $map, ?string $field): array|Collection
    {
         return $this->dao->getConfig($map, $field);
    }

}