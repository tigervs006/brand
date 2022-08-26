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
     * @return Upload
     * @param int|null $type
     */
    public static function init(int $type = null): Upload
    {
        if (is_null($type)) {
            $type = (int) sys_config('upload_type');
        }
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

        /* 设置CDN域名 */
        1 < $type && $config['uploadUrl'] = sys_config('uploadUrl');
        return new Upload($type, $config);
    }
}
