<?php

namespace app\index\controller;

use core\basic\BaseController;
use app\services\article\ArticleServices;

class Industry extends BaseController
{
    /**
     * 数量
     * @var int
     */
    private int $rows = 10;

    /**
     * @var ArticleServices
     */
    private ArticleServices $services;

    protected function initialize()
    {
        parent::initialize();
        $this->services = app()->make(ArticleServices::class);
        // 热门文章
        $this->view::assign('hotArt', $this->services->getList(
            $this->page, $this->rows,'id, click, title, litpic, create_time', ['click' => 'desc']));
    }

    /**
     * 文章列表
     * @return string
     */
    final public function index(): string
    {
        $result = $this->services->paginate('id, cid, click, title, author, litpic, create_time, description', $this->rows);
        $this->view::assign(['result' => $result]);
        return $this->view::fetch('../industry/index');
    }

    /**
     * 文章内容
     * @return string
     */
    final public function detail(): string
    {
        $result = $this->services->article($this->id);
        // 阅读量自增
        $result && $this->services->inc($result['id'], $this->incValue);
        // 上/下一篇文章
        $prenext = $this->services->prenext($result['id']);
        $this->view::assign(['result' => $result, 'prenext' => $prenext]);
        return $this->view::fetch('../industry/detail');
    }
}