<?php
namespace api\store\handler\v1_0\toutiao;

use asbamboo\api\apiStore\ApiClassInterface;
use asbamboo\api\apiStore\ApiRequestParamsInterface;
use asbamboo\api\apiStore\ApiResponseParamsInterface;
use exception\MessageException;
use asbamboo\api\exception\ApiException;
use api\store\parameter\v1_0\toutiao\oauth\OauthResponse;
use model\setting\Manager AS SettingManager;
use model\setting\Code;
use exception\SystemException;
use asbamboo\router\RouterInterface;

/**
 * @name 今日头条账号授权
 * @desc 接口的服务端自行处理，获取授权code到access token之间的整个过程，前端调用这个接口需要使用表单提交或location跳转
 * @request api\store\parameter\v1_0\toutiao\oauth\OauthRequest
 * @response api\store\parameter\v1_0\toutiao\oauth\OauthResponse
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2021年8月27日
 */
class Oauth implements ApiClassInterface
{
    
    private $SettingManager;
    
    /**
     * @param SettingManager $SettingManager
     */
    public function __construct(SettingManager $SettingManager, RouterInterface $Router){
        $this->SettingManager   = $SettingManager;
        $this->Router           = $Router;
    }
    
    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\api\apiStore\ApiClassInterface::exec()
     */
    public function exec(ApiRequestParamsInterface $Params): ?ApiResponseParamsInterface
    {
        try {
            $DouyinSettingEntity  = $this->SettingManager->loadByType(Code::TYPE_DOUYIN);
            
            if(empty($DouyinSettingEntity->getData()['client_key'])){
                throw new SystemException('系统尚未配置抖音open api clent info');
            }
            
            return new OauthResponse(
                $this->Router,
                $DouyinSettingEntity->getData()['client_key'],
                $Params->getRedirectUrl()
            );
        }catch(MessageException $e){
            throw new ApiException($e->getMessage());
        }
    }
}
