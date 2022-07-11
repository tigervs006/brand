<?php
declare (strict_types = 1);
namespace app\console\controller\product;

use think\response\Json;
use core\basic\BaseController;
use core\exceptions\ApiException;
use think\exception\ValidateException;
use app\services\product\ProductServices;
use app\services\channel\ChannelServices;

class ProductController extends BaseController
{
    private ProductServices $services;

    private string $validator = 'app\console\validate\ProductValidator';

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
            'special',
            'content',
            'keywords',
            'description',
        ], null, 'trim');

        if (isset($post['id'])) {
            $message = '编辑';
        } else {
            $message = '新增';
            $post['click'] = mt_rand(436, 695);
        }

        /* 验证数据 */
        try {
            $this->validate($post, $this->validator);
        } catch (ValidateException $e) {
            throw new ApiException($e->getError());
        }

        $this->services->saveProduct($post, $message);

        return $this->json->successful($message . '商品成功');
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
     * 商品状态
     * @return Json
     */
    final public function setStatus(): Json
    {
        $data = $this->request->post(['status']);
        $message = $data['status'] ? '上架' : '下架';
        $this->services->updateOne($this->id, $data);
        return $this->json->successful($message . '商品成功');
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
