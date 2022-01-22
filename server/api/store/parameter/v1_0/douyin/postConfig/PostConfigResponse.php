<?php
namespace api\store\parameter\v1_0\douyin\postConfig;

use asbamboo\api\apiStore\ApiResponseParams;

/**
 * 交易支付接口请求响应值
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年12月5日
 */
class PostConfigResponse extends ApiResponseParams
{
    /**
     * @desc 固定值
     * @example success
     * @var string
     */
    protected $status = "success";
}