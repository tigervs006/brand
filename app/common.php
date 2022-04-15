<?php
declare (strict_types = 1);

// 应用公共文件

/**
 * 面包屑导航
 * @return array
 * @param string $channel
 */
function crumbs (string $channel): array
{
    static $field = 'id, pid, name, cname';
    /** @var app\services\channel\ChannelServices $services */
    $services = app()->make(app\services\channel\ChannelServices::class);
    // 获取栏目信息
    $pinfo = $services->getOne(array('name' => $channel, 'status' => 1), $field)->toArray();
    // 获取父栏目数据
    $pdata = $services->getParentInfo(array($pinfo), $field);
    // 获取面包屑导航
    return $services->getParentCrumbs($pdata);
}