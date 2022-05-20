<?php
declare (strict_types = 1);

// 应用公共文件

if (!function_exists('sys_config')) {
    /**
     * 获取单个系统配置
     * @return string
     * @param string $name 配置名
     * @param string $default 默认配置名
     */
    function sys_config(string $name, string $default = ''): string
    {
        if (empty($name)) return $default;
        return app('sysConfig')->getFieldValue($name, 'name', 'value');
    }
}

if (!function_exists('strOrderFilter')) {
    /**
     * 排序字段字符串截取
     * @return string
     * @param string $str 字符串
     * @param string|null $needle 节点
     * @param bool|null $before_needle 节点前面/后面
     */
    function strOrderFilter(string $str, ?string $needle = 'end', ?bool $before_needle = true): string
    {
        return stristr($str, $needle, $before_needle);
    }
}
