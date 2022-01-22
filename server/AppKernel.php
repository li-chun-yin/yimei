<?php

use asbamboo\framework\kernel\KernelAbstract;

class AppKernel extends KernelAbstract
{
    /**
     * 配置文件路径
     *
     * @return string
     */
    public function getConfigPath(): string
    {
        return __DIR__ . '/config/config.php';
    }
    
    /**
     * 获取项目的根目录
     *
     * @return string
     */
    public function getProjectDir(): string
    {
        return __DIR__;
    }
}
