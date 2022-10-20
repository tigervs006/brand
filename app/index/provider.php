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

// 容器Provider定义文件
use app\index\ExceptionHandle;

return [
    'think\Paginator' => 'app\index\Bootstrap',
    'think\exception\Handle' => ExceptionHandle::class
];
