<?php

namespace app\console\validate;

use think\validate;

class MenuValidator extends validate
{
    protected $regex = [
        'path' => '[\d?:\-]+',
        'locale' => '[\w?:\.]+',
    ];

    protected $rule = [
        'pid'                   => 'require|integer',
        'name'                  => 'require|alphaDash',
        'icon'                  => 'requireIf:pid,0|alphaDash',
        'sort'                  => 'integer|between:1,1000',
        'paths'                 => 'require|regex:path',
        'locale'                => 'require|regex:locale',
        'authority'             => 'array',
    ];

    protected $message = [
        'pid.require'           => '请选择上级菜单，默认为顶级菜单',
        'pid.integer'           => '上级菜单的id必须为正整数',
        'paths.require'         => '菜单路径是必填字段',
        'paths.regex'           => '菜单路径错误，参考格式：0-1-2',
        'name.require'          => '菜单名称为必填项',
        'name.alphaDash'        => '菜单名称只能是英文字母、数字和下划线、破折号的组合',
        'icon.requireIf'        => '请为顶级栏目菜单设置图标',
        'icon.alphaDash'        => '菜单图标只能是英文字母、数字和下划线、破折号的组合',
        'sort.integer'          => '菜单排序必须是正整数',
        'sort.between'          => '菜单排序数值应在1~1000之间',
        'locale.require'        => '本地语言是必填字段',
        'locale.regex'          => '多语言只能是英文字母、数字和下划线、英文句号的组合',
        'authority.array'       => '菜单权限参数类型必须为数组形式'
    ];

}
