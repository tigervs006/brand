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
namespace app\dao\user;

use app\dao\BaseDao;
use app\model\user\JwtToken;

class JwtTokenDao extends BaseDao
{
    /**
     * @inheritDoc
     */
    protected function setModel(): string
    {
        return JwtToken::class;
    }
}
