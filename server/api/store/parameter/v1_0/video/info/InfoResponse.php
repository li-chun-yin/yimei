<?php
namespace api\store\parameter\v1_0\video\info;

use asbamboo\api\apiStore\ApiResponseParams;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年12月5日
 */
class InfoResponse extends ApiResponseParams
{
    /**
     * @desc Video id
     * @example
     * @var string
     */
    public $id = '';
    
    /**
     * @desc Video url
     * @example 
     * @var string
     */
    public $url = '';
    
    /**
     * @desc 
     * @example
     * @var json_array
     */
    public $syncs = [];

    /**
     * @desc
     * @example
     * @var json
     */
    public $sync_desc = [];
}