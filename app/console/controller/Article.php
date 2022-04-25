<?php
declare (strict_types = 1);
namespace app\console\controller;

use core\basic\BaseController;
use app\services\article\ArticleServices;

class Article extends BaseController
{
    private ArticleServices $services;

    protected function initialize()
    {
        parent::initialize();
        $this->services = app()->make(ArticleServices::class);
    }

    /**
     * 文章内容
     * @return mixed
     */
    final public function index(): mixed
    {
        $result = $this->services->article($this->id)->toArray();
        return $this->json->successful('请求成功', $result);
    }

    /**
     * 删除文章
     * @return mixed
     */
    final public function delete(): mixed
    {
        $this->services->delete($this->id);
        return $this->json->successful('删除成功');
    }

    /**
     * 新增|编辑
     * @return mixed
     */
    final public function save(): mixed
    {
        $data = $this->request->param();
        $this->services->saveArticle($data);
        return $this->json->successful('请求成功', $this->request->param());
    }

    /**
     * 文章列表
     * @return mixed
     */
    final public function lists(): mixed
    {
        // 需提取的字段
        $field = 'id, cid, click, title, author, status, create_time, update_time, is_head, is_recom, is_collect';
        $orderField = $this->request->param('sortField/s', 'id');
        $sortOrder = $this->request->param('sortOrder/s', 'desc');
        $total = $this->services->getCount($this->status); // 获取除删除外的所有文章总数
        $data = $this->services->getList($this->current, $this->pageSize, $field, [$orderField => $sortOrder]);
        return $this->json->successful('请求成功', compact('total', 'data'));
    }
}