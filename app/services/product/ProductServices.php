<?php
declare (strict_types = 1);
namespace app\services\product;

use app\services\BaseServices;
use app\dao\product\ProductDao;
use core\exceptions\ApiException;

class ProductServices extends BaseServices
{
    public function __construct(ProductDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * 删除文章
     * @return void
     * @param int|array|string $id
     */
    public function remove(int|array|string $id): void
    {
        /** @var  ProductDetailServices $productDetailServices */
        $productDetailServices = app()->make(ProductDetailServices::class);
        $this->transaction(function () use ($id, $productDetailServices) {
            $result = $this->dao->delete($id) && $productDetailServices->delete($id);
            !$result && throw new ApiException('删除商品失败');
        });
    }
}
