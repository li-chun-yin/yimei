<?php
namespace api\store\parameter\v1_0\kuaishou\postConfig;

use asbamboo\api\apiStore\ApiRequestParamsAbstract;
use asbamboo\api\apiStore\traits\CommonApiRequestParamsTrait;

class PostConfigRequest extends ApiRequestParamsAbstract
{
    use CommonApiRequestParamsTrait;

    /**
     * app_id
     * @required 必须
     * @desc app_id
     * @example
     * @var string
     */
    public $app_id;

    /**
     * app_secret
     * @required 必须
     * @desc
     * @example
     * @var string
     */
    public $app_secret;
}
