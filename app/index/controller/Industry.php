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
        $this->view::assign('hotArt', $this->hortArt());// 获取热门文章
    }

    /**
     * 文章列表
     * @return string
     */
    final public function index(): string
    {
        $field = 'id, cid, click, title, author, litpic, create_time, description';
        $result = $this->services->getPaginate($this->status, $this->pageSize, $field, $this->order, ['channel']);
        return $this->view::fetch('../industry/index', ['result' => $result]);
    }

    /**
     * 文章内容
     * @return string
     */
    final public function detail(): string
    {
        $result = $this->services->get($this->id, null, ['content']);
        // 阅读量自增
        $result && $this->services->setInc($result['id'], $this->incValue);
        // 上/下一篇文章
        $prenext = $this->services->getPrenext($result['id']);
        return $this->view::fetch('../industry/detail', ['result' => $result, 'prenext' => $prenext]);
    }

    /**
     * 热门文章
     * @return array|\think\Collection
     */
    final public function hortArt(): array|\think\Collection
    {
        return $this->services->getList($this->current, $this->pageSize,$this->status, 'id, click, title, litpic, create_time', ['click' => 'desc']);
    }
}
