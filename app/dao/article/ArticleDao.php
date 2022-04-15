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
     * @return mixed
     * @param int $id
     */
    public function getArtContent(int $id): mixed
    {
        return $this->getModel()->where($this->status)->with(['content'])->find($id);
    }

    /**
     * 文章列表
     * @return array|\think\Collection
     * @param int $page 页数
     * @param int $listRows 列数
     * @param array|null $order 排序
     * @param string|null $field 字段
     */
    public function getArtList(int $page, int $listRows, ?string $field = '*', ?array $order = ['id' => 'desc']): array|\think\Collection
    {
        return $this->getModel()->with(['channel'])->field($field)->order($order)->page($page, $listRows)->select();
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
        return $this->getModel()->with(['channel'])->where($this->status)->field($field)->order($order)->paginate($rows);
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
}