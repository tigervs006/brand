<?php

namespace app\console\validate;

use think\validate;

class FormValidator extends validate
{
    protected $regex = [
        'tel' => '1[3456789]\d{9}',
    ];

    protected $rule = [
        'username'  => 'require|max:20',
        'mobile'    => 'require|integer|regex:tel',
        'email'     => 'require|email',
        'company'   => 'require|min:4|max:30',
        'province'  => 'require',
        'city'      => 'require',
        'district'  => 'require',
        'message'   => 'require|min:10|max:256'
    ];

    protected $message = [
        'username.require'  => '请填写您的姓名',
        'username.max'      => '没有一句话这么长的姓名',
        'mobile.require'    => '请填写您的手机号码',
        'mobile.regex'      => '手机号码格式错误',
        'email.require'     => '请填写您的邮件地址',
        'email.email'       => '请正确填写您的邮箱',
        'company.require'   => '请填写您的公司名称',
        'company.min'       => '请填写您的公司简称或全称',
        'company.max'       => '没有一句话这么长的公司名称',
        'province.require'  => '请填写您所在的省份',
        'city.require'      => '请填写您所在的城市',
        'district.require'  => '请填写您所在的区域',
        'message.require'   => '请简要描述您的需求',
        'message.min'       => '请稍微详细点描述您的需求',
        'message.max'       => '留下您的联系后再和工作人员详细沟通吧'
    ];

    protected $scene = [
        'basic' => ['username', 'mobile', 'email', 'message'],
        'modal' => ['username', 'mobile', 'email', 'company', 'province', 'city', 'district', 'message']
    ];
}
