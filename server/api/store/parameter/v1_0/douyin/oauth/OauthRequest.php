<?php
namespace api\store\parameter\v1_0\douyin\oauth;

use asbamboo\api\apiStore\ApiRequestParamsAbstract;
use asbamboo\api\apiStore\traits\CommonApiRequestParamsTrait;

class OauthRequest extends ApiRequestParamsAbstract
{
    use CommonApiRequestParamsTrait;

    /**
     * redirect_url
     * @required 必须
     * @desc redirect_url
     * @example
     * @var string
     */
    public $redirect_url = '';
}