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
    public function delete(int|array|string $id): void
    {
        /** @var  ArticleContentServices $articleContentService */
        $articleContentService = app()->make(ArticleContentServices::class);
        $this->transaction(function () use ($id, $articleContentService) {
            $result = $this->dao->delete($id) && $articleContentService->delete($id);
            !$result && throw new ApiException('删除文章失败');
        });
    }

    /**
     * 文章内容
     * @return mixed
     * @param int|string $id
     */
    public function article(int|string $id): mixed
    {
        return $this->dao->getArtContent($id);
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
     * 分页列表，用于前端
     * @param int $rows 数量
     * @param string $field 字段
     * @param array|null $order 排序
     */
    public function paginate(string $field, int $rows, ?array $order = ['id' => 'desc']): \think\Paginator
    {
        return $this->dao->getPaginate($field, $rows, $order);
    }

    /**
     * 文章列表
     * @return array|\think\Collection
     * @param int $current 当前页
     * @param int $pageSize 数量
     * @param array|null $map 条件
     * @param array|null $order 排序
     * @param string|null $field 字段
     */
    public function getList(int $current, int $pageSize, ?array $map, ?string $field, ?array $order): array|\think\Collection
    {
        return $this->dao->getArtList($current, $pageSize, $map, $field, $order);
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
            switch (true) {
                case $id:
                    $info = $this->dao->updateOne($id, $data);
                    $content['aid'] = $id;
                    $res = $info && $articleContentService->update($id, $content, 'aid');
                    break;
                default:
                    unset($data['id']);
                    $data['add_time'] = time();
                    $info = $this->dao->saveAll($data);
                    $content['aid'] = $info->id;
                    $res = $info && $articleContentService->save($content);
            }
            if ($res) {
                return $info;
            } else {
                throw new ApiException('保存失败');
            }
        });
    }
}