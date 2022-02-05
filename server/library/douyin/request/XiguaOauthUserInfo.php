<?php
namespace library\douyin\request;

/**
 * 换取头条用户授权信息
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月3日
 */
class XiguaOauthUserInfo extends OauthUserInfo
{
    public function __construct()
    {
        $this->setGateway('https://open-api.ixigua.com/');
    }
}