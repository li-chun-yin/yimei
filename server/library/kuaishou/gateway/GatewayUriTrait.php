<?php
namespace library\kuaishou\gateway;

use asbamboo\http\Uri;
use asbamboo\http\UriInterface;
use library\kuaishou\request\RequestInterface;

/**
 * 接口请求网关uri
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年10月9日
 */
trait GatewayUriTrait
{
    /**
     * 网关uri
     *
     * @var string
     */
    private $gateway_uri;

    /**
     * 设置请求网关
     *
     * @param string $uri
     * @return RequestInterface
     */
    public function setGateway(string $uri) : RequestInterface
    {
        $this->gateway_uri  = $uri;
        return $this;
    }

    /**
     * 返回请求网关
     *
     * @return string|NULL
     */
    public function getGateway() : ?UriInterface
    {
        if($this->gateway_uri == null){
            $this->gateway_uri    = 'https://open.kuaishou.com';
        }
        return new Uri($this->gateway_uri);
    }
}