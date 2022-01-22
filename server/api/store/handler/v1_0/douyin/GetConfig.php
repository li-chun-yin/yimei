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
use model\setting\Code;
use api\store\parameter\v1_0\douyin\getConfig\GetConfigResponse;
use model\setting\Repository AS SettingRepository;

/**
 * @name 获取抖音配置
 * @desc 抖音开发配置信息
 * @request api\store\parameter\v1_0\douyin\getConfig\GetConfigRequest
 * @response api\store\parameter\v1_0\douyin\getConfig\GetConfigResponse
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2021年8月27日
 */
class GetConfig implements ApiClassInterface
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
        SettingRepository $SettingRepository
    ){
        $this->Session              = $Session;
        $this->UserToken            = $UserToken;
        $this->Db                   = $Db;
        $this->SettingRepository    = $SettingRepository;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\api\apiStore\ApiClassInterface::exec()
     */
    public function exec(ApiRequestParamsInterface $Params): ?ApiResponseParamsInterface
    {
        try {
            
            $SettingEntity  = $this->SettingRepository->findOneByType(Code::TYPE_DOUYIN);
            $data           = $SettingEntity->getData();
            
            return new GetConfigResponse([
                'client_key'        => $data['client_key'],
                'client_secret'     => $data['client_secret'],
            ]);
            
        }catch(MessageException $e){
            throw new ApiException($e->getMessage());
        }
    }
}
