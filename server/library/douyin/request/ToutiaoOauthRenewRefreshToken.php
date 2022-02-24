<?php
namespace library\douyin\request;

use library\douyin\gateway\GatewayUriTrait;
use library\douyin\request\tool\BodyTrait;
use library\douyin\request\tool\CreateRequestTrait;
use library\douyin\request\tool\UriTrait;

/**
 * 换取应用授权令牌
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月3日
 */
class ToutiaoOauthRenewRefreshToken implements RequestInterface
{
    use GatewayUriTrait;
    use BodyTrait;
    use UriTrait;
    use CreateRequestTrait;

    public function __construct()
    {
        $this->setGateway('https://open.snssdk.com/');
    }

    /**
     * 接口请求的method参数的固定值
     *
     * @var string
     */
    private $path = '/oauth/renew_refresh_token/';

    /**
     *
     * {@inheritDoc}
     * @see RequestInterface::assignData()
     */
    public function assignData(array $assign_data): RequestInterface
    {
        $this->assign_data['client_key']    = $assign_data['client_key'];
        $this->assign_data['refresh_token'] = $assign_data['refresh_token'];

        return $this;
    }

    /**
     *
     * @return array|NULL
     */
    public function getAssignData() : ?array
    {
        return $this->assign_data;
    }
}