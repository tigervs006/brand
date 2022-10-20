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
namespace app\index\controller;

use core\basic\BaseController;

class About extends BaseController
{
    final public function index(): string
    {
        return $this->view::fetch('../about/index');
    }

    final public function system(): string
    {
        return $this->view::fetch('../about/system');
    }

    final public function produce(): string
    {
        return $this->view::fetch('../about/produce');
    }
}
