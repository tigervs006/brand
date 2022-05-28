<?php
declare (strict_types = 1);
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
