<?php
namespace model\setting;

use exception\MessageException;

/**
 * 字段验证器
 *  - 确保字段时数据可写入的字段
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月28日
 */
trait Validator
{
    /**
     *
     * @throws MessageException
     */
    public function validateData(string $data, string $type) : void
    {
    }
}