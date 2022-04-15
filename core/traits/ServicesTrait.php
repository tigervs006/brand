<?php
namespace core\traits;

use think\Model;

/**
 * Trait ServicesTrait
 * @package core\traits
 * @method int saveAll(array $data) 批量新增数据
 * @method int getCount(?string $field = 'id') 计算数据总量
 * @method \core\basic\BaseModel saveOne(array $data) 新增一条数据
 * @method mixed getOne(array $map, ?string $field = '*') 根据条件获取单条数据
 * @method boolean delete(int|array $id, ?string $key = null) 删除一条或多条数据
 * @method \core\basic\BaseModel batchUpdate(array $ids, array $data, ?string $key) 批量更新数据
 * @method array|\think\Collection getData(array $map, ?array $order, ?string $field = '*') 根据条件获取数据
 * @method \core\basic\BaseModel updateOne(int|array|string $id, array $data, ?string $key = null) 更新一条数据
 */
trait ServicesTrait
{

}
