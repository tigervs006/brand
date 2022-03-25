<?php

use think\exception\HttpException;
use think\db\exception\DbException;
use app\services\system\ConfigServices;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;

/**
 * 获取前端配置项
 */
try {
    /** @var ConfigServices $services */
    $services = app()->make(ConfigServices::class);
    return array_column($services->index(['type' => 2, 'status' => 1], 'name, value')->toArray(), 'value', 'name');
} catch (DbException|DataNotFoundException|ModelNotFoundException $e) {
    throw new HttpException(400, $e->getMessage());
}
