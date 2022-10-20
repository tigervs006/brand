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

use app\Request;
use app\ExceptionHandle;

// 容器Provider定义文件
return [
    'think\Request'          => Request::class,
    'think\exception\Handle' => ExceptionHandle::class
];
