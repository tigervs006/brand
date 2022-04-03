<?php

use think\db\exception\DbException;
use app\services\system\ConfigServices;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;

/**
 * 获取所有配置项
 */
try {
    /** @var ConfigServices $services */
    $services = app()->make(ConfigServices::class);
    $result = $services->index(['status' => 1], 'name, value')->toArray();
    return array_column($result, 'value', 'name');
} catch (DbException|DataNotFoundException|ModelNotFoundException $e) {
    return app('json')->fail($e->getMessage());
}
