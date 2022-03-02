<?php

namespace app\index\controller;

use app\index\BaseController;

class Services extends BaseController
{
    final public function index(): string
    {
        return $this->view::fetch('../services/index');
    }

    final public function video(): string
    {
        return $this->view::fetch('../services/video');
    }

    final public function detail(): string
    {
        return $this->view::fetch('../services/detail');
    }

    final public function software(): string
    {
        return $this->view::fetch('../services/software');
    }

    final public function instruction(): string
    {
        return $this->view::fetch('../services/instruction');
    }
}
