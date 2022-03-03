<?php

declare (strict_types = 1);

namespace app\dao\channel;

use app\dao\BaseDao;

class ChannelDao extends BaseDao
{
    protected function setModel(): string
    {
        return ChannelDao::class;
    }
}