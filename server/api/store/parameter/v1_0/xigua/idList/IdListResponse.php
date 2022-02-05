<?php
namespace api\store\parameter\v1_0\xigua\idList;

use asbamboo\api\apiStore\ApiResponseParams;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年12月5日
 */
class IdListResponse extends ApiResponseParams
{
    /**
     * @desc douyinId[]
     * @example $items
     * @var array
     */
    public $items = [];
    
    /**
     * 
     * @var number
     */
    public $total = 0;
}