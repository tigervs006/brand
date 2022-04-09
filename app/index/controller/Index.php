<?php

namespace app\index\controller;

use core\basic\BaseController;
use app\services\article\ArticleServices;

class Index extends BaseController
{
    /**
     * @var ArticleServices
     */
    private ArticleServices $services;

    protected function initialize()
    {
        parent::initialize();
        $this->services = app()->make(ArticleServices::class);
    }

    final public function index(): string
    {
        $hotArt = $this->services->getList(1, 7, 'id, title, click, litpic, author, is_head, create_time, description', ['is_head' => 'desc', 'id' => 'desc']);
        $this->view::assign('hotart', $hotArt);
        return $this->view::fetch('/index');
    }
}
