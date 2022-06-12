<?php

namespace app\console\validate;

use think\validate;

class ChannelValidator extends validate
{
    protected $rule = [
        'pid' => 'require|integer',
        'sort' => 'integer|between:1,1000',
        'name' => 'require|alphaDash|min:2|max:20',
        'cname' => 'require|chs|min:2|max:20',
        'banner' => 'require',
        'status' => 'integer|between:0,1',
        'title' => 'require|min:8|max:32',
        'keywords' => 'require|min:8|max:32',
        'description' => 'require|min:20|max:100'
    ];

    protected $message = [
        'pid.require' => '请选择上级栏目',
        'pid.integer' => '上级栏目ID必须是正整数',
        'sort.integer' => '栏目排序必须是正整数',
        'sort.between' => '栏目排序范围应在1~1000',
        'name.require' => '栏目英文不得为空',
        'name.alphaDash' => '栏目英文就为字母、数字或下划线的组合',
        'name.min' => '栏目英文不得少于2个字符',
        'name.max' => '栏目英文请控制在20个字符以内',
        'cname.require' => '栏目名称不得为空',
        'cname.chs' => '栏目名称必须是中文字符',
        'cname.min' => '栏目名称不得于2个中文字符',
        'cname.max' => '栏目名称不得超过20个中文字符',
        'banner.require' => '栏目大图不得为空',
        'status.integer' => '栏目状态必须是正整数',
        'status.between' => '栏目状态的值应为0或1',
        'title.require' => 'SEO标题不得为空',
        'title.min' => 'SEO标题不得少于8个字符',
        'title.max' => 'SEO标题请控制在32个字符以内',
        'keywords.require' => 'SEO关键词不得为空',
        'keywords.min' => 'SEO关键词不得少于8个字符',
        'keywords.max' => 'SEO关键词请控制在32个字符以内',
        'description.require' => 'SEO描述不得不空',
        'description.min' => 'SEO描述不得少20个字符',
        'description.max' => 'SEO描述请控制在100个字符以内'
    ];

    protected $scene = [
        'single' => ['pid', 'sort', 'name', 'cname', 'status'],
        'save' => ['pid', 'sort', 'name', 'cname', 'banner', 'status', 'title', 'keywords', 'description']
    ];
}
