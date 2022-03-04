<?php

namespace app\index\controller;

use app\index\BaseController;

class Product extends BaseController
{
    final public function index(): string
    {
         return $this->view::fetch('../product/index');
    }

    final public function detail(): string
    {
        return $this->view::fetch('../product/detail');
    }
}
