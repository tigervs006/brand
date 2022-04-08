<?php
declare (strict_types = 1);
namespace core\basic;

use think\App;
use think\Request;
use think\Validate;
use think\facade\View;
use app\services\channel\ChannelServices;

/**
 * 控制器基础类
 */
abstract class BaseController
{
    /**
     * Request实例
     * @var Request
     */
    protected Request $request;

    /**
     * 应用实例
     * @var App
     */
    protected App $app;

    /**
     * @var int
     */
    protected int $page;

    /**
     * 界面渲染
     * @var View
     */
    protected View $view;

    /**
     * @var object
     */
    protected object $json;

    /**
     * @var int
     */
    protected int $listRows;

    /**
     * @var int|array|string
     */
    protected int|array|string $id;

    /**
     * 是否批量验证
     * @var bool
     */
    protected bool $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected array $middleware = [];

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app, View $view)
    {
        $this->app     = $app;
        $this->view    = $view;
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {
        $this->json = App('json');
        $this->id = $this->request->param('id', 1);
        $this->page = $this->request->param('page/d', 1);
        $this->listRows = $this->request->param('listRows/d', 15);

        // 只在index应用执行
         App('http')->getName() === 'index' && $this->Channel();
    }

    /**
     * 网站栏目
     * @return void
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function Channel(): void
    {
        /** @var ChannelServices $services */
        $services = app()->make(ChannelServices::class);
        $result = $services->getChildren($services->index(['status' => 1], ['id' => 'asc', 'sort' => 'desc'], 'id, pid, name, level, cname'));
        $this->view::assign('channel', $result);
    }

    /**
     * 验证数据
     * @access protected
     * @param array $data 数据
     * @param array|string $validate 验证器名或者验证规则数组
     * @param array $message 提示信息
     * @param bool $batch 是否批量验证
     * @return bool
     */
    protected function validate(array $data, array|string $validate, array $message = [], bool $batch = false): bool
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                [$validate, $scene] = explode('.', $validate);
            }
            $class = str_contains($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v     = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }
}
