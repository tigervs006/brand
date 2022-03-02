<?php

namespace app\index\controller;

use app\index\BaseController;

class Cases extends BaseController
{
    final public function index(): string
    {
        return $this->view::fetch('../case/index');
    }

    final public function detail(): string
    {
        return $this->view::fetch('../case/detail');
    }
}