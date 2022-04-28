<?php
declare (strict_types = 1);
namespace app\index\controller;

use core\basic\BaseController;
use app\services\article\ArticleServices;

class Industry extends BaseController
{
    /**
     * @var ArticleServices
     */
    private ArticleServices $services;

    protected function initialize()
    {
        parent::initialize();
        $this->services = $this->app->make(ArticleServices::class);
        // 热门文章
        $this->view::assign('hotArt', $this->services->getList(
            $this->current, $this->pageSize,$this->status, 'id, click, title, litpic, create_time', ['click' => 'desc']));
    }

    /**
     * 文章列表
     * @return string
     */
    final public function index(): string
    {
        $result = $this->services->paginate('id, cid, click, title, author, litpic, create_time, description', $this->pageSize);
        return $this->view::fetch('../industry/index', ['result' => $result]);
    }

    /**
     * 文章内容
     * @return string
     */
    final public function detail(): string
    {
        $result = $this->services->article($this->id);
        // 阅读量自增
        $result && $this->services->setInc($result['id'], $this->incValue);
        // 上/下一篇文章
        $prenext = $this->services->prenext($result['id']);
        return $this->view::fetch('../industry/detail', ['result' => $result, 'prenext' => $prenext]);
    }
}