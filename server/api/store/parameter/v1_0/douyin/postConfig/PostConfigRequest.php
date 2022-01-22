<?php
namespace api\store\parameter\v1_0\douyin\postConfig;

use asbamboo\api\apiStore\ApiRequestParamsAbstract;
use asbamboo\api\apiStore\traits\CommonApiRequestParamsTrait;

class PostConfigRequest extends ApiRequestParamsAbstract
{
    use CommonApiRequestParamsTrait;

    /**
     * client_key
     * @required 必须
     * @desc client_key
     * @example
     * @var string
     */
    public $client_key;

    /**
     * client_secret
     * @required 必须
     * @desc
     * @example
     * @var string
     */
    public $client_secret;
}
