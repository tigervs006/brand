<?php

namespace app\console\validate;

use think\validate;

class UserValidate extends validate
{
    protected $rule = [
        'id' => 'require',
        'gid' => 'require',
        'name' => 'require|alphaDash|max:20',
        'cname' => 'require|chs|max:10',
        'email' => 'require|email',
        'avatar' => 'require|url',
        'password' => 'require|length:32',
        'confirmPassword' => 'require|length:32|confirm:password'
    ];
}
