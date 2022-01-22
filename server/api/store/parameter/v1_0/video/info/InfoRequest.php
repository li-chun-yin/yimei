<?php
namespace api\store\parameter\v1_0\video\info;

use asbamboo\api\apiStore\ApiRequestParamsAbstract;
use asbamboo\api\apiStore\traits\CommonApiRequestParamsTrait;

class InfoRequest extends ApiRequestParamsAbstract
{
    use CommonApiRequestParamsTrait;
    
    /**
     * @desc Id
     * @required 必须
     * @example 1
     * @var string
     */
    public $id;
}
