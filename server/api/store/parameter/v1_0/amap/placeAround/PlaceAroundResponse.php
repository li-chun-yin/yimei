<?php
namespace api\store\parameter\v1_0\amap\placeAround;

use asbamboo\api\apiStore\ApiResponseParams;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2020年6月11日
 */
class PlaceAroundResponse extends ApiResponseParams
{
    /**
     * @desc data[]
     * @example $items
     * @var array
     */
    public $items = [];
    
    
    /**
     * @desc 数量
     * @example 0
     * @var number
     */
    public $total = 0;
}