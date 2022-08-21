<?php
declare (strict_types = 1);
namespace app\services\system;

use core\utils\JwtAuth;
use app\services\BaseServices;
use app\dao\system\SystemLogDao;

class SystemLogServices extends BaseServices
{
    private JwtAuth $jwtServices;

    public function __construct(JwtAuth $jwtAuth, SystemLogDao $dao)
    {
        $this->dao = $dao;
        $this->jwtServices = $jwtAuth;
    }

    /**
     * 记录操作日志
     * @return void
     * @param $request
     * @param string $token
     * @param null|int $level 日志级别
     * @param null|string $action 操作描述
     */
    public function actionLogRecord($request, string $token, int $level = null, string $action = null): void
    {
        $method = $request->rule()->getMethod();
        $options = $request->rule()->getOption();
        $tokenInfo = $this->jwtServices->parseToken($token);
        /* 只记录post方式的日志 */
        'post' === $method && $this->dao->saveOne([
            'level' => $level ?: 3,
            'uid' => $tokenInfo['uid'],
            'gid' => $tokenInfo['gid'],
            'path' => $request->rule()->getRule(),
            'ipaddress' => ip2long($request->ip()),
            'action' => $action ?: $options['route_name'],
        ]);
    }
}
