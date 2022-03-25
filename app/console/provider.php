<?php

use app\console\ApiExceptionHandle;

// 容器Provider定义文件
return [
    'think\exception\Handle' => ApiExceptionHandle::class
];
