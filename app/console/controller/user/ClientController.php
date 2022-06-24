<?php
declare (strict_types = 1);
namespace app\console\controller\user;

use think\response\Json;
use core\basic\BaseController;
use app\services\user\ClientServices;

class ClientController extends BaseController
{
    private ClientServices $services;

    public function initialize()
    {
        parent::initialize();
        $this->services = $this->app->make(ClientServices::class);
    }

    /**
     * 获取客户列表
     * @return Json
     * fixme: 组装的搜索条件不兼容$map内的其它搜索条件
     */
    final public function lists(): Json
    {
        /** 获取时间范围 */
        $dateRange = $this->request->only(['dateRange'], 'get', 'trim');
        /** 获取排序条件 */
        $order = $this->request->only(['create_time'], 'get', 'strOrderFilter');
        /** 获取筛选条件 */
        $map = $this->request->only(['mobile', 'source', 'username'], 'get', 'trim');
        if ($dateRange) {
            /** 组装按时间段搜索条件 */
            $map[] = ['create_time', 'between time', [$dateRange['dateRange'][0], $dateRange['dateRange'][1]]];
        }
        $list = $this->services->getList($this->current, $this->pageSize, $map ?: null, '*', $order);
        if ($list->isEmpty()) {
            return $this->json->fail('There is nothing...');
        } else {
            $total = $this->services->getCount($map ?: null);
            return $this->json->successful(compact('list', 'total'));
        }
    }
}
