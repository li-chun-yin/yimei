<?php
namespace api\store\parameter\v1_0\video\sync;

use asbamboo\api\apiStore\ApiResponseParams;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2020年6月11日
 */
class SyncResponse extends ApiResponseParams
{
    /**
     * @desc 固定值
     * @example success
     * @var string
     */
    protected $status = "success";
}
