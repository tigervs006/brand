<?php
declare (strict_types = 1);
namespace app\services\auth;

use app\dao\auth\AuthDao;
use app\services\BaseServices;

class AuthServices extends BaseServices
{
    /**
     * @param AuthDao $dao
     */
    public function __construct(AuthDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * 生成菜单树状结构
     * todo: 需做缓存
     * @return array
     * @param int|null $pid 父级id
     * @param string|null $pname 父级名称
     * @param array|\think\Collection $data data
     */
    public function getTreeMenu(array|\think\Collection $data, ?int $pid = 0, ?string $pname = '顶级菜单', ?string $plocale = 'menu.top'): array
    {
        $tree = [];
        foreach ($data as $val) {
            if ($pid == $val['pid']) {
                $xxx = '/';
                $val['plocale'] = $plocale;
                $pname && $val['pname'] = $pname;
                $ids = explode('-', $val['parent']);
                foreach ($ids as $id) {
                    if (!$id) {
                        $xxx .= $val['name'];
                    } else {
                        $xxx .=  $this->dao->getFieldValue($id, 'id', 'name') . '/';
                    }
                }
                $val['url'] = $xxx;
                $children = self::getTreeMenu($data, $val['id'], $pname ? $val['name'] : null, $val['locale']);
                $children && $val['children'] = $children;
                $tree[] = $val;
            }
        }
        return $tree;
    }
}
