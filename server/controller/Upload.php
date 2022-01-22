<?php
namespace controller;

use Constant;
use asbamboo\framework\controller\ControllerAbstract;
use asbamboo\http\Response;
use asbamboo\http\TextResponse;
use asbamboo\http\Constant AS HttpConstant;
use asbamboo\http\Stream;
use model\upload\Repository AS UploadRepository;

class Upload extends ControllerAbstract
{
    /**
     *
     * @param string $image
     */
    public function read(string $upload_id)
    {
        ini_set("memory_limit", -1);
        
        /**
         *
         * @var UploadRepository $UploadRepository
         */
        $UploadRepository       = $this->Container->get(UploadRepository::class);
        $UploadEntity           = $UploadRepository->findOneByUploadId($upload_id);
        if(empty($UploadEntity)){
            return new TextResponse('资源不存在。', HttpConstant::STATUS_NOT_FOUND);
        }
        $upload_path            = $UploadEntity->getPath();
        $upload_media_type      = $UploadEntity->getMimeType();
        $image_absolute_path    = Constant::UPLOAD_ROOT_PATH . DIRECTORY_SEPARATOR . $upload_path;

        $Body           = new Stream($image_absolute_path, 'rb');
        return new Response($Body, HttpConstant::STATUS_OK, [
            'content-type'  => $upload_media_type,
        ]);
    }
}