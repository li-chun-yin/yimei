<?php
namespace api\store\parameter\v1_0\amap\placeAround;

use asbamboo\api\apiStore\ApiRequestParamsAbstract;
use asbamboo\api\apiStore\traits\CommonApiRequestParamsTrait;

class PlaceAroundRequest extends ApiRequestParamsAbstract
{
    use CommonApiRequestParamsTrait;

    /**
     * @desc keywords
     * @required 可选
     * @example 
     * @var string
     */
    public $keywords;
    
    /**
     * @desc 纬度
     * @required 可选
     * @example 35.86166
     * @var number
     */
    public $latitude;
    
    /**
     * @desc 经度
     * @required 可选
     * @example 104.195397
     * @var number
     */
    public $longitude;
}
