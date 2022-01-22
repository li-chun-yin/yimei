<?php
namespace api\store\parameter\v1_0\video\checkSyncStatus;

use asbamboo\api\apiStore\ApiRequestParamsAbstract;
use asbamboo\api\apiStore\traits\CommonApiRequestParamsTrait;

class CheckSyncStatusRequest extends ApiRequestParamsAbstract
{
    use CommonApiRequestParamsTrait;
    
    /**
     * @desc 视频文件 upload_id
     * @required 必填
     * @example 6478ac7dc5166895a6461e0cfcb2ee5e
     * @var string
     */
    public $upload_id;
}