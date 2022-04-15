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
}