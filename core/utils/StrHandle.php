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

namespace core\utils;

/**
 * 字符串操作帮助类
 * Class StrHandle
 * @package crmeb\utils
 */
class StrHandle
{
    /**
     * @return string
     * @param $route
     * @param string $action
     * @param string $module
     * @param string $controller
     */
    public static function getAuthName(string $action, string $controller, string $module, $route): string
    {
        return strtolower($module . '/' . $controller . '/' . $action . '/' . self::paramStr($route));
    }

    /**
     * @param $params
     * @return string
     */
    public static function paramStr($params): string
    {
        if (!is_array($params)) $params = json_decode($params, true) ?: [];
        $p = [];
        foreach ($params as $key => $param) {
            $p[] = $key;
            $p[] = $param;
        }
        return implode('/', $p);
    }

    /**
     * 截取中文指定字节
     * @return string
     * @param string $str
     * @param int $utf8len
     * @param string $file
     * @param string $chaet
     */
    public static function substrUTf8(string $str, int $utf8len = 100, string $chaet = 'UTF-8', string $file = '....'): string
    {
        if (mb_strlen($str, $chaet) > $utf8len) {
            $str = mb_substr($str, 0, $utf8len, $chaet) . $file;
        }
        return $str;
    }
}
