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

    /**
     * @var string 字段
     */
    private string $field = 'id, title, click, litpic, author, is_head, create_time, description';

    protected function initialize()
    {
        parent::initialize();
        $this->services = $this->app->make(ArticleServices::class);
    }

    /**
     * 系统环境
     * @return string
     */
    final public function info(): string
    {
        $content = phpinfo(INFO_MODULES);
        return $this->view::display((string) $content);
    }

    /**
     * 文章列表
     * @return string
     */
    final public function index(): string
    {
        $hotArt = $this->services->getList(1, 7, $this->status, $this->field, ['is_head' => 'desc', 'id' => 'desc']);
        return $this->view::fetch('/index', ['hotart' => $hotArt]);
    }
}
