<?php
declare (strict_types = 1);
namespace app\services\channel;

use think\Collection;
use app\services\BaseServices;
use app\dao\channel\ChannelDao;
use think\db\exception\DbException;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;

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
     * @throws DbException
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     */
    public function index(array $map, ?array $order, ?string $field): array|Collection
    {
        return $this->dao->getChananel($map, $order, $field);
    }

    /**
     * 获取栏目树状结构
     * @return array
     * @param int $pid 所属栏目pid
     * @param Collection $data 数据
     * @param string $plevel 所属栏目名称
     */
    public function getChildren(Collection $data, int $pid = 0, string $plevel = '顶级栏目'): array
    {
        $tree = [];
        foreach ($data as $val) {
            if ($val['pid'] == $pid) {
                $val['belongsto'] = $plevel;
                $children = self::getChildren($data, $val['id'], $val['cname']);
                if ($children) $val['children'] = $children;
                $tree[] = $val;
            }
        }
        return $tree;
    }

    /**
     * 递归单个/批量删除栏目
     * @return array
     * @param $data
     * @param $id
     */
    public function getChildrenId($data, $id): array
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
}
