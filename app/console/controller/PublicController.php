<?php
declare (strict_types = 1);
namespace app\console\controller;

use think\response\Json;
use core\basic\BaseController;

class PublicController extends BaseController
{
    /**
     * 上传接口
     */
    final public function upload(): Json
    {
        try {
            $upload = \core\services\UploadService::init();
            $fileInfo = $upload->to('routine/product')->validate()->move();
            return $this->json->successful('文件上传成功', compact('fileInfo'));
        } catch (\Exception $e) {
            return $this->json->fail($e->getMessage());
        }
    }
}
