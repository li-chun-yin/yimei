<?php
namespace api\store\parameter\v1_0\upload\data;

use asbamboo\api\apiStore\ApiResponseParams;

/**
 *
 * @author 李春寅<licy2013@aliyun.com>
 * @since 2022年2月21日
 */
class DataResponse extends ApiResponseParams
{
    /**
     * @desc 文件的id
     * @example 12
     * @var int
     */
    public $id;

    /**
     * @desc 文件的url
     * @example https://xxxxx
     * @var string
     */
    public $url;
}
