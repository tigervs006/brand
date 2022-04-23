<?php
declare (strict_types = 1);
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

    final public function info(): string
    {
        $content = phpinfo(INFO_MODULES);
        return $this->view::display((string) $content);
    }

    final public function index(): string
    {
        $hotArt = $this->services->getList(1, 7, 'id, title, click, litpic, author, is_head, create_time, description', ['is_head' => 'desc', 'id' => 'desc']);
        return $this->view::fetch('/index', ['hotart' => $hotArt]);
    }
}
