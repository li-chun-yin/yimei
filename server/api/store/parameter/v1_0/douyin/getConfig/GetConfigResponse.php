<?php
namespace api\store\parameter\v1_0\douyin\getConfig;

use asbamboo\api\apiStore\ApiResponseParams;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年12月5日
 */
class GetConfigResponse extends ApiResponseParams
{
    /**
     * @desc client_key
     * @example 5465456465
     * @var string
     */
    protected $client_key = "";

    /**
     * @desc client_secret
     * @example 5465456465
     * @var string
     */
    protected $client_secret = "";
}