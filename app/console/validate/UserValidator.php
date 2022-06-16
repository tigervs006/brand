<?php

namespace app\console\validate;

use think\validate;

class UserValidator extends validate
{
    protected $regex = [
        'url'   => '[\/\/]{2}\w.*?'
    ];

    protected $rule = [
        'id'                => 'require',
        'gid'               => 'require',
        'name'              => 'require|alphaDash|max:20',
        'cname'             => 'require|chs|max:10',
        'email'             => 'require|email',
        'avatar'            => 'require|regex:url',
        'password'          => 'require|length:32',
        'confirmPassword'   => 'require|length:32|confirm:password'
    ];

    protected $message = [
        'id.require'                => '用户id不得为空',
        'gid.require'               => '请为用户设置用户组',
        'name.require'              => '用户名不得为空',
        'name.alphaDash'            => '用户名只能是数字、英文、下划线的组合',
        'name.max'                  => '这么长的用户名你确定能记得住?',
        'cname.require'             => '请为用户设置中文名',
        'cname.chs'                 => '用户姓名只能是中文',
        'cname.max'                 => '请做个行不更名坐不改姓的人',
        'email.require'             => '用户邮箱不得为空',
        'email.email'               => '请输入正确的用户邮箱',
        'avatar.require'            => '请上传用户头像',
        'avatar.regex'              => '用户头像网址错误，只需截取[//]后面的网址则可',
        'password.require'          => '用户密码不得为空',
        'password.length'           => '无效的用户密码',
        'confirmPassword.require'   => '请确认用户密码',
        'confrimPassword.length'    => '无效的确认用户密码',
        'confrimPassword.confirm'   => '确认的用户密码不一致',
    ];

    protected $scene = [
        'edit' => ['id', 'gid', 'name', 'cname', 'email', 'avatar'],
        'save' => ['gid', 'name', 'cname', 'email', 'avatar', 'password', 'confirmPassword']
    ];
}
