<?php
namespace api\store\handler\v1_0\video;

use asbamboo\api\apiStore\ApiClassInterface;
use asbamboo\api\apiStore\ApiRequestParamsInterface;
use asbamboo\api\apiStore\ApiResponseParamsInterface;
use exception\MessageException;
use asbamboo\api\exception\ApiException;
use asbamboo\http\ServerRequestInterface;
use asbamboo\router\RouterInterface;
use api\store\parameter\v1_0\video\index\IndexResponse;
use model\uploadSyncDesc\Repository AS UploadSyncDescRepository;
use model\uploadSyncDesc\Code;
use library\model\UploadSyncDescStatus;
use asbamboo\database\FactoryInterface AS Db;

/**
 * @name 视频列表
 * @desc 需要同步至各个平台的视频列表
 * @request api\store\parameter\v1_0\video\index\IndexRequest
 * @response api\store\parameter\v1_0\video\index\IndexResponse
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2021年8月27日
 */
class Index implements ApiClassInterface
{
    /**
     * @var RouterInterface $Router
     * @var UploadSyncDescRepository $UploadSyncDescRepository
     * @var ServerRequestInterface $Request
     */
    private $UploadSyncDescRepository, $Request, $Router;

    /**
     */
    public function __construct(
        UploadSyncDescRepository $UploadSyncDescRepository,
        ServerRequestInterface $Request, RouterInterface $Router,
        UploadSyncDescStatus $UploadSyncDescStatus,
        Db $Db
    ){
        $this->UploadSyncDescRepository = $UploadSyncDescRepository;
        $this->Request                  = $Request;
        $this->Router                   = $Router;
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
            /**
             *
             * @var \model\uploadSyncDesc\Entity $UploadSyncDescEntity
             */
            $Paginator  = $this->UploadSyncDescRepository->getPageLists($this->Request);
            $items      = [];
            foreach($Paginator->getIterator() AS $UploadSyncDescEntity){

                $this->UploadSyncDescStatus->check($UploadSyncDescEntity);

                $items[]            = [
                    'original_name' => $UploadSyncDescEntity->getOriginalName(),
                    'mime_type'     => $UploadSyncDescEntity->getMimeType(),
                    'size'          => $UploadSyncDescEntity->getSize(),
                    'url'           => $this->Router->generateAbsoluteUrl('upload_read', ['upload_id' => $UploadSyncDescEntity->getUploadId()]),
                    'update_time'   => $UploadSyncDescEntity->getUpdateTime(),
                    'upload_id'     => $UploadSyncDescEntity->getUploadId(),
                    'status'        => $UploadSyncDescEntity->getStatus(),
                    'status_text'   => Code::STATUSS[$UploadSyncDescEntity->getStatus()],
                ];
            }

            $this->Db->getManager()->flush();

            return new IndexResponse([
                'items'     => $items,
                'total'     => $Paginator->count(),
            ]);
        }catch(MessageException $e){
            throw new ApiException($e->getMessage());
        }
    }
}
