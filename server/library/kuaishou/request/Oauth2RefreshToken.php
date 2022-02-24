<?php
namespace library\kuaishou\request;

use library\kuaishou\gateway\GatewayUriTrait;
use library\kuaishou\request\tool\BodyTrait;
use library\kuaishou\request\tool\CreateRequestTrait;
use library\kuaishou\request\tool\UriTrait;

/**
 * 换取应用授权令牌
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月3日
 */
class Oauth2RefreshToken implements RequestInterface
{
    use GatewayUriTrait;
    use BodyTrait;
    use UriTrait;
    use CreateRequestTrait;

    /**
     * 接口请求的method参数的固定值
     *
     * @var string
     */
    private $path = '/oauth2/refresh_token';

    /**
     *
     */
    public function __construct()
    {
        $this->method('GET');
    }

    /**
     *
     * {@inheritDoc}
     * @see RequestInterface::assignData()
     */
    public function assignData(array $assign_data): RequestInterface
    {
        $this->query_data['app_id']         = $assign_data['app_id'];
        $this->query_data['app_secret']     = $assign_data['app_secret'];
        $this->query_data['refresh_token']  = $assign_data['refresh_token'];
        $this->query_data['grant_type']     = $assign_data['grant_type'] ?? 'refresh_token';

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