<?php

namespace app\dao\article;

use app\dao\BaseDao;
use app\model\article\Article;

class ArticleDao extends BaseDao
{
    public function setModel(): string
    {
        return Article::class;
    }

    /**
     * 文章内容
     * @return array
     * @param int $id
     */
    public function getArtContent(int $id): array
    {
        return $this->getModel()->where('status', 1)->with(['content'])->find($id)->toArray();
    }

    /**
     * 文章列表
     * @return array
     * @param int $page 页数
     * @param int $listRows 列数
     * @param array|null $order 排序
     */
    public function getArtList(int $page, int $listRows, ?array $order): array
    {
        return $this->getModel()->with(['channel'])->order($order)->page($page, $listRows)->select()->toArray();
    }

    /**
     * @param array $data
     * @return \core\basic\BaseModel
     * @throws \Exception
     */
    public function editArticle(array $data)
    {
        return $this->getModel()->with(['content'])::update($data);
    }
}