<?php
declare (strict_types = 1);
namespace app\services\channel;

use think\Collection;
use app\services\BaseServices;
use app\dao\channel\ChannelDao;

class ChannelServices extends BaseServices
{
    public function __construct(ChannelDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @return array|Collection
     * @param array $map 条件
     * @param string|null $field 字段
     * @param array|null $order 排序
     */
    public function index(array $map, ?array $order, ?string $field): array|Collection
    {
        return $this->dao->getChananel($map, $order, $field);
    }

    /**
     * 获取栏目树状结构
     * @return array
     * @param int $pid 父栏目pid
     * @param Collection $data 栏目数据
     * @param string $plevel 所属栏目名称
     */
    public function getChildren(Collection $data, int $pid = 0, string $plevel = '顶级栏目'): array
    {
        $tree = [];
        foreach ($data as $val) {
            if ($val['pid'] == $pid) {
                $val['belongsto'] = $plevel;
                $children = self::getChildren($data, $val['id'], $val['cname']);
                $children && $val['children'] = $children;
                $tree[] = $val;
            }
        }
        return $tree;
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
