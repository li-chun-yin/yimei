<?php
namespace api\store\handler\v1_0\video;

use asbamboo\api\apiStore\ApiClassInterface;
use asbamboo\api\apiStore\ApiRequestParamsInterface;
use asbamboo\api\apiStore\ApiResponseParamsInterface;
use asbamboo\api\exception\ApiException;
use exception\MessageException;
use asbamboo\http\ServerRequest;
use api\store\parameter\v1_0\upload\file\FileResponse;
use model\upload\Manager AS UploadManager;
use model\upload\Code AS UploadCode;
use asbamboo\router\Router;
use asbamboo\database\FactoryInterface AS DbFactoryInterface;
use model\uploadSyncDesc\Manager AS UploadSyncDescManager;

/**
 * @name 上传视频
 * @desc 上传视频
 * @request api\store\parameter\v1_0\video\create\CreateRequest
 * @response api\store\parameter\v1_0\video\create\CreateResponse
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2020年3月7日
 */
class Create implements ApiClassInterface
{
    /**
     *
     * @var Router $Router
     * @var ServerRequest $ServerRequest
     * @var UploadManager $UploadManager
     * @var DbFactoryInterface $Db
     */
    private $Router, $ServerRequest, $UploadManager, $Db;
    
    public function __construct(
        ServerRequest $ServerRequest,
        UploadManager $UploadManager,
        UploadSyncDescManager $UploadSyncDescManager,
        Router $Router,
        DbFactoryInterface $Db
    ){
        $this->Router                   = $Router;
        $this->ServerRequest            = $ServerRequest;
        $this->UploadManager            = $UploadManager;
        $this->UploadSyncDescManager    = $UploadSyncDescManager;
        $this->Db                       = $Db;
    }
    
    public function exec(ApiRequestParamsInterface $Params): ?ApiResponseParamsInterface
    {
        try
        {
            $FileResponse   = new FileResponse();
            
            /**
             *
             * @var \asbamboo\http\UploadedFileInterface $Upfile
             */
            $Upfile            = $this->ServerRequest->getUploadedFiles()['file'];
            
            if(empty($Upfile)){
                throw new MessageException('请选择上传文件.');
            }
            
            $UploadEntity   = $this->UploadManager->load();
            $this->UploadManager->create([
                'extension'     => 'error:' . $Upfile->getError(),
                'mime_type'     => $Upfile->getClientMediaType(),
                'original_name' => $Upfile->getClientFilename(),
                'size'          => $Upfile->getSize(),
                'type'          => UploadCode::TYPE_VIDEO_SYNC,                
            ]);
            
            $absolute_path      = \Constant::UPLOAD_ROOT_PATH . DIRECTORY_SEPARATOR . $UploadEntity->getPath();
            
            if(!is_dir(\Constant::UPLOAD_ROOT_PATH)){
                mkdir(\Constant::UPLOAD_ROOT_PATH, 0755, true);
            }
            
            $this->Db->getManager()->transactional(function()use($UploadEntity, $Upfile, $absolute_path){
                $this->Db->getManager()->flush();
                
                $this->UploadSyncDescManager->load();
                $this->UploadSyncDescManager->create([
                    'mime_type'         => $UploadEntity->getMimeType(),
                    'original_name'     => $UploadEntity->getOriginalName(),
                    'size'              => $UploadEntity->getSize(),
                    'upload_id'         => $UploadEntity->getUploadId(),
                    'path'              => $UploadEntity->getPath(),
                ]);
                
                if($Upfile->moveTo($absolute_path) == false){
                    throw new MessageException('文件上传失败：' . $Upfile->getError());
                }
            });
            
            $FileResponse->id   = $UploadEntity->getUploadId();
            $FileResponse->url  = $this->Router->generateAbsoluteUrl('upload_read', ['upload_id' => $UploadEntity->getUploadId()]);
            return $FileResponse;
        }catch(MessageException $e){
            throw new ApiException($e->getMessage());
        }
    }
}
