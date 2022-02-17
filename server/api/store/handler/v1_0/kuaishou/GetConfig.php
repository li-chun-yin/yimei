<?php
namespace api\store\handler\v1_0\kuaishou;

use asbamboo\api\apiStore\ApiClassInterface;
use asbamboo\api\apiStore\ApiRequestParamsInterface;
use asbamboo\api\apiStore\ApiResponseParamsInterface;
use exception\MessageException;
use asbamboo\api\exception\ApiException;
use asbamboo\database\FactoryInterface AS DbFactoryInterface;
use model\setting\Code;
use api\store\parameter\v1_0\kuaishou\getConfig\GetConfigResponse;
use model\setting\Repository AS SettingRepository;

/**
 * @name 获取抖音配置
 * @desc 抖音开发配置信息
 * @request api\store\parameter\v1_0\kuaishou\getConfig\GetConfigRequest
 * @response api\store\parameter\v1_0\kuaishou\getConfig\GetConfigResponse
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2021年8月27日
 */
class GetConfig implements ApiClassInterface
{

    /**
     *
     * @param DbFactoryInterface $Db
     */
    public function __construct(
        DbFactoryInterface $Db,
        SettingRepository $SettingRepository
    ){
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

            $SettingEntity  = $this->SettingRepository->findOneByType(Code::TYPE_KUAISHOU);
            $data           = $SettingEntity->getData();

            return new GetConfigResponse([
                'app_id'        => $data['app_id'],
                'app_secret'    => $data['app_secret'],
            ]);

        }catch(MessageException $e){
            throw new ApiException($e->getMessage());
        }
    }
}
