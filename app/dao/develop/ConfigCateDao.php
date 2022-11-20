<?php
/*
 * +----------------------------------------------------------------------------------
 * | https://www.tigervs.com
 * +----------------------------------------------------------------------------------
 * | Email: Kevin@tigervs.com
 * +----------------------------------------------------------------------------------
 * | Copyright (c) Shenzhen Tiger Technology Co., Ltd. 2018~2022. All rights reserved.
 * +----------------------------------------------------------------------------------
 */

declare (strict_types=1);

namespace app\dao\develop;

use app\dao\BaseDao;
use app\model\develop\ConfigCate;

/**
 * class ConfigCateDao
 * @createAt 2022/11/16 12:08
 * @package app\dao\develop
 */
class ConfigCateDao extends BaseDao
{
    /**
     * @inheritDoc
     */
    protected function setModel(): string
    {
        return ConfigCate::class;
    }
}
