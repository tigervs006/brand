<?php
declare (strict_types = 1);
namespace app\console\controller\channel;

use think\response\Json;
use core\basic\BaseController;
use core\exceptions\ApiException;
use think\exception\ValidateException;
use app\services\channel\ChannelServices;

class ChannelController extends BaseController
{
    private ChannelServices $services;
    private string $validator = 'app\console\validate\ChannelValidator.';

    public function initialize()
    {
        parent::initialize();
        $this->services = $this->app->make(ChannelServices::class);
    }

    /**
     * 删除栏目
     * @return Json
     */
    final public function delete(): Json
    {
        $this->services->remove($this->id);
        return $this->json->successful('删除栏目成功');
    }

    /**
     * 树状结构数据
     * @return Json
     */
    final public function lists(): Json
    {
        // 适配ProFormTreeSelect的search参数
        $title = $this->request->get('keyWords/s', null, 'trim');
        $map = $this->request->get(['status'], null, 'trim');
        $title && $map[] = ['cname', 'like', '%' . $title . '%'];
        $data = $this->services->getData($map ?? null, ['id' => 'asc']);
        $list = empty($map) ? $this->services->getTreeData($data, 0, null) : $data;
        return $list ? $this->json->successful(compact('list')) : $this->json->fail('There is not thing');
    }

    /**
     * 新增/编辑栏目
     * @return Json
     */
    final public function save(): Json
    {
        $post = $this->request->post(
            [
                'id',
                'pid',
                'sort',
                'path',
                'name',
                'cname',
                'level',
                'title',
                'single',
                'banner',
                'status',
                'keywords',
                'description'
            ], null, 'trim'
        );
        // 过滤空值字段
        $data = array_filter($post, function ($val) {
            // 避免过滤0、boolean值
            return !("" === $val || null === $val);
        });
        $data['level'] = 0;
        if (!empty($data['pid']) && 0 <= $data['pid']) {
            $data['level'] = $this->services->getFieldValue($data['pid'], 'id', 'level') + 1;
        }
        // 释放由EditableProTable随机生成的字符串id
        if (!empty($data['id']) && !is_numeric($data['id'])) unset($data['id']);
        $message = empty($data['id']) ? '新增' : '编辑'; // 新增/编辑
        $scene = empty($data['single']) ? 'save' : 'single';
        // 验证必要数据
        try {
            $this->validate($data, $this->validator . $scene);
        } catch (ValidateException $e) {
            throw new ApiException($e->getError());
        }
        $this->services->saveChannel($data, $message);
        return $this->json->successful($message . '栏目成功');
    }

    /**
     * 栏目状态
     * @return Json
     */
    final public function setStatus(): Json
    {
        $data = $this->request->post(['status']);
        $message = $data['status'] ? '显示' : '隐藏';
        $this->services->updateOne($this->id, $data);
        return $this->json->successful($message . '栏目成功');
    }
}
