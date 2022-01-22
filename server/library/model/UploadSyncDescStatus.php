<?php
namespace  library\model;

use model\uploadSyncDesc\Entity AS UploadSyncDescEntity;
use asbamboo\di\ContainerAwareTrait;
use model\douyinId\Repository AS DouyinIdRepository;
use model\uploadSync\Repository AS UploadSyncRepository;
use model\uploadSync\Code AS UploadSyncCode;
use model\uploadSyncDesc\Code AS UploadSyncDescCode;
use model\uploadSyncDesc\Manager AS UploadSyncDescManager;

/**
 * 
 * @author 李春寅 <http://licy.top>
 */
class UploadSyncDescStatus
{
    use ContainerAwareTrait;
    
    /**
     * 核对 $UploadSyncDescEntity 的 status 值。并且调用数据更新方法。
     * 但如果更新的数据需要持久化，那么需要在这个方法调用后，继续调用database的flush方法
     *  - 1. 如果 UploadSync 数据， 
     *       那么 UploadSyncDesc 的状态是 UploadSyncDescCode::STATUS_WAIT
     *  - 2. 如果 UploadSync 数据含有 UploadSyncCode::STATUS_ING， 
     *  -    那么 UploadSyncDesc 的状态是 UploadSyncDescCode::STATUS_ING
     *  - 3. 如果 UploadSync 数据全部是 UploadSyncCode::STATUS_DONE、数据总数少于绑定系统的第三方账号
     *       那么 UploadSyncDesc 的状态是 UploadSyncDescCode::STATUS_PART
     *  - 4. 如果 UploadSync 数据全部是 UploadSyncCode::STATUS_DONE、数据总数等于或对于（可能会有第三方账号绑定后删除）绑定系统的第三方账号 
     *       那么 UploadSyncDesc 的状态是 UploadSyncDescCode::STATUS_DONE
     * @param UploadSyncDescEntity $UploadSyncDescEntity
     */
    public function check(UploadSyncDescEntity $UploadSyncDescEntity) : void
    {
        /**
         * @var UploadSyncDescManager $UploadSyncDescManager
         * @var UploadSyncRepository $UploadSyncRepository
         * @var \model\uploadSync\Entity $UploadSyncEntity
         */
        $UploadSyncRepository   = $this->Container->get(UploadSyncRepository::class);
        $UploadSyncDescManager  = $this->Container->get(UploadSyncDescManager::class);
        $sync_open_ids          = $this->getSyncOpenIds();
        $UploadSyncDescManager->load($UploadSyncDescEntity);
        
        $status                 = UploadSyncDescCode::STATUS_WAIT;
        $synced_count           = 0;
        $UploadSyncEntitys      = $UploadSyncRepository->findByUploadId($UploadSyncDescEntity->getUploadId());
        foreach($UploadSyncEntitys AS $UploadSyncEntity){
            if($UploadSyncEntity->getStatus() == UploadSyncCode::STATUS_ING){
                $status = UploadSyncDescCode::STATUS_ING;
                break;
            }
            if($UploadSyncEntity->getStatus() == UploadSyncCode::STATUS_DONE && in_array($UploadSyncEntity->getUnikey(), $sync_open_ids)){
                $synced_count ++;
            }
        }
        
        if($status == UploadSyncDescCode::STATUS_ING) {
            $UploadSyncDescManager->updateStatusIng(['sync_data' => $UploadSyncDescEntity->getSyncData()]);
        }
        
        
        if($status != UploadSyncDescCode::STATUS_ING && $synced_count > 0 && $synced_count < count($sync_open_ids)){
            $UploadSyncDescManager->updateStatusPart();
        }

        if($status != UploadSyncDescCode::STATUS_ING && $synced_count == count($sync_open_ids)){
            $UploadSyncDescManager->updateStatusDone();
        }        
    }
    
    private function getSyncOpenIds()
    {
        /**
         * 
         * @var DouyinIdRepository $DouyinIdRepository
         */
        $DouyinIdRepository = $this->Container->get(DouyinIdRepository::class);
        return $DouyinIdRepository->getAllOpenIds();
    }
}
