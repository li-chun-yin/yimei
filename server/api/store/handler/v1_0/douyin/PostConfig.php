<?php
namespace api\store\handler\v1_0\douyin;

use asbamboo\api\apiStore\ApiClassInterface;
use asbamboo\api\apiStore\ApiRequestParamsInterface;
use asbamboo\api\apiStore\ApiResponseParamsInterface;
use exception\MessageException;
use asbamboo\api\exception\ApiException;
use asbamboo\session\Session;
use asbamboo\database\FactoryInterface AS DbFactoryInterface;
use asbamboo\security\user\token\UserToken;
use model\setting\Manager AS SettingManager;
use model\setting\Code;
use exception\NotFoundSettingException;
use api\store\parameter\v1_0\douyin\postConfig\PostConfigResponse;

/**
 * @name 修改抖音配置
 * @desc 修改抖音开发配置信息
 * @request api\store\parameter\v1_0\douyin\postConfig\PostConfigRequest
 * @response api\store\parameter\v1_0\douyin\postConfig\PostConfigResponse
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2021年8月27日
 */
class PostConfig implements ApiClassInterface
{

    /**
     *
     * @param Session $Session
     * @param UserToken $UserToken
     * @param DbFactoryInterface $Db
     */
    public function __construct(
        Session $Session,
        UserToken $UserToken,
        DbFactoryInterface $Db,
        SettingManager $SettingManager
    ){
        $this->Session              = $Session;
        $this->UserToken            = $UserToken;
        $this->Db                   = $Db;
        $this->SettingManager       = $SettingManager;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\api\apiStore\ApiClassInterface::exec()
     */
    public function exec(ApiRequestParamsInterface $Params): ?ApiResponseParamsInterface
    {
        try {
            try {
                $this->SettingManager->loadByType(Code::TYPE_DOUYIN);
                $this->SettingManager->updateByDouyin([
                    'client_key'    => $Params->getClientKey(),
                    'client_secret' => $Params->getClientSecret(),
                ]);
            } catch (NotFoundSettingException $e){
                $this->SettingManager->load();
                $this->SettingManager->createByDouyin([
                    'client_key'    => $Params->getClientKey(),
                    'client_secret' => $Params->getClientSecret(),
                ]);
            }
            $this->Db->getManager()->flush();
            
            return new PostConfigResponse();
        }catch(MessageException $e){
            throw new ApiException($e->getMessage());
        }
    }
}
