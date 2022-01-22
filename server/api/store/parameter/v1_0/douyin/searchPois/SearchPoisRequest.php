<?php
namespace api\store\parameter\v1_0\douyin\searchPois;

use asbamboo\api\apiStore\ApiRequestParamsAbstract;
use asbamboo\api\apiStore\traits\CommonApiRequestParamsTrait;

class SearchPoisRequest extends ApiRequestParamsAbstract
{
    use CommonApiRequestParamsTrait;
    
    /**
     * @desc 分页游标, 第一页请求cursor是0,
     * @required 可选
     * @example 0
     * @var string
     */
    public $cursor = 0;
    
    /**
     * @desc 每页显示数量
     * @required 可选
     * @example 10
     * @var string
     */
    public $count = 20;

    /**
     * @desc 查询关键字，例如美食
     * @required 可选
     * @example 美食
     * @var string
     */
    public $keyword = '附近';

    /**
     * @desc 查询城市，例如上海、北京
     * @required 可选
     * @example 上海
     * @var string
     */
    public $city = '';
}
