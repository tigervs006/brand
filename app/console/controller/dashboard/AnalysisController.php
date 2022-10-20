<?php
/*
 * +---------------------------------------------------------------------------------
 * | Author: Kevin
 * +---------------------------------------------------------------------------------
 * | Email: Kevin@tigervs.com
 * +---------------------------------------------------------------------------------
 * | Copyright (c) Shenzhen Ruhu Technology Co., Ltd. 2018-2022. All rights reserved.
 * +---------------------------------------------------------------------------------
 */

declare (strict_types = 1);
namespace app\console\controller\dashboard;

use think\response\Json;
use core\basic\BaseController;

class AnalysisController extends BaseController
{
    public function index(): Json
    {
        $analys = '{"visitData":[{"x":"2022-05-28","y":7},{"x":"2022-05-29","y":5},{"x":"2022-05-30","y":4},{"x":"2022-05-31","y":2},{"x":"2022-06-01","y":4},{"x":"2022-06-02","y":7},{"x":"2022-06-03","y":5},{"x":"2022-06-04","y":6},{"x":"2022-06-05","y":5},{"x":"2022-06-06","y":9},{"x":"2022-06-07","y":6},{"x":"2022-06-08","y":3},{"x":"2022-06-09","y":1},{"x":"2022-06-10","y":5},{"x":"2022-06-11","y":3},{"x":"2022-06-12","y":6},{"x":"2022-06-13","y":5}],"visitData2":[{"x":"2022-05-28","y":1},{"x":"2022-05-29","y":6},{"x":"2022-05-30","y":4},{"x":"2022-05-31","y":8},{"x":"2022-06-01","y":3},{"x":"2022-06-02","y":7},{"x":"2022-06-03","y":2}],"salesData":[{"x":"1月","y":1157},{"x":"2月","y":655},{"x":"3月","y":845},{"x":"4月","y":463},{"x":"5月","y":812},{"x":"6月","y":1197},{"x":"7月","y":1031},{"x":"8月","y":1110},{"x":"9月","y":654},{"x":"10月","y":805},{"x":"11月","y":891},{"x":"12月","y":454}],"searchData":[{"index":1,"keyword":"搜索关键词-0","count":315,"range":70,"status":1},{"index":2,"keyword":"搜索关键词-1","count":153,"range":14,"status":0},{"index":3,"keyword":"搜索关键词-2","count":818,"range":0,"status":1},{"index":4,"keyword":"搜索关键词-3","count":157,"range":65,"status":1},{"index":5,"keyword":"搜索关键词-4","count":7,"range":5,"status":0},{"index":6,"keyword":"搜索关键词-5","count":77,"range":38,"status":0},{"index":7,"keyword":"搜索关键词-6","count":966,"range":27,"status":1},{"index":8,"keyword":"搜索关键词-7","count":858,"range":74,"status":0},{"index":9,"keyword":"搜索关键词-8","count":200,"range":25,"status":0},{"index":10,"keyword":"搜索关键词-9","count":481,"range":40,"status":0},{"index":11,"keyword":"搜索关键词-10","count":263,"range":27,"status":1},{"index":12,"keyword":"搜索关键词-11","count":27,"range":57,"status":1},{"index":13,"keyword":"搜索关键词-12","count":195,"range":7,"status":0},{"index":14,"keyword":"搜索关键词-13","count":692,"range":31,"status":1},{"index":15,"keyword":"搜索关键词-14","count":457,"range":4,"status":1},{"index":16,"keyword":"搜索关键词-15","count":208,"range":5,"status":1},{"index":17,"keyword":"搜索关键词-16","count":759,"range":51,"status":1},{"index":18,"keyword":"搜索关键词-17","count":418,"range":13,"status":1},{"index":19,"keyword":"搜索关键词-18","count":558,"range":99,"status":0},{"index":20,"keyword":"搜索关键词-19","count":220,"range":43,"status":0},{"index":21,"keyword":"搜索关键词-20","count":515,"range":19,"status":0},{"index":22,"keyword":"搜索关键词-21","count":875,"range":29,"status":0},{"index":23,"keyword":"搜索关键词-22","count":815,"range":33,"status":1},{"index":24,"keyword":"搜索关键词-23","count":662,"range":52,"status":1},{"index":25,"keyword":"搜索关键词-24","count":768,"range":1,"status":1},{"index":26,"keyword":"搜索关键词-25","count":86,"range":43,"status":1},{"index":27,"keyword":"搜索关键词-26","count":702,"range":24,"status":1},{"index":28,"keyword":"搜索关键词-27","count":773,"range":85,"status":1},{"index":29,"keyword":"搜索关键词-28","count":246,"range":51,"status":1},{"index":30,"keyword":"搜索关键词-29","count":119,"range":53,"status":0},{"index":31,"keyword":"搜索关键词-30","count":675,"range":92,"status":0},{"index":32,"keyword":"搜索关键词-31","count":548,"range":44,"status":1},{"index":33,"keyword":"搜索关键词-32","count":731,"range":84,"status":0},{"index":34,"keyword":"搜索关键词-33","count":239,"range":98,"status":0},{"index":35,"keyword":"搜索关键词-34","count":534,"range":62,"status":1},{"index":36,"keyword":"搜索关键词-35","count":845,"range":11,"status":1},{"index":37,"keyword":"搜索关键词-36","count":677,"range":45,"status":0},{"index":38,"keyword":"搜索关键词-37","count":541,"range":91,"status":1},{"index":39,"keyword":"搜索关键词-38","count":554,"range":99,"status":1},{"index":40,"keyword":"搜索关键词-39","count":886,"range":4,"status":1},{"index":41,"keyword":"搜索关键词-40","count":300,"range":59,"status":1},{"index":42,"keyword":"搜索关键词-41","count":831,"range":66,"status":1},{"index":43,"keyword":"搜索关键词-42","count":786,"range":52,"status":1},{"index":44,"keyword":"搜索关键词-43","count":708,"range":54,"status":1},{"index":45,"keyword":"搜索关键词-44","count":505,"range":92,"status":0},{"index":46,"keyword":"搜索关键词-45","count":418,"range":34,"status":1},{"index":47,"keyword":"搜索关键词-46","count":226,"range":61,"status":1},{"index":48,"keyword":"搜索关键词-47","count":74,"range":95,"status":1},{"index":49,"keyword":"搜索关键词-48","count":92,"range":13,"status":0},{"index":50,"keyword":"搜索关键词-49","count":819,"range":32,"status":0}],"offlineData":[{"name":"Stores 0","cvr":0.9},{"name":"Stores 1","cvr":0.3},{"name":"Stores 2","cvr":0.7},{"name":"Stores 3","cvr":0.8},{"name":"Stores 4","cvr":0.6},{"name":"Stores 5","cvr":0.1},{"name":"Stores 6","cvr":0.2},{"name":"Stores 7","cvr":0.3},{"name":"Stores 8","cvr":0.7},{"name":"Stores 9","cvr":0.2}],"offlineChartData":[{"date":"11:51","type":"客流量","value":82},{"date":"11:51","type":"支付笔数","value":106},{"date":"12:21","type":"客流量","value":75},{"date":"12:21","type":"支付笔数","value":72},{"date":"12:51","type":"客流量","value":109},{"date":"12:51","type":"支付笔数","value":88},{"date":"13:21","type":"客流量","value":30},{"date":"13:21","type":"支付笔数","value":55},{"date":"13:51","type":"客流量","value":26},{"date":"13:51","type":"支付笔数","value":51},{"date":"14:21","type":"客流量","value":103},{"date":"14:21","type":"支付笔数","value":73},{"date":"14:51","type":"客流量","value":23},{"date":"14:51","type":"支付笔数","value":37},{"date":"15:21","type":"客流量","value":12},{"date":"15:21","type":"支付笔数","value":21},{"date":"15:51","type":"客流量","value":20},{"date":"15:51","type":"支付笔数","value":106},{"date":"16:21","type":"客流量","value":97},{"date":"16:21","type":"支付笔数","value":90},{"date":"16:51","type":"客流量","value":62},{"date":"16:51","type":"支付笔数","value":66},{"date":"17:21","type":"客流量","value":40},{"date":"17:21","type":"支付笔数","value":96},{"date":"17:51","type":"客流量","value":88},{"date":"17:51","type":"支付笔数","value":13},{"date":"18:21","type":"客流量","value":62},{"date":"18:21","type":"支付笔数","value":51},{"date":"18:51","type":"客流量","value":26},{"date":"18:51","type":"支付笔数","value":19},{"date":"19:21","type":"客流量","value":72},{"date":"19:21","type":"支付笔数","value":27},{"date":"19:51","type":"客流量","value":85},{"date":"19:51","type":"支付笔数","value":61},{"date":"20:21","type":"客流量","value":82},{"date":"20:21","type":"支付笔数","value":15},{"date":"20:51","type":"客流量","value":33},{"date":"20:51","type":"支付笔数","value":19},{"date":"21:21","type":"客流量","value":63},{"date":"21:21","type":"支付笔数","value":108}],"salesTypeData":[{"x":"家用电器","y":4544},{"x":"食用酒水","y":3321},{"x":"个护健康","y":3113},{"x":"服饰箱包","y":2341},{"x":"母婴产品","y":1231},{"x":"其他","y":1231}],"salesTypeDataOnline":[{"x":"家用电器","y":244},{"x":"食用酒水","y":321},{"x":"个护健康","y":311},{"x":"服饰箱包","y":41},{"x":"母婴产品","y":121},{"x":"其他","y":111}],"salesTypeDataOffline":[{"x":"家用电器","y":99},{"x":"食用酒水","y":188},{"x":"个护健康","y":344},{"x":"服饰箱包","y":255},{"x":"其他","y":65}],"radarData":[{"name":"个人","label":"引用","value":10},{"name":"个人","label":"口碑","value":8},{"name":"个人","label":"产量","value":4},{"name":"个人","label":"贡献","value":5},{"name":"个人","label":"热度","value":7},{"name":"团队","label":"引用","value":3},{"name":"团队","label":"口碑","value":9},{"name":"团队","label":"产量","value":6},{"name":"团队","label":"贡献","value":3},{"name":"团队","label":"热度","value":1},{"name":"部门","label":"引用","value":4},{"name":"部门","label":"口碑","value":1},{"name":"部门","label":"产量","value":6},{"name":"部门","label":"贡献","value":5},{"name":"部门","label":"热度","value":7}]}';
        return $this->json->successful(json_decode($analys, true));
    }
}
