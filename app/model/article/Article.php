<?php

namespace app\model\article;

use core\basic\BaseModel;
use think\model\relation\HasOne;

class Article extends BaseModel
{
    // 只读字段
    protected $readonly = ['create_time'];

    public function content(): HasOne
    {
        return $this->hasOne(ArticleContent::class, 'aid', 'id')->bind(['content']);
    }
}