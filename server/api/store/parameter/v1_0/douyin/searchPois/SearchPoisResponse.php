<?php
namespace api\store\parameter\v1_0\douyin\searchPois;

use asbamboo\api\apiStore\ApiResponseParams;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年12月5日
 */
class SearchPoisResponse extends ApiResponseParams
{
    /**
     * @desc douyinId[]
     * @example []
     * @var array
     */
    public $items = [];
    
    /**
     * @desc 用于下一页请求的cursor
     * 
     * @var number
     */
    public $cursor = 0;

    /**
     * @desc 是否还有下一页
     * @example 0
     * @var bool
     */
    public $has_more = false;
}