<?php
namespace library\douyin\request;

/**
 * 换取头条用户授权信息
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月3日
 */
class ToutiaoOauthUserInfo extends OauthUserInfo
{
    public function __construct()
    {
        $this->setGateway('https://open.snssdk.com/');
    }
}