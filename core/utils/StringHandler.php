<?php
declare (strict_types = 1);
namespace core\utils;

class StringHandler
{
    /**
     * 特定字符串截取
     * @return string
     * @param string $str 字符串
     * @param string $needle 节点
     * @param bool|null $before_needle 截取位置
     */
    function strNeedleExtract(string $str, string $needle, ?bool $before_needle = true): string
    {
        return stristr($str, $needle, $before_needle);
    }
}