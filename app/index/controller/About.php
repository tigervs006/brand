<?php

namespace app\index\controller;

use app\BaseController;

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