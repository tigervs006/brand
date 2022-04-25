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
    /**
     * 状态码
     * @var int
     */
    private int $code = 200;

    /**
     * @return $this
     * @param int $code
     */
    public function code(int $code): self
    {
        $this->code = $code;
        return $this;
    }

    /**
     * 创建Response实体
     * @return Response
     * @param int $status 状态码
     * @param string $msg 信息
     * @param array|null $data data
     * @param bool|null $success AntDesignPro
     */
    public function make(int $status, string $msg, ?array $data = null, ?bool $success = true): Response
    {
        $res = compact('status', 'msg', 'success');

        $msg && $res['msg'] = $msg;
        $res['method'] = Request::method();
        $res['path'] = Request::pathinfo();
        !is_null($data) && $res['result'] = $data;

        return Response::create($res, 'json', $this->code);
    }

    /**
     * @return Response
     * @param string|array $msg 信息
     * @param array|null $data  data
     * @param bool|null $success AntDesignPro
     */
    public function success(string|array $msg = 'ok', ?array $data = null, ?bool $success = true): Response
    {
        if (is_array($msg)) {
            $data = $msg;
            $msg = 'ok';
        }

        return $this->make(200, $msg, $data, $success);
    }

    /**
     * @param ...$args
     * @return Response
     */
    public function successful(...$args): Response
    {
        return app('json')->success(...$args);
    }

    /**
     * @return Response
     * @param string|array $msg 信息
     * @param array|null $data data
     * @param bool|null $success AntDesignPro
     */
    public function fail(string|array $msg = 'fail', ?array $data = null, ?bool $success = false): Response
    {
        if (is_array($msg)) {
            $data = $msg;
            $msg = 'ok';
        }

        return $this->make(400, $msg, $data, $success);
    }

    /**
     * @return mixed
     * @param string $status 状态码
     * @param string|array $msg 信息
     * @param array $result data
     */
    public function status(string $status, string|array $msg, array $result = []): mixed
    {
        $status = strtoupper($status);
        if (is_array($msg)) {
            $result = $msg;
            $msg = 'ok';
        }
        return app('json')->success($msg, compact('status', 'result'));
    }
}
