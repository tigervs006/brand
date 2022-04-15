<?php

namespace core\traits;

/**
 * Trait ServicesTrait
 * @package core\traits
 * @method int saveAll(array $data) 批量新增数据
 * @method \core\basic\BaseModel saveOne(array $data) 新增一条数据
 * @method int getCount(?array $map, ?string $field = 'id') 计算数据总量
 * @method bool delete(int|array $id, ?string $key = null) 删除一条或多条数据
 * @method mixed getOne(array $map, ?string $field = '*') 根据条件获取单条数据
 * @method bool setInc(int $id, int $incValue, ?string $field = 'click') 自增阅读量或其它
 * @method \core\basic\BaseModel batchUpdate(array $ids, array $data, ?string $key) 批量更新数据
 * @method array|\think\Collection getData(array $map, ?array $order, ?string $field = '*') 根据条件获取数据
 * @method \core\basic\BaseModel updateOne(int|array|string $id, array $data, ?string $key = null) 更新一条数据
 * @method mixed getFieldValue(string $value, string $field, ?string $valueKey, ?array $where = []) 获取某个字段值
 */
trait ServicesTrait
{
    /**
     * 获取树状结构ID
     * @return array
     * @param int $id id
     * @param array $data data
     */
    public function getTreeId(array $data, int $id): array
    {
        static $idArr = [];
        foreach ($data as $val) {
            if ($id == $val['pid']) {
                $idArr[] = $val['id'];
                self::getTreeId($data, $val['id']);
            }
        }
        return $idArr;
    }

    /**
     * 生成树状数据结构
     * @return array
     * @param int $pid 父级pid
     * @param string|null $plevel 父级名称
     * @param array|\think\Collection $data data
     */
    public function getTreeData(array|\think\Collection $data, ?string $plevel, int $pid = 0): array
    {
        $tree = [];
        foreach ($data as $val) {
            if ($val['pid'] == $pid) {
                $plevel && $val['belongsto'] = $plevel;
                $children = self::getTreeData($data, $plevel??null, $val['id']);
                $children && $val['children'] = $children;
                $tree[] = $val;
            }
        }
        return $tree;
    }
}