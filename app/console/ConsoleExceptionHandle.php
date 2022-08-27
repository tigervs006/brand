<?php
declare (strict_types = 1);
namespace app\console;

use Throwable;
use think\Request;
use think\Response;
use think\facade\Log;
use think\exception\Handle;
use core\exceptions\ApiException;
use core\exceptions\AuthException;
use think\db\exception\DbException;
use think\exception\ValidateException;

class ConsoleExceptionHandle extends Handle
{

    /**
     * 记录异常信息（包括日志或者其它方式记录）
     * @return void
     * @param Throwable $exception
     */
    public function report(Throwable $exception): void
    {
        $data = [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'code' => $this->getCode($exception),
            'message' => $this->getMessage($exception),
        ];

        //日志内容
        $log = [
            request()->tokenInfo()['aud'],
            request()->ip(),
            ceil(msectime() - (request()->time(true) * 1000)),
            strtoupper(request()->rule()->getMethod()),
            app('http')->getName() . '/' . request()->rule()->getRule(),
            json_encode(request()->param(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),

        ];
        Log::write(implode("|", $log), "error");
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
            return app('json')->fail($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ]);
        } else if ($e instanceof AuthException || $e instanceof ApiException || $e instanceof ValidateException) {
            return app('json')->fail($e->getMessage(), $e->getCode());
        } else {
            return app('json')->fail($e->getMessage(), 400, config('index.app_debug') ? [
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
