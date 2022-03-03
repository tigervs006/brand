<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2020 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

namespace core\basic;

use core\traits\ErrorTrait;

/**
 * Class BaseStorage
 * @package crmeb\basic
 */
abstract class BaseStorage
{
    use ErrorTrait;

    /**
     * 驱动名称
     * @var string
     */
    protected string $name;

    /**
     * 驱动配置文件名
     * @var string
     */
    protected string $configFile;

    /**
     * BaseStorage constructor.
     * @param string $name 驱动名
     * @param array $config 其他配置
     * @param string|null $configFile 驱动配置名
     */
    public function __construct(string $name, array $config = [], string $configFile = null)
    {
        $this->name = $name;
        $this->configFile = $configFile;
        $this->initialize($config);
    }

    /**
     * 初始化
     * @param array $config
     * @return mixed
     */
    abstract protected function initialize(array $config): mixed;

}
