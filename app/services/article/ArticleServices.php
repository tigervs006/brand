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
     * @param int|array|string $id
     */
    public function remove(int|array|string $id): void
    {
        /** @var  ArticleContentServices $articleContentService */
        $articleContentService = app()->make(ArticleContentServices::class);
        $this->transaction(function () use ($id, $articleContentService) {
            $result = $this->dao->delete($id) && $articleContentService->delete($id);
            !$result && throw new ApiException('删除文章失败');
        });
    }

    /**
     * 上/下一篇文章
     * @return array
     * @param int $id id
     */
    public function prenext(int $id): array
    {
        return $this->dao->getPrenext($id);
    }

    /**
     * 新增|编辑
     * @return mixed
     * @param array $data
     * @param string $message
     */
    public function saveArticle(array $data, string $message): mixed
    {
        $id = $data['id'] ?? 0;
        $content = $data['content'];
        unset($data['id'], $data['content']);
        /** @var ArticleContentServices $articleContentService */
        $articleContentService = app()->make(ArticleContentServices::class);
        return $this->transaction(function () use ($id, $data, $content, $message, $articleContentService) {
            if ($id) {
                $info = $this->dao->updateOne($id, $data, 'id');
                $res = $info && $articleContentService->updateOne($id, ['content' => $content], 'aid');
            } else {
                $info = $this->dao->saveOne($data);
                $res = $info && $articleContentService->saveOne(['aid' => $info->id, 'content' => $content]);
            }
            !$res && throw new ApiException($message . '文章失败');
        });
    }
}
