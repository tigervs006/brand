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

    public function updateConfig(array $data): \think\Collection
    {
        foreach ($data as $val) {
            10 === $val['id']
            && 15 < count(explode(',', $val['value']))
            && throw new ApiException('【首页关键词】不得超过15个，否则会被搜索引擎判断为堆砌关键词而K站');
        }
        return $this->dao->batchUpdateAll($data);
    }
}
