<?php

namespace app\dao;

use think\Model;
use core\basic\BaseModel;

abstract class BaseDao
{
    /**
     * 当前表别名
     * @var string
     */
    protected string $alias;

    /**
     * join表名
     * @var string
     */
    protected string $joinAlias;

    /**
     * 设置当前模型
     * @return string
     */
    abstract protected function setModel(): string;
}
