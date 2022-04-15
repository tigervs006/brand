<?php
declare (strict_types = 1);
namespace app\dao\system;

use app\dao\BaseDao;
use app\model\system\Config;

class ConfigDao extends BaseDao
{
    protected function setModel(): string
    {
        return Config::class;
    }
}
