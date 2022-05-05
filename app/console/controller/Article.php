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
        $this->services = $this->app->make(ArticleServices::class);
    }

    /**
     * 获取文章
     * @return mixed
     */
    final public function index(): mixed
    {
        $info = $this->services->getOne(['id' => $this->id], null, ['content']);
        if (null === $info) {
            return $this->json->fail('There is nothing...');
        } else {
            return $this->json->successful('请求成功', compact('info'));
        }
    }

    /**
     * 删除文章
     * @return mixed
     */
    final public function delete(): mixed
    {
        $this->services->remove($this->id);
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
        $msg = isset($data['id']) ? '编辑' : '新增';
        return $this->json->successful($msg . '文章成功');
    }

    /**
     * 文章列表
     * @return mixed
     * FIXME: 组装的搜索条件不兼容$map内的其它搜索条件
     */
    final public function lists(): mixed
    {
        // 获取搜索标题
        $title = $this->request->get('title/s');
        // 获取时间范围
        $dateRange = $this->request->only(['startTime', 'endTime'], 'get');
        // 需提取的字段
        $field = 'id, cid, click, title, author, status, create_time, update_time, is_head, is_recom, is_collect';
        // 获取排序字段
        $order = $this->request->only(['click', 'create_time', 'update_time'], 'get', 'strOrderFilter');
        // 排除字段后获得map
        $map = $this->request->except(['title', 'click', 'current', 'pageSize','startTime', 'endTime', 'create_time', 'update_time'], 'get');
        // 组装按时间段搜索条件
        $dateRange && $map[] = ['create_time', 'between time', [$dateRange['startTime'], $dateRange['endTime']]];
        // 组装文章标题搜索条件
        $title && $map[] = ['title', 'like', '%' . $title . '%'];
        // 提取数据总数
        $total = $this->services->getCount($map ?: null);
        // 提取文章列表
        $list = $this->services->getList($this->current, $this->pageSize, $map ?: null, $field, $order ?: $this->order, ['channel']);
        if ($list->isEmpty()) {
            return $this->json->fail('There is nothing...');
        } else {
            return $this->json->successful('请求成功', compact('total', 'list'));
        }
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
        $list = $userServices->getData($this->status, $this->order, 'name, cname');
        if ($list->isEmpty()) {
            return $this->json->fail('There is nothing...');
        } else {
            return $this->json->successful('请求成功', compact('list'));
        }
    }

    /**
     * 新闻栏目
     * @return mixed
     */
    final public function getChannel (): mixed
    {
        /** @var \app\services\channel\ChannelServices $channelServices */
        $channelServices = $this->app->make(\app\services\channel\ChannelServices::class);
        $list = $channelServices->getChildInfo(['id' => 4], 'id, name, cname');
        if (empty($list)) {
            return $this->json->fail('There is nothing...');
        } else {
            return $this->json->successful('请求成功', compact('list'));
        }
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