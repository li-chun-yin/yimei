<?php
namespace api\store\parameter\v1_0\douyin\idList;

use asbamboo\api\apiStore\ApiRequestParamsAbstract;
use asbamboo\api\apiStore\traits\CommonApiRequestParamsTrait;

class IdListRequest extends ApiRequestParamsAbstract
{
    use CommonApiRequestParamsTrait;
    
    /**
     * @desc 页码
     * @required 可选
     * @example 1
     * @var string
     */
    public $page;
    
    /**
     * @desc 每页显示数量
     * @required 可选
     * @example 1
     * @var string
     */
    public $limit;
}
