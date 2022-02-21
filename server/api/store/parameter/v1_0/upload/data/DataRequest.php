<?php
namespace api\store\parameter\v1_0\upload\data;

use asbamboo\api\apiStore\ApiRequestParamsAbstract;
use asbamboo\api\apiStore\traits\CommonApiRequestParamsTrait;

/**
 *
 * @author 李春寅<licy2013@aliyun.com>
 * @since 2022年2月21日
 */
class DataRequest extends ApiRequestParamsAbstract
{
    use CommonApiRequestParamsTrait;

    /**
     * @desc 文件内容
     * @required 必填
     * @example
     * @var string
     */
    public $data;
}
