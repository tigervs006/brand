<?php
declare (strict_types = 1);
namespace app\model\user;

use core\basic\BaseModel;
use think\model\relation\HasOne;

class User extends BaseModel
{
    protected $pk = 'id';

    protected $updateTime = 'last_login';

    public function token(): HasOne
    {
        return $this->hasOne(JwtToken::class, 'uid', 'id')->field('uid, user');
    }
}
