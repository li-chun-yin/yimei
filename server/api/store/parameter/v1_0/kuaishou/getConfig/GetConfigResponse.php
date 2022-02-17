<?php
namespace api\store\parameter\v1_0\kuaishou\getConfig;

use asbamboo\api\apiStore\ApiResponseParams;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年12月5日
 */
class GetConfigResponse extends ApiResponseParams
{
    /**
     * @desc app_id
     * @example 5465456465
     * @var string
     */
    protected $app_id = "";

    /**
     * @desc app_secret
     * @example 5465456465
     * @var string
     */
    protected $app_secret = "";
}