<?php
declare (strict_types = 1);
namespace app\index\controller;

use think\Collection;
use think\response\Json;
use core\basic\BaseController;
use app\services\system\RegionServices;
use app\services\article\ArticleServices;
use app\services\product\ProductServices;

class Index extends BaseController
{
    /**
     * @var RegionServices
     */
    private RegionServices $regionServices;

    /**
     * @var ProductServices
     */
    private ProductServices $productServices;

    /**
     * @var ArticleServices
     */
    private ArticleServices $articleServices;

    /**
     * @var string 区域字段
     */
    private string $region_field = 'id, cid, pid, code, name';

    private string $product_field = 'id, pid, title, click, album, special, description';

    /**
     * @var string 文档字段
     */
    private string $article_field = 'id, cid, title, click, litpic, author, is_head, create_time, description';

    protected function initialize()
    {
        parent::initialize();
        $this->regionServices = $this->app->make(RegionServices::class);
        $this->articleServices = $this->app->make(ArticleServices::class);
        $this->productServices = $this->app->make(ProductServices::class);
    }

    /**
     * 首页数据
     * @return string
     */
    final public function index(): string
    {
        return $this->view::fetch('/index', ['case' => $this->getCase(), 'hotart' => $this->getArticle(), 'product' => $this->getProduct()]);
    }

    /**
     * 获取商品列表
     * @return Collection
     */
    private function getProduct(): Collection
    {
        return $this->productServices->getList(
            1,
            8,
            $this->status,
            $this->product_field,
            ['click' => 'desc', 'id' => 'desc'],
            null, null, ['channel']
        );
    }

    /**
     * 获取案例列表
     * @return Collection
     */
    private function getCase(): Collection
    {
        return $this->articleServices->getList(
            1,
            4,
            array(['status', '=', 1], ['cid', '=', 6]),
            $this->article_field,
            $this->order,
            null, null, ['channel']
        );
    }

    /**
     * 获取文档列表
     * @return Collection
     */
    private function getArticle(): Collection
    {
        return $this->articleServices->getList(
            1,
            7,
            array(['status', '=', 1], ['cid', '<>', 6]),
            $this->article_field,
            ['is_head' => 'desc', 'id' => 'desc'],
            null, null, ['channel']
        );
    }

    /**
     * 行政区域
     * @return Json
     */
    final public function region(): Json
    {
        $pid = $this->request->get('pid/d', 0);
        $list = $this->regionServices->getChildCity($pid, $this->region_field);
        return $this->json->successful(compact('list'));
    }
}
