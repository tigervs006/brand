<?php
declare (strict_types = 1);
namespace app\services\article;

use app\services\BaseServices;
use app\dao\article\ArticleContentDao;

/**
 * Class ArticleContentServices
 * @package app\services\article
 * @method save(array $data) 保存内容
 * @method update($id, array $data, ?string $key = null)
 */
class ArticleContentServices extends BaseServices
{
    /**
     * 构造函数
     * @param ArticleContentDao $dao
     */
    public function __construct(ArticleContentDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * 删除文章
     * @return bool
     * @param int|array $id
     */
    public function del(int|array $id): bool
    {
        return $this->dao->delete($id);
    }
}