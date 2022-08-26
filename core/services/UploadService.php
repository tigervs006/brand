<?php

namespace core\services;

use core\services\upload\Upload;
use core\exceptions\UploadException;

/**
 * Class UploadService
 * @package core\services
 */
class UploadService
{

    /**
     * @var array
     */
    protected static array $upload = [];

    /**
     * @param null $type
     * @return Upload|mixed
     */
    public static function init($type = null): mixed
    {
        if (is_null($type)) {
            $type = (int) sys_config('upload_type', 1);
        }
        if (isset(self::$upload['upload_' . $type])) {
            return self::$upload['upload_' . $type];
        }
        $type = (int) $type;
        $config = [];
        switch ($type) {
            case 1: // 本地
                break;
            case 2: // OSS
                $config = [
                    'storageName' => sys_config('alioss_bucket'),
                    'storageRegion' => sys_config('alioss_endpoint'),
                    'accessKey' => sys_config('alioss_accessKey_id'),
                    'secretKey' => sys_config('alioss_accessKey_secret'),
                ];
                break;
            case 3: // COS
                $config = [
                    'storageName' => sys_config('txcos_bucket'),
                    'accessKey' => sys_config('txcos_secret_id'),
                    'secretKey' => sys_config('txcos_secret_key'),
                    'storageRegion' => sys_config('txcos_region'),
                ];
                break;
            default:
                throw new UploadException('您已关闭上传功能');
        }

        // 定义CDN域名
        if (1 !== $type) {
            $config['uploadUrl'] = sys_config('uploadUrl');
        }

        return self::$upload['upload_' . $type] = new Upload($type, $config);
    }
}
