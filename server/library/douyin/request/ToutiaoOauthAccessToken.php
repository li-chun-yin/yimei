<?php
namespace library\douyin\request;

/**
 * 换取应用授权令牌
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月3日
 */
class ToutiaoOauthAccessToken extends OauthAccessToken
{
    public function __construct()
    {
        $this->setGateway('https://open.snssdk.com/');
    }
}