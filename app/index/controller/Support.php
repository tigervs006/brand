<?php

namespace app\index\controller;

use app\index\BaseController;

class Support extends BaseController
{
    final public function index(): string
    {
        return $this->view::fetch('../support/index');
    }

    final public function video(): string
    {
        return $this->view::fetch('../support/video');
    }

    final public function detail(): string
    {
        return $this->view::fetch('../support/detail');
    }

    final public function software(): string
    {
        return $this->view::fetch('../support/software');
    }

    final public function instruction(): string
    {
        return $this->view::fetch('../support/instruction');
    }
}
