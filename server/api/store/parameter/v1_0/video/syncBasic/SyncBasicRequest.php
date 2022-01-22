<?php
namespace api\store\parameter\v1_0\video\syncBasic;

use asbamboo\api\apiStore\ApiRequestParamsAbstract;
use asbamboo\api\apiStore\traits\CommonApiRequestParamsTrait;

class SyncBasicRequest extends ApiRequestParamsAbstract
{
    use CommonApiRequestParamsTrait;
    
    /**
     * @desc 视频文件 upload_id
     * @required 必填
     * @example 6478ac7dc5166895a6461e0cfcb2ee5e
     * @var string
     */
    public $upload_id;

    /**
     * @desc 文本描述 text
     * @example 文本描述
     * @var string
     */
    public $text;

    /**
     * @desc 位置poi id 
     * @example 
     * @var string
     */
    public $poi_id;

    /**
     * @desc 位置poi name
     * @example
     * @var string
     */
    public $poi_name;

    /**
     * @desc 封面图片
     * @example
     * @var string
     */
    public $cover_image_upload_id;
}