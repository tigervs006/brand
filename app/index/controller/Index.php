<?php

namespace app\index\controller;

use app\index\BaseController;

class Index extends BaseController
{
    final public function index(): string
    {
        return $this->view::fetch('/index');
    }
}
