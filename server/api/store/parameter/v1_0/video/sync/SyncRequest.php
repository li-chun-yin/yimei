<?php
namespace api\store\parameter\v1_0\video\sync;

use asbamboo\api\apiStore\ApiRequestParamsAbstract;
use asbamboo\api\apiStore\traits\CommonApiRequestParamsTrait;

class SyncRequest extends ApiRequestParamsAbstract
{
    use CommonApiRequestParamsTrait;
    
    /**
     * @desc 视频文件 upload_id
     * @required 必填
     * @example
     * @var string
     */
    public $upload_id;

    /**
     * @desc 类型
     * @required 必填
     * @example
     * @var string
     */
    public $type;

    /**
     * @desc 同步的账号的唯一标识
     * @required 必填
     * @example
     * @var string
     */
    public $unikey;
    
    /**
     * @desc 同步的数据信息
     * @required 必填
     * @example
     * @var string
     */
    public $sync_request;
}