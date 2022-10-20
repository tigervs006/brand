<?php
/*
 * +---------------------------------------------------------------------------------
 * | Author: Kevin
 * +---------------------------------------------------------------------------------
 * | Email: Kevin@tigervs.com
 * +---------------------------------------------------------------------------------
 * | Copyright (c) Shenzhen Ruhu Technology Co., Ltd. 2018-2022. All rights reserved.
 * +---------------------------------------------------------------------------------
 */

declare (strict_types = 1);
namespace app\dao\system;

use app\dao\BaseDao;
use app\model\system\ActionLog;

class SystemLogDao extends BaseDao
{
    protected function setModel(): string
    {
        return ActionLog::class;
    }
}
