<?php

namespace app\dao\channel;

use app\dao\BaseDao;
use think\Collection;
use app\model\channel\Channel;

class ChannelDao extends BaseDao
{
    protected function setModel(): string
    {
        return Channel::class;
    }
}