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
     * 自增阅读量
     * @return bool
     * @param int $id id
     * @param int $incValue 步长
     */
    public function inc(int $id, int $incValue): bool
    {
        return $this->dao->setInc($id, $incValue);
    }

    /**
     * 计算总数
     * @return int
     */
    public function count(): int
    {
        return $this->dao->getCount();
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
     * @return array
     * @param int $page
     * @param int $listRows
     * @param array|null $order
     * @param string|null $field
     */
    public function getList(int $page, int $listRows, ?string $field = '*', ?array $order = ['id' => 'desc']): array
    {
        return $this->dao->getArtList($page, $listRows, $field, $order);
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

    /**
     * 获取某个字段值
     * @return mixed
     * @param string $value 值
     * @param string $filed 字段
     * @param string $valueKey 键值
     */
    public function getFieldValue(string $value, string $filed, string $valueKey): mixed
    {
        return $this->dao->getFieldValue($value, $filed, $valueKey);
    }
}