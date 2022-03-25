<?php

namespace app\index\controller;

use core\basic\BaseController;

class Industry extends BaseController
{
    final public function index(): string
    {
        return $this->view::fetch('../industry/index');
    }

    final public function detail(): string
    {
        return $this->view::fetch('../industry/detail');
    }
}