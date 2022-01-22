<?php
namespace api\store\parameter\v1_0\amap\ipLocation;

use asbamboo\api\apiStore\ApiRequestParamsAbstract;
use asbamboo\api\apiStore\traits\CommonApiRequestParamsTrait;

class IpLocationRequest extends ApiRequestParamsAbstract
{
    use CommonApiRequestParamsTrait;

    /**
     * @desc IP类型 值为 4 或 6，4 表示 IPv4，6 表示 IPv6
     * @required 可选
     * @example 4
     * @var number
     */
    public $type = '4';
    
    /**
     * @desc ip
     * @required 可选
     * @example 221.206.131.10
     * @var number
     */
    public $ip;
}
