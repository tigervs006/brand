<?php
declare (strict_types = 1);
namespace app\console\controller\system;

use think\facade\Cache;
use think\response\Json;
use core\basic\BaseController;
use app\services\system\DataBackupServices;

class DataBackupController extends BaseController
{
    /**
     * @var array|mixed
     */
    private mixed $tables;

    /**
     * @var DataBackupServices
     */
    private DataBackupServices $services;

    public function initialize()
    {
        parent::initialize();
        $this->tables = $this->request->post('tables');
        $this->services = $this->app->make(DataBackupServices::class);
    }

    /**
     * 读取列表
     * @return Json
     */
    final public function index(): Json
    {
        $list = $this->services->getDataList();
        return $this->json->successful(compact('list'));
    }

    /**
     * 查看表结构
     */
    final public function read(string $tablename): Json
    {
        $list = $this->services->getRead($tablename);
        return $this->json->successful(compact('list'));
    }

    /**
     * 优化数据表
     */
    final public function optimize(): Json
    {
        $res = $this->services->getDbBackup()->optimize($this->tables);
        return $res ? $this->json->successful('优化表成功') : $this->json->fail('优化表失败');
    }
    /**
     * 修复数据表
     */
    final public function repair(): Json
    {
        $res = $this->services->getDbBackup()->repair($this->tables);
        return $res ? $this->json->successful('修复表成功') : $this->json->fail('修复表失败');
    }

    /**
     * 备份数据表
     */
    final public function backup(): Json
    {
        $res = $this->services->backup($this->tables);
        return $res ? $this->json->fail('数据备份失败' . $res) : $this->json->successful('数据备份成功');
    }

    /**
     * 获取备份记录
     */
    final public function record(): Json
    {
        $list = $this->services->getBackup();
        return $this->json->successful($list);
    }

    /**
     * 删除备份记录
     */
    public function delRecord(): Json
    {
        $filename = intval(request()->post('filename'));
        $this->services->getDbBackup()->delFile($filename);
        return $this->json->successful('删除备份记录成功...');
    }

    /**
     * 恢复备份记录
     */
    public function import(): Json
    {
        $param = $this->request->only(
            [
                'part'  => 0,
                'time'  => 0,
                'start' => 0,
            ], 'post', 'intval'
        );
        $db = $this->services->getDbBackup();
        if (is_numeric($param['time']) && !$param['start']) {
            $list = $db->getFile('timeverif', $param['time']);
            if (is_array($list)) {
                Cache::set('backup_list', $list, 300);
                return $this->json->successful('初始化完成！', array('part' => 1, 'start' => 0));
            } else {
                return $this->json->fail('备份文件可能已经损坏，请检查！');
            }
        } else if (is_numeric($param['part']) && is_numeric($param['start'])) {
            $list = Cache::get('backup_list');
            $start = $db->setFile($list)->import($param['start']);
            if (false === $start) {
                return $this->json->fail('还原数据出错！');
            } elseif (0 === $start) {
                if (isset($list[++$param['part']])) {
                    $data = array('part' => $param['part'], 'start' => 0);
                    return $this->json->successful("正在还原...#{$param['part']}", $data);
                } else {
                    Cache::delete('backup_list');
                    return $this->json->successful('数据还原完成！');
                }
            } else {
                $data = array('part' => $param['part'], 'start' => $start[0]);
                if ($start[1]) {
                    $rate = floor(100 * ($start[0] / $start[1]));
                    return $this->json->successful("正在还原...#{$param['part']}({$rate}%)", $data);
                } else {
                    $data['gz'] = 1;
                    return $this->json->successful("正在还原...#{$param['part']}", $data);
                }
            }
        } else {
            return $this->json->fail('参数错误！');
        }
    }

    /**
     * 下载备份记录
     */
    public function download()
    {
        $time = intval(request()->param('time'));
        $key = $this->services->getDbBackup()->downloadFile($time, 0, true);
        return $this->json->successful(compact('key'));
    }
}
