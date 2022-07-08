<?php
declare (strict_types = 1);
namespace app\console\controller\product;

use think\response\Json;
use core\basic\BaseController;
use app\services\product\ProductServices;
use app\services\channel\ChannelServices;

class ProductController extends BaseController
{
    private ProductServices $services;

    public function initialize()
    {
        parent::initialize();
        $this->services = $this->app->make(ProductServices::class);
    }

    /**
     * 新增/编辑
     * @return Json
     */
    final public function save(): Json
    {
        $post = $this->request->post([
            'id',
            'pid',
            'album',
            'title',
            'status',
            'content',
            'keywords',
            'description',
        ], null, 'trim');
        return $this->json->successful(['info' => $post]);
    }

    /**
     * 删除商品
     * @return Json
     */
    final public function delete(): Json
    {
        $this->services->remove($this->id);
        return $this->json->successful('删除商品成功');
    }

    /**
     * 获取商品分类
     * @return Json
     */
    final public function getCate(): Json
    {
        $channelServices = $this->app->make(ChannelServices::class);
        $list = $channelServices->getChildInfo(['id' => 1], 'id, name, cname');
        return $list ? $this->json->successful(compact('list')) : $this->json->fail();
    }
}
