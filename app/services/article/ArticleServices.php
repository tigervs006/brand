<?php
declare (strict_types = 1);
namespace app\services\article;

use app\services\BaseServices;
use app\dao\article\ArticleDao;
use core\exceptions\ApiException;

class ArticleServices extends BaseServices
{
    /**
     * 构造函数
     * @param ArticleDao $dao
     */
    public function __construct(ArticleDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * 删除文章
     * @return void
     * @param int|array $id
     */
    public function del(int|array $id): void
    {
        /** @var  ArticleContentServices $articleContentService */
        $articleContentService = app()->make(ArticleContentServices::class);
        $this->transaction(function () use ($id, $articleContentService) {
            $result = $this->dao->delete($id);
            $result = $result && $articleContentService->del($id);
            !$result && throw new ApiException('删除文章失败');
        });
    }

    /**
     * 文章内容
     * @return array
     * @param int $id
     */
    public function article(int $id): array
    {
        return $this->dao->getArtContent($id);
    }

    /**
     * 文章列表
     * @return array
     * @param int $page
     * @param int $listRows
     * @param array|null $order
     */
    public function getList(int $page, int $listRows, ?array $order): array
    {
        $total = $this->dao->getCount();
        $result = $this->dao->getArtList($page, $listRows, $order);
        return compact('total', 'result');
    }

    /**
     * 新增|编辑
     * @return mixed
     * @param array $data
     */
    public function saveArticle(array $data): mixed
    {
        /** @var ArticleContentServices $articleContentService */
        $articleContentService = app()->make(ArticleContentServices::class);
        $id = $data['id'];
        $content['content'] = $data['content'];
        unset($data['content'], $data['id']);
        return $this->transaction(function () use ($id, $data, $articleContentService, $content) {
            if ($id) {
                $info = $this->dao->updateOne($id, $data);
                $content['aid'] = $id;
                $res = $info && $articleContentService->update($id, $content, 'aid');
            } else {
                unset($data['id']);
                $data['add_time'] = time();
                $info = $this->dao->saveAll($data);
                $content['aid'] = $info->id;
                $res = $info && $articleContentService->save($content);
            }
            if (!$res) {
                throw new ApiException('保存失败');
            } else {
                return $info;
            }
        });
    }
}