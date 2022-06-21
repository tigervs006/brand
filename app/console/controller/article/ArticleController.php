<?php
declare (strict_types = 1);
namespace app\console\controller\article;

use think\response\Json;
use core\basic\BaseController;
use core\exceptions\ApiException;
use core\utils\StringHandler as Str;
use think\exception\ValidateException;
use app\services\article\ArticleServices;

class ArticleController extends BaseController
{
    private ArticleServices $services;

    /**
     * 验证器
     * @var string
     */
    private string $validator = 'app\console\validate\ArticleValidator.save';

    protected function initialize()
    {
        parent::initialize();
        $this->services = $this->app->make(ArticleServices::class);
    }

    /**
     * 获取文章
     * @return Json
     */
    final public function index(): Json
    {
        $info = $this->services->getOne(['id' => $this->id], null, ['content']);
        if (null === $info) {
            return $this->json->fail('There is nothing...');
        } else {
            return $this->json->successful(compact('info'));
        }
    }

    /**
     * 删除文章
     * @return Json
     */
    final public function delete(): Json
    {
        $this->services->remove($this->id);
        return $this->json->successful('删除成功');
    }

    /**
     * 新增|编辑
     * @return Json
     */
    final public function save(): Json
    {
        $data = $this->request->post(
            [
                'id',
                'cid',
                'status',
                'author',
                'title',
                'keywords',
                'description',
                'content',
                'litpic',
                'author',
                'is_head',
                'is_recom',
                'is_litpic'
            ], null, 'trim'
        );
        // 验证必要数据
        try {
            $this->validate($data, $this->validator);
        } catch (ValidateException $e) {
            throw new ApiException($e->getError());
        }
        // 处理特殊符号
        $data['keywords'] = Str::strSymbol($data['keywords']);
        15 < count(explode(',', $data['keywords']))
        && throw new ApiException('网站关键词不得超过15个，否则会被搜索引擎判断为堆砌关键词而K站');
        if (isset($data['id'])) {
            $message = '编辑';
        } else {
            $message = '新增';
            $data['click'] = mt_rand(246, 579);
        }
        // 只有一篇是头条
        if (isset($data['is_head']) && $data['is_head']) {
            $aid = $this->services->getFieldValue($data['is_head'], 'is_head', 'id');
            $aid && $this->services->updateOne($aid, ['is_head' => 0], 'id');
        }
        $this->services->saveArticle($data, $message);
        return $this->json->successful($message . '文章成功');
    }

    /**
     * 文章列表
     * @return Json
     * FIXME: 组装的搜索条件不兼容$map内的其它搜索条件
     */
    final public function lists(): Json
    {
        // 获取时间范围
        $dateRange = $this->request->only(['startTime', 'endTime'], 'get');
        // 获取搜索标题
        $title = $this->request->get('title/s', null, 'trim');
        // 需提取的字段
        $field = 'id, cid, click, title, author, status, create_time, update_time, is_head, is_recom, is_collect';
        // 获取排序字段
        $order = $this->request->only(['click', 'create_time', 'update_time'], 'get', 'strOrderFilter');
        // 排除字段后获得map
        $map = $this->request->except(['title', 'click', 'current', 'pageSize', 'startTime', 'endTime', 'create_time', 'update_time'], 'get');
        // 组装按时间段搜索条件
        $dateRange && $map[] = ['create_time', 'between time', [$dateRange['startTime'], $dateRange['endTime']]];
        // 组装文章标题搜索条件
        $title && $map[] = ['title', 'like', '%' . $title . '%'];
        // 提取文章列表
        $list = $this->services->getList($this->current, $this->pageSize, $map ?: null, $field, $order ?: $this->order, ['channel']);
        if ($list->isEmpty()) {
            return $this->json->fail('There is nothing...');
        } else {
            // 提取数据总数
            $total = $this->services->getCount($map ?: null);
            return $this->json->successful(compact('total', 'list'));
        }
    }

    /**
     * 获取作者
     * @return Json
     */
    final public function getAuthor(): Json
    {
        /** @var \app\services\user\UserServices $userServices */
        $userServices = $this->app->make(\app\services\user\UserServices::class);
        // 获取系统用户作为文章作者
        $list = $userServices->getData($this->status, $this->order, 'name, cname');
        if ($list->isEmpty()) {
            return $this->json->fail('There is nothing...');
        } else {
            return $this->json->successful(compact('list'));
        }
    }

    /**
     * 新闻栏目
     * @return Json
     */
    final public function getChannel(): Json
    {
        /** @var \app\services\channel\ChannelServices $channelServices */
        $channelServices = $this->app->make(\app\services\channel\ChannelServices::class);
        $list = $channelServices->getChildInfo(['id' => 4], 'id, name, cname');
        if (empty($list)) {
            return $this->json->fail('There is nothing...');
        } else {
            return $this->json->successful(compact('list'));
        }
    }

    /**
     * 文章状态
     * @return Json
     */
    final public function setStatus(): Json
    {
        $data = $this->request->post(['status']);
        $message = $data['status'] ? '显示' : '隐藏';
        $this->services->updateOne($this->id, $data);
        return $this->json->successful($message . '文章成功');
    }
}
