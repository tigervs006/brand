<?php

namespace app\services\channel;

use app\services\BaseServices;
use app\dao\channel\ChannelDao;

class ChannelServices extends BaseServices
{
    public function __construct(ChannelDao $dao)
    {
        $this->dao = $dao;
    }
}