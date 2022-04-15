<?php

namespace app\services\channel;

use app\services\BaseServices;
use app\dao\channel\ChannelDao;

class ChannelServices extends BaseServices
{
    public function __construct(ChannelDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * 获取父栏目信息
     * @return array
     * @param array $info 栏目信息
     * @param string $field 栏目字段
     */
    public function getParentInfo(array $info, string $field): array
    {
        static $infoArr = [];
        foreach ($info as $val) {
            if ($val['pid']) {
                // 查找父级栏目
                $pinfo = $this->dao->getOne(['id' => $val['pid'], 'status' => 1], $field)->toArray();
                $pinfo['path'] = ''; // 生成path键值用于组合栏目url时用
                $pinfo && self::getParentInfo(array($pinfo), $field);
                $pinfo && $infoArr[] = $pinfo;
            }
        }
        // 父级栏目可能是顶级栏目没有pid，需合并
        return array_merge($infoArr, $info);
    }

    /**
     * 整理组合栏目URL
     * @return array
     * @param array $data 数据
     * @param int $pid 父栏目id
     */
    public function getParentCrumbs(array $data, int $pid = 0): array
    {
        static $arrPath = [];
        static $separation = '/';
        foreach ($data as $val) {
            if ($val['pid'] == $pid) {
                $val['path'] = $separation .= $val['name'] . '/';
                self::getParentCrumbs($data, $val['id']);
                $arrPath[] = $val;
            }
        }
        // 根据pid实现升序排序
        array_multisort(array_column($arrPath, 'pid'), SORT_ASC, $arrPath);
        return $arrPath;
    }

    /**
     * 删除单个/批量栏目
     * @return array
     * @param int $id 栏目id
     * @param array $data 栏目信息
     */
    public function getChildrenId(array $data, int $id): array
    {
        static $idsArr = [];
        foreach ($data as $val) {
            if ($id == $val['pid']) {
                $idsArr[] = $val['id'];
                self::getChildrenId($data, $val['id']);
            }
        }
        return $idsArr;
    }

    /**
     * 获取栏目信息
     * @return mixed
     * @param string $key key
     * @param string $field 字段名
     * @param int|string $value 字段值
     */
    public function getChannelInfo(string $key, int|string $value, string $field): mixed
    {
        return  $this->dao->getOne([$key => $value, 'status' => 1], $field);
    }
}
