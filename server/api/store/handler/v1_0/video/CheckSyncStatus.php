<?php
namespace api\store\handler\v1_0\video;

use asbamboo\api\apiStore\ApiClassInterface;
use asbamboo\api\apiStore\ApiRequestParamsInterface;
use asbamboo\api\apiStore\ApiResponseParamsInterface;
use exception\MessageException;
use asbamboo\api\exception\ApiException;
use model\uploadSyncDesc\Manager AS UploadSyncDescManager;
use library\model\UploadSyncDescStatus;
use api\store\parameter\v1_0\video\checkSyncStatus\CheckSyncStatusResponse;
use asbamboo\database\FactoryInterface AS DbFactoryInterface;

/**
 * @name 视频同步状态
 * @desc 校准数据中视频同步的状态
 * @request api\store\parameter\v1_0\video\checkSyncStatus\CheckSyncStatusRequest
 * @response api\store\parameter\v1_0\video\checkSyncStatus\CheckSyncStatusResponse
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2021年8月27日
 */
class CheckSyncStatus implements ApiClassInterface
{
    /**
     */
    public function __construct(UploadSyncDescManager $UploadSyncDescManager, UploadSyncDescStatus $UploadSyncDescStatus, DbFactoryInterface $Db)
    {
        $this->UploadSyncDescManager    = $UploadSyncDescManager;
        $this->UploadSyncDescStatus     = $UploadSyncDescStatus;
        $this->Db                       = $Db;
    }
    
    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\api\apiStore\ApiClassInterface::exec()
     */
    public function exec(ApiRequestParamsInterface $Params): ?ApiResponseParamsInterface
    {
        try {
            
            $UploadSyncDescEntity           = $this->UploadSyncDescManager->load($Params->getUploadId());
            
            $this->UploadSyncDescStatus->check($UploadSyncDescEntity);
            $this->Db->getManager()->flush();
            
            $UploadSyncDescEntity           = $this->UploadSyncDescManager->load($Params->getUploadId());
                        
            return new CheckSyncStatusResponse([
                'status'    => $UploadSyncDescEntity->getStatus(),
            ]);
        }catch(MessageException $e){
            throw new ApiException($e->getMessage());
        }
    }
}
