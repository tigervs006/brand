<?php
declare (strict_types = 1);
namespace app\index\controller;

use core\basic\BaseController;
use app\services\channel\ChannelServices;
use app\services\product\ProductServices;

class Product extends BaseController
{

    /**
     * @var ProductServices
     */
    private ProductServices $services;

    /**
     * @var ChannelServices
     */
    private ChannelServices $channelServices;

    public function initialize()
    {
        parent::initialize();
        $this->services = $this->app->make(ProductServices::class);
        $this->channelServices = $this->app->make(ChannelServices::class);
    }

    /**
     * 商品详情
     * @return string
     */
    public function index(): string
    {
        $map = array_merge($this->status, ['id' => $this->id]);
        $info = $this->services->getOne($map, '*', ['detail']);
        is_null($info) && abort(404, "page doesn't exist");
        return $this->view::fetch('../product/detail', compact('info'));
    }

    /**
     * 商品列表
     * @return string
     * @throws \Throwable
     */
    public function list(): string
    {
        /* 获取当前栏目信息 */
        $info = $this->channelServices->listInfo();
        $map = array_merge($this->status, ['pid' => $info['ids']]);
        $list = $this->services->getPaginate($map, $this->current, $this->pageSize, $info['fullpath'], '*', $this->order, ['channel']);
        return $this->view::fetch('../product/index', compact('list', 'info'));
    }
}
