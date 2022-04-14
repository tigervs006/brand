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
     * 自增阅读量
     * @return bool
     * @param int $id id
     * @param int $incValue 步长
     */
    public function setInc(int $id, int $incValue): bool
    {
        $articleClick = $this->getModel()->find($id);
        $articleClick->click += $incValue;
        return $articleClick->isAutoWriteTimestamp(false)->save();
    }

    /**
     * 文章内容
     * @return array
     * @param int $id
     */
    public function getArtContent(int $id): array
    {
        return $this->getModel()->where(['status' => 1])->with(['content'])->find($id)->toArray();
    }

    /**
     * 文章列表
     * @return array
     * @param int $page 页数
     * @param int $listRows 列数
     * @param array|null $order 排序
     * @param string|null $field 字段
     */
    public function getArtList(int $page, int $listRows, ?string $field = '*', ?array $order = ['id' => 'desc']): array
    {
        return $this->getModel()->with(['channel'])->field($field)->order($order)->page($page, $listRows)->select()->toArray();
    }

    /**
     * @return \think\Paginator
     * 前端分页列表
     * @param int $rows 数量
     * @param string $field 字段
     * @param array|null $order 排序
     */
    public function getPaginate(string $field, int $rows, ?array $order = null): \think\Paginator
    {
        return $this->getModel()->with(['channel'])->where(['status' => 1])->field($field)->order($order)->paginate($rows);
    }

    /**
     * 上/下一篇文章
     * @return array
     * @param int $id id
     */
    public function getPrenext(int $id): array
    {
        $next = $this->getModel()->where('id', '>', $id)->field('id,title')->limit(1)->select();
        $pre = $this->getModel()->where('id', '<', $id)->field('id,title')->order('id', 'desc')->limit(1)->select();
        if ($pre->isEmpty()) {
            $pre = array(
                'id' => '',
                'title' => '已经是第一篇了'
            );
        } else {
            $pre = array(
              'id' => $pre[0]['id'],
              'title' => $pre[0]['title']
            );
        }
        if ($next->isEmpty()) {
            $next = array(
                'id' => '',
                'title' => '这是最后一篇了'
            );
        } else {
            $next = array(
                'id' => $next[0]['id'],
                'title' => $next[0]['title']
            );
        }
        return compact('pre', 'next');
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
        return $this->getModel()->getFieldValue($value, $filed, $valueKey);
    }
}