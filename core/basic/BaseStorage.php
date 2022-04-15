<?php

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
