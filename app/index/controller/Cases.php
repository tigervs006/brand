<?php
declare (strict_types = 1);
namespace app\index\controller;

use core\basic\BaseController;
use app\services\article\ArticleServices;
use app\services\channel\ChannelServices;

class Cases extends BaseController
{
    /**
     * @var ArticleServices
     */
    private ArticleServices $services;

    /**
     * @var ChannelServices
     */
    private ChannelServices $channelServices;

    /**
     * @var string
     */
    private string $field = 'id, cid, click, title, author, litpic, create_time, description';

    protected function initialize()
    {
        parent::initialize();
        $this->services = $this->app->make(ArticleServices::class);
        $this->channelServices = $this->app->make(ChannelServices::class);
    }

    final public function index(): string
    {
        $info = $this->services->get($this->id, null, ['content']);
        // 阅读量自增
        $info && $this->services->setInc($info['id'], $this->incValue);
        // 上/下一篇文章
        $prenext = $this->services->getPrenext($info['id'], array(['cid', '=', 6]), 'id, cid, title');
        return $this->view::fetch('../case/detail', compact('info', 'prenext'));
    }

    final public function list(): string
    {
        /* 获取当前栏目信息 */
        $info = $this->channelServices->listInfo();
        $map = array_merge($this->status, ['cid' => $info['ids']]);
        $list = $this->services->getPaginate($map, $this->current, 9, $info['fullpath'], '*', $this->order, ['channel']);
        return $this->view::fetch('../case/index', compact('list', 'info'));
    }
}
