<?php

namespace app\index\controller;

use app\index\BaseController;

class Area extends BaseController
{
    final public function office(): string
    {
        return $this->view::fetch('../area/office');
    }

    final public function affairs(): string
    {
        return $this->view::fetch('../area/affairs');
    }

    final public function finance(): string
    {
        return $this->view::fetch('../area/finance');
    }

    final public function medical(): string
    {
        return $this->view::fetch('../area/medical');
    }

    final public function educate(): string
    {
        return $this->view::fetch('../area/educate');
    }

    final public function telecom(): string
    {
        return $this->view::fetch('../area/telecom');
    }
}