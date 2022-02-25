<?php
namespace api\store\parameter\v1_0\douyin\idDisabled;

use asbamboo\api\apiStore\ApiRequestParamsAbstract;
use asbamboo\api\apiStore\traits\CommonApiRequestParamsTrait;

class IdDisabledRequest extends ApiRequestParamsAbstract
{
    use CommonApiRequestParamsTrait;

    /**
     * @desc open_id
     * @required 必须
     * @example 111111111111
     * @var string
     */
    public $open_id;

    /**
     * @desc 0 活着 1
     * @required 必须
     * @example 1
     * @var int
     */
    public $disabled;
}
