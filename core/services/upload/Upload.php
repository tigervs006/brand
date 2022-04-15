<?php

namespace core\services\upload;

use think\facade\Config;
use core\basic\BaseManager;

/**
 * Class Upload
 * @package crmeb\services\upload
 * @mixin \core\services\upload\storage\OSS
 * @mixin \core\services\upload\storage\COS
 */
class Upload extends BaseManager
{
    /**
     * 空间名
     * @var string
     */
    protected $namespace = '\\core\\services\\upload\\storage\\';

    /**
     * 设置默认上传类型
     * @return mixed
     */
    protected function getDefaultDriver(): mixed
    {
        return Config::get('upload.default', 'local');
    }


}
