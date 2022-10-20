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

namespace core\exceptions;

use Throwable;

/**
 * Class AuthException
 * @package core\exceptions
 */
class UploadException extends \RuntimeException
{
    public function __construct(array|string $message = "", int $code = 400, Throwable $previous = null)
    {
        if (is_array($message)) {
            $errInfo = $message;
            $code = $errInfo[0] ?? $code;
            $message = $errInfo[1] ?? 'Upload failed';
        }
        parent::__construct($message, $code, $previous);
    }
}
