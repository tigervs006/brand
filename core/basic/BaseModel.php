<?php

namespace core\basic;

use think\Model;
use core\traits\ModelTrait;

class BaseModel extends Model
{
    use ModelTrait;

    public function simple()
    {
        return $this->getFieldValue(1, 'id');

    }
}