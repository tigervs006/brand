<?php
/*
 * +---------------------------------------------------------------------------------
 * | Author: Kevin
 * +---------------------------------------------------------------------------------
 * | Email: Kevin@tigervs.com
 * +---------------------------------------------------------------------------------
 * | Copyright (c) Shenzhen Ruhu Technology Co., Ltd. 2018-2022. All rights reserved.
 * +---------------------------------------------------------------------------------
 */

declare (strict_types = 1);

use think\facade\Cache;
use app\services\system\ConfigServices;

return Cache::remember('index', function () {
    /** @var ConfigServices $services */
    $services = app()->make(ConfigServices::class);
    $result = $services->getData(['type' => [1, 4]], null, 'name, value')->toArray();
    return count($result) ? array_column($result, 'value', 'name') : '';
}, 3600 * 24 * 7);
