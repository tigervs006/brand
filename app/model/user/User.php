<?php
declare (strict_types = 1);
namespace app\model\user;

use core\basic\BaseModel;
use think\model\relation\HasOne;

class User extends BaseModel
{
    protected $pk = 'id';
    // 只读字段
    protected $readonly = ['create_time'];

    public function token(): HasOne
    {
        return $this->hasOne(JwtToken::class, 'uid', 'id')->field('uid, user');
    }
}
