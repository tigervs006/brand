<?php
declare (strict_types = 1);
namespace app\services\system;

use app\dao\system\ConfigDao;
use app\services\BaseServices;
use core\exceptions\ApiException;

class ConfigServices extends BaseServices
{
    public function __construct(ConfigDao $dao)
    {
        $this->dao = $dao;
    }

    public function updateConfig(array $data, ?string $message = '更新'): \think\Collection
    {
        return $this->dao->batchUpdateAll($data);
    }
}
