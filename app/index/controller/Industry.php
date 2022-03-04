<?php

namespace app\index\controller;

use app\index\BaseController;

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