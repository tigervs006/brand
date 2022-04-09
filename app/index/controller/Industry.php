<?php

namespace app\index\controller;

use core\basic\BaseController;
use app\services\article\ArticleServices;

class Industry extends BaseController
{
    /**
     * 分页数
     * @var int
     */
    private int $rows = 10;

    /**
     * @var ArticleServices
     */
    private ArticleServices $services;

    /**
     * 排序方式
     * @var array|string[]
     */
    private array $order = ['id' => 'desc'];

    /**
     * 获取字段
     * @var string
     */
    private string $field = 'id, cid, click, title, author, litpic, create_time, description';


    protected function initialize()
    {
        parent::initialize();
        $this->services = app()->make(ArticleServices::class);
    }

    /**
     * 文章列表
     * @return string
     */
    final public function index(): string
    {
        $result = $this->services->paginate($this->field, $this->rows);
        $hotArt = $this->services->paginate('id, cid, click, title, litpic, create_time', $this->rows, ['click' => 'desc']);
        $this->view::assign(['result' => $result, 'hotArt' => $hotArt]);
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