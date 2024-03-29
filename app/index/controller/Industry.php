<?php
/*
 * +----------------------------------------------------------------------------------
 * | https://www.tigervs.com
 * +----------------------------------------------------------------------------------
 * | Email: Kevin@tigervs.com
 * +----------------------------------------------------------------------------------
 * | Copyright (c) Shenzhen Tiger Technology Co., Ltd. 2018~2022. All rights reserved.
 * +----------------------------------------------------------------------------------
 */

declare (strict_types = 1);
namespace app\index\controller;

use think\Collection;
use core\basic\BaseController;
use app\services\channel\ChannelServices;
use app\services\article\ArticleServices;

class Industry extends BaseController
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
        $this->view::assign('hotArt', $this->hortArt()); // 获取热门文章
        $this->channelServices = $this->app->make(ChannelServices::class);
    }

    /**
     * 文章内容
     * @return string
     */
    final public function index(): string
    {
        $result = $this->services->get($this->id, null, ['content']);
        // 阅读量自增
        $result && $this->services->setInc($result['id'], $this->incValue);
        // 上/下一篇文章
        $prenext = $this->services->getPrenext($result['id'], array(['cid', '<>', 6]), 'id, cid, title');
        return $this->view::fetch('../industry/detail', ['result' => $result, 'prenext' => $prenext]);
    }

    /**
     * 文章列表
     * @return string
     */
    final public function list(): string
    {
        /* 获取当前栏目信息 */
        $info = $this->channelServices->listInfo();
        $map = array_merge($this->status, ['cid' => $info['ids']]);
        $list = $this->services->getPaginate($map, $this->current, $this->pageSize, $info['fullpath'], $this->field, $this->order, ['channel']);
        return $this->view::fetch('../industry/index', compact('list', 'info'));
    }

    /**
     * 热门文章
     * @return array|Collection
     */
    final public function hortArt(): array|Collection
    {
        return $this->services->getList(
            $this->current,
            $this->pageSize,
            array(['status', '=', 1], ['cid', '<>', 6]),
            'id, cid, click, title, litpic, create_time',
            ['click' => 'desc'], null, null, ['channel']);
    }
}
