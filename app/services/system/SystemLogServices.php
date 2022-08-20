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
     */
    public function actionLogRecord($request, string $token): void
    {
        $method = $request->rule()->getMethod();
        $options = $request->rule()->getOption();
        $tokenInfo = $this->jwtServices->parseToken($token);
        $this->dao->saveOne([
            'gid' => $tokenInfo['gid'],
            'user' => $tokenInfo['aud'],
            'method' => strtoupper($method),
            'action' => $options['route_name'],
            'level' => 'get' === $method ? 2 : 3,
            'path' => $request->rule()->getRule(),
            'ipaddress' => ip2long($request->ip()),
        ]);
    }
}
