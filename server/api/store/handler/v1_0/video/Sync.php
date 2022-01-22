<?php
namespace api\store\handler\v1_0\video;

use asbamboo\api\apiStore\ApiClassInterface;
use asbamboo\api\apiStore\ApiRequestParamsInterface;
use asbamboo\api\apiStore\ApiResponseParamsInterface;
use exception\MessageException;
use asbamboo\api\exception\ApiException;
use model\uploadSync\Manager AS UploadSyncManager;
use asbamboo\database\FactoryInterface AS DbFactoryInterface;
use api\store\parameter\v1_0\video\sync\SyncResponse;
use model\uploadSyncDesc\Manager AS UploadSyncDescManager;

/**
 * @name 视频列表
 * @desc 需要同步至各个平台的视频列表
 * @request api\store\parameter\v1_0\video\sync\SyncRequest
 * @response api\store\parameter\v1_0\video\sync\SyncResponse
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2021年8月27日
 */
class Sync implements ApiClassInterface
{
    /**
     */
    public function __construct(UploadSyncManager $UploadSyncManager, UploadSyncDescManager $UploadSyncDescManager, DbFactoryInterface $DbFactory)
    {
        $this->UploadSyncDescManager    = $UploadSyncDescManager;
        $this->UploadSyncManager        = $UploadSyncManager;
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
            
            $this->UploadSyncManager->load();
            $this->UploadSyncManager->create([
                'unikey'        => $Params->getUnikey(),
                'upload_id'     => $Params->getUploadId(),
                'type'          => $Params->getType(),
                'sync_request'  => $Params->getSyncRequest(),
            ]);
            
            $UploadSyncDescEntity   = $this->UploadSyncDescManager->load($Params->getUploadId());
            $this->UploadSyncDescManager->updateStatusIng([
                'sync_data'     => $UploadSyncDescEntity->getSyncData(),
            ]);
            
            $this->Db->getManager()->flush();
            
            return new SyncResponse();
        }catch(MessageException $e){
            throw new ApiException($e->getMessage());
        }
    }
}
