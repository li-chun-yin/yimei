<?php
namespace  library\model;

use model\uploadSyncDesc\Entity AS UploadSyncDescEntity;
use asbamboo\di\ContainerAwareTrait;
use model\douyinId\Repository AS DouyinIdRepository;
use model\toutiaoId\Repository AS ToutiaoIdRepository;
use model\xiguaId\Repository AS XiguaIdRepository;
use model\uploadSync\Repository AS UploadSyncRepository;
use model\uploadSync\Code AS UploadSyncCode;
use model\uploadSyncDesc\Code AS UploadSyncDescCode;
use model\uploadSyncDesc\Manager AS UploadSyncDescManager;
use model\kuaishouId\Repository AS KuaishouIdRepository;

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
     *  - 1. 如果 没有 UploadSync 数据，
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
        $sync_toutiao_open_ids  = $this->getSyncToutiaoOpenIds();
        $sync_xigua_open_ids    = $this->getSyncXiguaOpenIds();
        $sync_kuaishou_open_ids = $this->getSyncKuaishouOpenIds();
        $sync_total             = count($sync_open_ids)
                                + count($sync_toutiao_open_ids)
                                + count($sync_xigua_open_ids)
                                + count($sync_kuaishou_open_ids)
        ;
        $UploadSyncDescManager->load($UploadSyncDescEntity);

        $status                 = UploadSyncDescCode::STATUS_WAIT;
        $synced_count           = 0;
        $UploadSyncEntitys      = $UploadSyncRepository->findByUploadId($UploadSyncDescEntity->getUploadId());
        foreach($UploadSyncEntitys AS $UploadSyncEntity){
            if($UploadSyncEntity->getStatus() == UploadSyncCode::STATUS_ING){
                $status = UploadSyncDescCode::STATUS_ING;
                break;
            }

            if(     $UploadSyncEntity->getType() == UploadSyncCode::TYPE_DOUYIN
                &&  $UploadSyncEntity->getStatus() == UploadSyncCode::STATUS_DONE
                &&  in_array($UploadSyncEntity->getUnikey(), $sync_open_ids)
            ){
                $synced_count ++;
            }

            if(     $UploadSyncEntity->getType() == UploadSyncCode::TYPE_TOUTIAO
                &&  $UploadSyncEntity->getStatus() == UploadSyncCode::STATUS_DONE
                &&  in_array($UploadSyncEntity->getUnikey(), $sync_toutiao_open_ids)
            ){
                $synced_count ++;
            }

            if(     $UploadSyncEntity->getType() == UploadSyncCode::TYPE_XIGUA
                &&  $UploadSyncEntity->getStatus() == UploadSyncCode::STATUS_DONE
                &&  in_array($UploadSyncEntity->getUnikey(), $sync_xigua_open_ids)
            ){
                $synced_count ++;
            }

            if(     $UploadSyncEntity->getType() == UploadSyncCode::TYPE_KUAISHOU
                &&  $UploadSyncEntity->getStatus() == UploadSyncCode::STATUS_DONE
                &&  in_array($UploadSyncEntity->getUnikey(), $sync_kuaishou_open_ids)
            ){
                $synced_count ++;
            }
        }

        if($status == UploadSyncDescCode::STATUS_ING) {
            $UploadSyncDescManager->updateStatusIng(['sync_data' => $UploadSyncDescEntity->getSyncData()]);
        }

        if($status != UploadSyncDescCode::STATUS_ING && $synced_count > 0 && $synced_count < $sync_total){
            $UploadSyncDescManager->updateStatusPart();
        }

        if($status != UploadSyncDescCode::STATUS_ING && $synced_count >= $sync_total){
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


    private function getSyncToutiaoOpenIds()
    {
        /**
         *
         * @var ToutiaoIdRepository $ToutiaoIdRepository
         */
        $ToutiaoIdRepository = $this->Container->get(ToutiaoIdRepository::class);
        return $ToutiaoIdRepository->getAllOpenIds();
    }

    private function getSyncXiguaOpenIds()
    {
        /**
         *
         * @var XiguaIdRepository $XiguaIdRepository
         */
        $XiguaIdRepository = $this->Container->get(XiguaIdRepository::class);
        return $XiguaIdRepository->getAllOpenIds();
    }

    private function getSyncKuaishouOpenIds()
    {
        /**
         *
         * @var KuaishouIdRepository $KuaishouIdRepository
         */
        $KuaishouIdRepository = $this->Container->get(KuaishouIdRepository::class);
        return $KuaishouIdRepository->getAllOpenIds();
    }
}
