<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2020 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

namespace app\http\middleware;

use think\Request;
use think\Response;
use think\facade\Config;
use core\interfaces\MiddlewareInterface;

/**
 * 跨域中间件
 * Class AllowOriginMiddleware
 * @package app\http\middleware
 */
class AllowOriginMiddleware implements MiddlewareInterface
{

    /**
     * 允许跨域的域名
     * @var string
     */
    protected string $cookieDomain;

    /**
     * @return Response
     * @param \Closure $next
     * @param Request $request
     */
    public function handle(Request $request, \Closure $next): Response
    {
        $this->cookieDomain = Config::get('cookie.domain', '');
        $header = Config::get('cookie.header');
        $origin = $request->header('origin');

        if ($origin && ('' == $this->cookieDomain || strpos($origin, $this->cookieDomain)))
            $header['Access-Control-Allow-Origin'] = $origin;
        if ($request->method(true) == 'OPTIONS') {
            $response = Response::create('ok')->code(200)->header($header);
        } else {
            $response = $next($request)->header($header);
        }
        return $response;
    }
}
