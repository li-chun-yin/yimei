<?php
namespace api\store\handler\v1_0\video;

use asbamboo\api\apiStore\ApiClassInterface;
use asbamboo\api\apiStore\ApiRequestParamsInterface;
use asbamboo\api\apiStore\ApiResponseParamsInterface;
use exception\MessageException;
use asbamboo\api\exception\ApiException;
use asbamboo\router\RouterInterface;
use api\store\parameter\v1_0\video\info\InfoResponse;
use model\uploadSync\Repository AS UploadSyncRepository;
use model\uploadSyncDesc\Repository AS UploadSyncDescRepository;

/**
 * @name 视频列表
 * @desc 需要同步至各个平台的视频列表
 * @request api\store\parameter\v1_0\video\info\InfoRequest
 * @response api\store\parameter\v1_0\video\info\InfoResponse
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2021年8月27日
 */
class Info implements ApiClassInterface
{
    /**
     * @var RouterInterface $Router
     * @var UploadSyncRepository $UploadSyncRepository
     * @var UploadSyncDescRepository $UploadSyncDescRepository
     */
    private $UploadSyncRepository, $UploadSyncDescRepository, $Router;
    
    /**
     */
    public function __construct(RouterInterface $Router, UploadSyncRepository $UploadSyncRepository, UploadSyncDescRepository $UploadSyncDescRepository)
    {
        $this->UploadSyncRepository         = $UploadSyncRepository;
        $this->UploadSyncDescRepository     = $UploadSyncDescRepository;
        $this->Router                       = $Router;
    }
    
    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\api\apiStore\ApiClassInterface::exec()
     */
    public function exec(ApiRequestParamsInterface $Params): ?ApiResponseParamsInterface
    {
        try {
            $InfoResponse       = new InfoResponse();
            
            $InfoResponse->id   = $Params->getId();
            $InfoResponse->url  = $this->Router->generateAbsoluteUrl('upload_read', ['upload_id' => $Params->getId()]);
            
            /**
             * 
             * @var \model\uploadSync\Entity $UploadSyncEntity
             */
            $UploadSyncEntitys  = $this->UploadSyncRepository->findByUploadId($Params->getId());
            foreach($UploadSyncEntitys AS $UploadSyncEntity){
                $key                        = $UploadSyncEntity->getType() . '_' . $UploadSyncEntity->getUnikey();
                $cover_image_upload_id      = $UploadSyncEntity->getSyncRequest()['cover_image_upload_id'];
                $cover_image_url            = empty($cover_image_upload_id) ? '' : $this->Router->generateAbsoluteUrl('upload_read', ['upload_id' => $UploadSyncEntity->getSyncRequest()['cover_image_upload_id']]);
                $InfoResponse->syncs[$key]  = [
                    'type'                  => $UploadSyncEntity->getType(),
                    'unikey'                => $UploadSyncEntity->getUnikey(),
                    'sync_request'          => array_merge([
                        'cover_image_url'   => $cover_image_url,
                    ], $UploadSyncEntity->getSyncRequest()),
                    'status'                => $UploadSyncEntity->getStatus(),
                ];
            }

            $UploadSyncDescEntity       = $this->UploadSyncDescRepository->findOneByUploadId($Params->getId());
            $cover_image_upload_id      = $UploadSyncDescEntity->getSyncData()['cover_image_upload_id'];
            $cover_image_url            = empty($cover_image_upload_id) ? '' : $this->Router->generateAbsoluteUrl('upload_read', ['upload_id' => $UploadSyncDescEntity->getSyncData()['cover_image_upload_id']]);
            $InfoResponse->sync_desc    = [
                'sync_data'             => array_merge([
                    'cover_image_url'   => $cover_image_url,
                ], $UploadSyncDescEntity->getSyncData()),
                'status'                => $UploadSyncDescEntity->getStatus()
            ];
            
            return $InfoResponse;
        }catch(MessageException $e){
            throw new ApiException($e->getMessage());
        }
    }
}
