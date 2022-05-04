<?php
declare (strict_types = 1);
namespace app\console;

use Throwable;
use think\Request;
use think\Response;
use think\facade\Env;
use think\exception\Handle;
use core\exceptions\ApiException;
use core\exceptions\AuthException;
use think\db\exception\DbException;
use think\exception\ValidateException;

class ApiExceptionHandle extends Handle
{

    /**
     * 记录异常信息（包括日志或者其它方式记录）
     * @return void
     * @param Throwable $exception
     */
    public function report(Throwable $exception): void
    {
        // 使用内置的方式记录异常日志
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     * @return Response
     * @param Throwable $e
     * @param Request $request
     */
    public function render($request, Throwable $e): Response
    {
        // 添加自定义异常处理机制
        if ($e instanceof DbException) {
            return app('json')->fail('数据获取失败', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ]);
        } else if ($e instanceof AuthException || $e instanceof ApiException || $e instanceof ValidateException) {
            return app('json')->fail($e->getMessage());
        } else {
            return app('json')->fail('未知错误', Env::get('app_debug', false) ? [
                'file' => $e->getFile(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTrace(),
                'message' => $e->getMessage(),
                'previous' => $e->getPrevious()
            ] : []);
        }
    }

}
