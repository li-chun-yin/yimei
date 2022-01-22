<?php
namespace api\store\parameter\v1_0\douyin\oauth;

use asbamboo\api\apiStore\ApiResponseRedirectParams;
use asbamboo\router\RouterInterface;

/**
 * 用户授权接口
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年12月5日
 */
class OauthResponse extends ApiResponseRedirectParams
{
    private $client_key;
    
    /**
     * 整个授权流程后需要返回的前端h5界面
     * @var string
     */
    private $redirect_url;
    
    public function __construct(RouterInterface $Router, string $client_key, string $redirect_url)
    {
        $this->Router       = $Router;
        $this->client_key   = $client_key;
        $this->redirect_url = $redirect_url;
    }
    
    public function getRedirectUri() : string
    {
        return "https://open.douyin.com/platform/oauth/connect/";
    }
    
    public function getRedirectResponseData() : array
    {
        return [
            'client_key'    => $this->client_key,
            'response_type' => 'code',
            'scope'         => 'user_info,video.create,video.data,video.list,poi.search',
//             'optionalScope' => '',
            'redirect_uri'  => $this->Router->generateAbsoluteUrl('douyin_code'),
            'state'         => $this->redirect_url,
        ];
    }
    
    /**
     * 默认采用表单提交的方式实现页面跳转的响应
     *
     * @return string
     */
    protected function getRedirectType() : string
    {
        return self::REDIRECT_TYPE_GET_REQUEST;
    }
}