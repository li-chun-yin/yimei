<?php
namespace api\store\handler\v1_0\video;

use asbamboo\api\apiStore\ApiClassInterface;
use asbamboo\api\apiStore\ApiRequestParamsInterface;
use asbamboo\api\apiStore\ApiResponseParamsInterface;
use exception\MessageException;
use asbamboo\api\exception\ApiException;
use asbamboo\database\FactoryInterface AS DbFactoryInterface;
use model\uploadSyncDesc\Manager AS UploadSyncDescManager;
use api\store\parameter\v1_0\video\syncBasic\SyncBasicResponse;

/**
 * @name 同步基准
 * @desc 修改视频同步基本信息
 * @request api\store\parameter\v1_0\video\syncBasic\SyncBasicRequest
 * @response api\store\parameter\v1_0\video\syncBasic\SyncBasicResponse
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2021年8月27日
 */
class SyncBasic implements ApiClassInterface
{
    /**
     * 
     * @param UploadSyncDescManager $UploadSyncDescManager
     * @param DbFactoryInterface $DbFactory
     */
    public function __construct(UploadSyncDescManager $UploadSyncDescManager, DbFactoryInterface $DbFactory)
    {
        $this->UploadSyncDescManager    = $UploadSyncDescManager;
        $this->Db                       = $DbFactory;
    }
    
    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\api\apiStore\ApiClassInterface::exec()
     */
    public function exec(ApiRequestParamsInterface $Params): ?ApiResponseParamsInterface
    {
        try {            
            $this->UploadSyncDescManager->load($Params->getUploadId());
            $this->UploadSyncDescManager->updateSyncData([
                'text'                  => (string) $Params->getText(),
                'abstract'              => (string) $Params->getAbstract(),
                'poi_id'                => (string) $Params->getPoiId(),
                'poi_name'              => (string) $Params->getPoiName(),
                'cover_image_upload_id' => (string) $Params->getCoverImageUploadId(),
                'claim_origin'          => (boolean) $Params->getClaimOrigin(),
                'praise'                => (boolean) $Params->getPraise(),
            ]);
            
            $this->Db->getManager()->flush();
            
            return new SyncBasicResponse();
        }catch(MessageException $e){
            throw new ApiException($e->getMessage());
        }
    }
}
