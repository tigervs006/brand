<?php
declare (strict_types = 1);
namespace app\dao;

use core\basic\BaseModel;
use think\helper\Str;

abstract class BaseDao
{
    /**
     * 当前表别名
     * @var string
     */
    protected string $alias;

    /**
     * join表名
     * @var string
     */
    protected string $joinAlias;

    /**
     * 默认状态
     * @var array|int[]
     */
    protected array $status = ['status' => 1];

    /**
     * 设置当前模型
     * @return string
     */
    abstract protected function setModel(): string;

    /**
     * 设置join链表模型
     */
    protected function setJoinModel(): string {
        return app()->make($this->setModel());
    }

    /**
     * 获取单条数据
     * @param int|string|array $id id
     * @param string|null $field 字段
     * @param array|null $with 关联模型
     * @return array|BaseModel|\think\Model|null
     */
    public function get(int|string|array $id, ?string $field, ?array $with = []): array|BaseModel|\think\Model|null
    {
        if (is_array($id)) {
            $map = $id;
        } else {
            $map = [$this->getPk() => $id];
        }
        return $this->getModel()->where($map)->when(count($with), function ($query) use ($with) {
            $query->with($with);
        })->field($field ?? '*')->find();
    }

    /**
     * 获取模型
     * @return BaseModel
     */
    protected function getModel(): BaseModel
    {
        return app()->make($this->setModel());
    }

    /**
     * 获取当前模型主键
     * @return string
     */
    protected function getPK(): string
    {
        return $this->getModel()->getPk();
    }

    /**
     * 自增字段
     * @return bool
     * @param int $id id
     * @param int $incValue 自增值
     * @param string|null $field 自增字段
     */
    public function setInc(int $id, int $incValue, ?string $field = 'click'): bool
    {
        $data = $this->getModel()->find($id);
        $data->$field += $incValue;
        return $data->isAutoWriteTimestamp(false)->save();
    }

    /**
     * 根据条件获取单条数据
     * @return \think\Model|array|BaseModel|null
     * @param array $map 条件
     * @param string|null $field 字段
     * @param array|null $with  关联模型
     */
    public function getOne(array $map, ?string $field, ?array $with = []): \think\Model|array|null|BaseModel
    {
        return $this->get($map, $field, $with);
    }

    /**
     * 根据条件获取所有数据
     * @return array|\think\Collection
     * @param array|null $map 条件
     * @param array|null $order 排序
     * @param string|null $field 字段
     */
    public function getData(?array $map = null, ?array $order = ['id' => 'desc'], ?string $field = '*'): array|\think\Collection
    {
        if (is_null($map)) {
            return $this->getModel()->order($order)->field($field)->select();
        } else {
            return $this->getModel()->where($map)->order($order)->field($field)->select();
        }
    }

    /**
     * 计算数据总量
     * @return int
     * @param array|null $map 条件
     * @param string|null $field 字段
     */
    public function getCount(?array $map, ?string $field = 'id'): int
    {
        if (is_null($map)) {
            return $this->getModel()->count($field);
        } else {
            return $this->getModel()->where($map)->count();
        }
    }

    /**
     * 获取某个列数组
     * @return array
     * @param array|null $map 条件
     * @param string $field 字段
     * @param string|null $key 索引
     */
    public function getColumn(string $field, ?array $map = null, ?string $key = ''): array
    {
        if (is_null($map)) {
            return $this->getModel()->column($field, $key);
        } else {
            return $this->getModel()->where($map)->column($field, $key);
        }
    }

    /**
     * 新增一条数据
     * @return BaseModel
     * @param array $data
     */
    public function saveOne(array $data): BaseModel
    {
        return $this->getModel()::create($data);
    }

    /**
     * 批量新增数据
     * @return int
     * @param array $data
     */
    public function saveAll(array $data): int
    {
        return $this->getModel()->insertAll($data);
    }

    /**
     * 删除一条或多条数据
     * @return boolean
     * @param int|array|string $id
     * @param string|null $key key
     */
    public function delete(int|array|string $id, ?string $key = null): bool
    {
        if (is_array($id)) {
            return $this->getModel()->useSoftDelete('delete_time',time())->delete($id) >= 1;
        } else {
            $where = [is_null($key) ? $this->getPk() : $key => $id];
            // FIXME: delete方法实际返回的是int类型，ThinkPHP的bug
            return $this->getModel()->where($where)->useSoftDelete('delete_time',time())->delete() >= 1;
        }
    }

    /**
     * 更新一条数据
     * @return BaseModel
     * @param array $data
     * @param string|null $key
     * @param array|int|string $id
     */
    public function updateOne(int|array|string $id, array $data, ?string $key = null): BaseModel
    {
        if (is_array($id)) {
            $where = $id;
        } else {
            $where =[is_null($key) ? $this->getPk() : $key => $id];
        }
        return $this->getModel()::update($data, $where);
    }

    /**
     * 批量更新数据
     * @return BaseModel
     * @param array $ids
     * @param array $data
     * @param string|null $key
     */
    public function batchUpdate(array $ids, array $data, ?string $key): BaseModel
    {
        return $this->getModel()->whereIn(is_null($key) ? $this->getPK() : $key, $ids)->update($data);
    }

    /**
     * 获取某个字段值
     * @return mixed
     * @param string $field 字段
     * @param string $value 键值
     * @param array|null $where 条件
     * @param string|null $valueKey 键值
     */
    public function getFieldValue(string $value, string $field, ?string $valueKey, ?array $where = []): mixed
    {
        return $this->getModel()->getFieldValue($value, $field, $valueKey, $where);
    }

    /**
     * @return array[]
     * 获取搜索器和搜索条件key
     * @param array $withSearch
     * @throws \ReflectionException
     */
    private function getSearchData(array $withSearch): array
    {
        $with = [];
        $mapKey = [];
        $respones = new \ReflectionClass($this->setModel());
        foreach ($withSearch as $fieldName) {
            $method = 'search' . Str::studly($fieldName) . 'Attr';
            if ($respones->hasMethod($method)) {
                $with[] = $fieldName;
            } else {
                $mapKey[] = $fieldName;
            }
        }
        return [$with, $mapKey];
    }

    /**
     * @return mixed
     * 根据搜索器获取搜索内容
     * @param array $withSearch
     * @param array|null $data
     * @throws \ReflectionException
     */
    protected function withSearchSelect(array $withSearch, ?array $data = []): mixed
    {
        [$with] = $this->getSearchData($withSearch);
        return $this->getModel()->withSearch($with, $data);
    }

    /**
     * 使用搜索器
     * @return mixed
     * @param array|null $map 条件
     */
    public function search(?array $map): mixed
    {
        if (is_null($map)) {
            return $this->getModel();
        } else {
            return $this->withSearchSelect(array_keys($map), $map);
        }
    }

    /**
     * 获取带分页或者有关联模型的列表
     * @return array|\think\Collection
     * @param int $current 当前页
     * @param int $pageSize 容量
     * @param array|null $map 条件
     * @param string|null $field 字段
     * @param array|null $order 排序
     * @param array|null $with  关联模型
     */
    public function getList(int $current, int $pageSize, ?array $map, ?string $field, ?array $order = ['id' => 'desc'], ?array $with = []): array|\think\Collection
    {
        if (is_null($map)) {
            return $this->getModel()->when(count($with), function ($query) use ($with) { $query->with($with); })->field($field ?? '*')->order($order)->page($current, $pageSize)->select();
        } else {
            return $this->getModel()->where($map)->when(count($with), function ($query) use ($with) { $query->with($with); })->field($field ?? '*')->order($order)->page($current, $pageSize)->select();
        }
    }
}
