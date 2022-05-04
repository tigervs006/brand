<?php
declare (strict_types = 1);
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
     * 获取子栏目信息
     * @return array
     * @param array $map 条件
     * @param string $field 字段
     * @param array|null $order 排序
     * TODO: 仅支持二级栏目，更深层级的栏目需要用到递归
     */
    public function getChildInfo(array $map, string $field, ?array $order = ['id' => 'asc']): array
    {
        $info = $this->dao->getOne($map, $field)->toArray();
        $childInfo = $this->dao->getData(['pid' => $info['id']], $order, $field)->toArray();
        return array_merge(array($info), $childInfo);
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
                $pinfo = $this->dao->getOne(array_merge(['id' => $val['pid']], $this->status), $field)->toArray();
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
}
