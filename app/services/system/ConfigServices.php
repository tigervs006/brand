<?php
declare (strict_types = 1);
namespace app\services\system;

use app\dao\system\ConfigDao;
use app\services\BaseServices;

class ConfigServices extends BaseServices
{
    public function __construct(ConfigDao $dao)
    {
        $this->dao = $dao;
    }
}
