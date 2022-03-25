<?php

namespace app\index\controller;

use core\basic\BaseController;
use app\services\channel\ChannelServices;
use think\db\exception\DbException;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;

class Index extends BaseController
{
    final public function index(): string
    {
        return $this->view::fetch('/index');
    }
}
