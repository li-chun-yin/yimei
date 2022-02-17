<?php
namespace api\store\handler\v1_0\kuaishou;

use asbamboo\api\apiStore\ApiClassInterface;
use asbamboo\api\apiStore\ApiRequestParamsInterface;
use asbamboo\api\apiStore\ApiResponseParamsInterface;
use exception\MessageException;
use asbamboo\api\exception\ApiException;
use asbamboo\database\FactoryInterface AS DbFactoryInterface;
use model\setting\Manager AS SettingManager;
use model\setting\Code;
use exception\NotFoundSettingException;
use api\store\parameter\v1_0\kuaishou\postConfig\PostConfigResponse;

/**
 * @name 修改快手配置
 * @desc 修改快手开发配置信息
 * @request api\store\parameter\v1_0\kuaishou\postConfig\PostConfigRequest
 * @response api\store\parameter\v1_0\kuaishou\postConfig\PostConfigResponse
 *
 * @author 李春寅<licy2013@aliyun.com>
 * @since 2022年2月17日
 */
class PostConfig implements ApiClassInterface
{

    /**
     *
     * @param DbFactoryInterface $Db
     */
    public function __construct(
        DbFactoryInterface $Db,
        SettingManager $SettingManager
    ){
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
                $this->SettingManager->loadByType(Code::TYPE_KUAISHOU);
                $this->SettingManager->updateByKuaishou([
                    'app_id'        => $Params->getAppId(),
                    'app_secret'    => $Params->getAppSecret(),
                ]);
            } catch (NotFoundSettingException $e){
                $this->SettingManager->load();
                $this->SettingManager->createByKuaishou([
                    'app_id'        => $Params->getAppId(),
                    'app_secret'    => $Params->getAppSecret(),
                ]);
            }
            $this->Db->getManager()->flush();

            return new PostConfigResponse();
        }catch(MessageException $e){
            throw new ApiException($e->getMessage());
        }
    }
}
