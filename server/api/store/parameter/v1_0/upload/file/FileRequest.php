<?php
namespace api\store\parameter\v1_0\upload\file;

use asbamboo\api\apiStore\ApiRequestParamsAbstract;
use asbamboo\api\apiStore\traits\CommonApiRequestParamsTrait;

class FileRequest extends ApiRequestParamsAbstract
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
