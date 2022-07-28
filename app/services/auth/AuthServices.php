<?php
declare (strict_types = 1);
namespace app\services\auth;

use app\dao\auth\AuthDao;
use app\services\BaseServices;
use core\exceptions\ApiException;

/**
 * @method \think\Collection queryMenu(string $ids) 查询用户菜单
 */
class AuthServices extends BaseServices
{
    /**
     * @param AuthDao $dao
     */
    public function __construct(AuthDao $dao)
    {
        $this->dao = $dao;
    }

    public function saveMenu(array $data, string $message): void
    {
        $id = $data['id'] ?? 0;
        unset($data['id']); // 释放$data中的id
        $this->transaction(function () use ($id, $data, $message) {
            $res = $id ? $this->dao->updateOne($id, $data, 'id') : $this->dao->saveOne($data);
            !$res && throw new ApiException($message . '菜单失败');
        });
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
                $fullPath = '/';
                $val['plocale'] = $plocale;
                $pname && $val['pname'] = $pname;
                $ids = explode('-', $val['paths']);
                foreach ($ids as $id) {
                    if (!$id) {
                        $fullPath .= $val['name'];
                    } else {
                        $fullPath .=  $this->dao->value(['id' => $id], 'name') . '/';
                    }
                }
                /* 如果是顶级菜单，直接返回；如果是二级及以上，则拼接当前的name */
                $val['path'] = !$pid ? $fullPath : $fullPath . $val['name'];
                $children = self::getTreeMenu($data, $val['id'], $pname ? $val['name'] : null, $val['locale']);
                $children && $val['children'] = $children;
                $tree[] = $val;
            }
        }
        return $tree;
    }
}
