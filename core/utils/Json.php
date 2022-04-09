<?php

namespace core\utils;

use think\Response;
use think\facade\Request;

/**
 * Json输出类
 * Class Json
 * @package core\utils
 */
class Json
{
    private int $code = 200;

    public function code(int $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function make(int $status, string $msg, ?array $data = null): Response
    {
        $res = compact('status', 'msg');

        $msg && $res['msg'] = $msg;
        $res['method'] = Request::method();
        $res['path'] = Request::pathinfo();
        !is_null($data) && $res['result'] = $data;

        return Response::create($res, 'json', $this->code);
    }

    public function success($msg = 'ok', ?array $data = null): Response
    {
        if (is_array($msg)) {
            $data = $msg;
            $msg = 'ok';
        }

        return $this->make(200, $msg, $data);
    }

    public function successful(...$args): Response
    {
        return app('json')->success(...$args);
    }

    public function fail($msg = 'fail', ?array $data = null): Response
    {
        if (is_array($msg)) {
            $data = $msg;
            $msg = 'ok';
        }

        return $this->make(400, $msg, $data);
    }

    public function status($status, $msg, $result = [])
    {
        $status = strtoupper($status);
        if (is_array($msg)) {
            $result = $msg;
            $msg = 'ok';
        }
        return app('json')->success($msg, compact('status', 'result'));
    }
}
