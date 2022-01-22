<?php
namespace api\store\parameter\v1_0\video\create;

use asbamboo\api\apiStore\ApiRequestParamsAbstract;
use asbamboo\api\apiStore\traits\CommonApiRequestParamsTrait;

class CreateRequest extends ApiRequestParamsAbstract
{
    use CommonApiRequestParamsTrait;
    
    /**
     * @desc 文件
     * @required 必填
     * @example
     * @var file
     */
    public $file;    
}
