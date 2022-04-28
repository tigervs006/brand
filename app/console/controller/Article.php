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
        // 获取排序字段
        $order = $this->request->only(['click', 'create_time', 'update_time'], 'get', 'strOrderFilter');
        // 排除字段后获得map
        $map = $this->request->except(['click', 'current', 'pageSize', 'create_time', 'update_time'], 'get');
        // 提取数据总数
        $total = $this->services->getCount($map ?: null);
        // 提取文章列表
        $data = $this->services->getList($this->current, $this->pageSize, $map ?: null, $field, $order ?: $this->order);
        return $this->json->successful('请求成功', compact('total', 'data'));
    }

    /**
     * 获取作者
     * @return mixed
     */
    final public function getAuthor(): mixed
    {
        /** @var \app\services\user\UserServices $userServices */
        $userServices = $this->app->make(\app\services\user\UserServices::class);
        // 获取系统用户作为文章作者
        $data = $userServices->getData($this->status, $this->order, 'name, cname');
        return $this->json->successful('获取作者成功', compact('data'));
    }

    /**
     * 文章状态
     * @return mixed
     */
    final public function setStatus(): mixed
    {
        $data = $this->request->post(['status']);
        $message = $data['status'] ? '显示' : '隐藏';
        $this->services->updateOne($this->id, $data);
        return $this->json->successful($message . '文章成功');
    }
}