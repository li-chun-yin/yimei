<?php
namespace command;

use asbamboo\console\command\CommandAbstract;
use asbamboo\di\ContainerAwareTrait;
use asbamboo\console\ProcessorInterface;
use asbamboo\database\FactoryInterface;
use model\uploadSync\Repository AS UploadSyncRepository;
use model\uploadSync\Code;
use model\uploadSync\Manager AS UploadSyncManager;
use model\uploadSyncDesc\Manager AS UploadSyncDescManager;
use model\uploadSync\Entity AS UploadSyncEntity;
use model\uploadSyncDesc\Entity AS UploadSyncDescEntity;
use model\douyinId\Repository AS DouyinIdRepository;
use model\upload\Repository AS UploadRepository;
use library\douyin\ApiClient;
use library\model\UploadSyncDescStatus;

/**
 * 添加新用户
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月28日
 */
class VideoSyncCommand extends CommandAbstract
{
    use ContainerAwareTrait;
    
    const DOUYIN_SPLIT_SIZE  = 5 * 1024 * 1024;

    /**
     *
     * @var FactoryInterface
     */
    private $Db;
    /**
     *
     */
    public function __construct(FactoryInterface $Db)
    {
        parent::__construct();
        $this->Db               = $Db;
        $this->AddOption('upload_id', null, '文件的upload id。');
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\console\command\CommandInterface::exec()
     */
    public function exec(ProcessorInterface $Processor)
    {
        try{
            $upload_id  = $this->getOptionValueByProcessor('upload_id', $Processor);
            $while      = empty($upload_id); // 如果传入$upload_id参数，name不需要循环处理

            /**
             * @var UploadSyncDescStatus $UploadSyncDescStatus
             * @var UploadSyncDescManager $UploadSyncDescManager
             * @var UploadSyncManager $UploadSyncManager
             * @var UploadSyncRepository $UploadSyncRepository
             */
            $UploadSyncRepository   = $this->Container->get(UploadSyncRepository::class);
            $UploadSyncManager      = $this->Container->get(UploadSyncManager::class);
            $UploadSyncDescManager  = $this->Container->get(UploadSyncDescManager::class);
            $UploadSyncDescStatus   = $this->Container->get(UploadSyncDescStatus::class);
            
            /**
             * @var UploadSyncEntity $UploadSyncEntity
             */
            do{                
                $UploadSyncEntitys      = [];
                $UploadSyncDescEntity   = null;
                
                if(empty($upload_id)){
                    $UploadSyncEntity       = $UploadSyncRepository->findOneByStatusIng();
                    if(!empty($UploadSyncEntity)){
                        $UploadSyncEntitys[]    = $UploadSyncEntity;
                        $UploadSyncDescEntity   = $UploadSyncDescManager->load($UploadSyncEntity->getUploadId());
                    }
                }else{
                    $UploadSyncEntitys      = $UploadSyncRepository->findByUploadId($upload_id);
                    $UploadSyncDescEntity   = $UploadSyncDescManager->load($upload_id);
                }
                
                if(!empty($UploadSyncEntitys)){
                    foreach($UploadSyncEntitys AS $UploadSyncEntity){
                        $Processor->output()->print('正在同步:' . $UploadSyncEntity->getUploadId() . "\r\n");
                        $Processor->output()->print('同步类型:' . Code::TYPES[$UploadSyncEntity->getType()] . "\r\n");
                        $Processor->output()->print('同步open id:' . $UploadSyncEntity->getUnikey() . "\r\n");
                        if($UploadSyncEntity->getStatus() == Code::STATUS_DONE){
                            $Processor->output()->print("已经同步过的视频.\r\n");
                            continue;
                        }
                        
                        $UploadSyncManager->load($UploadSyncEntity);
                        $sync_response  = $this->{'sync' . Code::TYPE_DOUYIN}($UploadSyncEntity, $UploadSyncDescEntity, $Processor);
                        $UploadSyncManager->updateSyncDone([
                            'sync_response' => $sync_response,
                        ]);
                        $this->Db->getManager()->flush();
                        
                        $Processor->output()->print(
                            "同步完成.\r\n"
                            );
                    }
                    
                    $this->Db->getManager()->getUnitOfWork()->clear(get_class($UploadSyncEntity));
                    $UploadSyncDescStatus->check($UploadSyncDescEntity);
                    $this->Db->getManager()->flush();                    
                }
            }while($while); 
        }catch(\Throwable $e){
            $Processor->output()->print($e->__toString());
        }
    }
    
    /**
     * 抖音类型的同步
     */
    private function sync0(UploadSyncEntity $UploadSyncEntity, UploadSyncDescEntity $UploadSyncDescEntity, ProcessorInterface $Processor)
    {
        $split_num          = floor($UploadSyncDescEntity->getSize() / self::DOUYIN_SPLIT_SIZE);
        
        /**
         * 
         * @var DouyinIdRepository $DouyinIdRepository
         */
        $DouyinIdRepository = $this->Container->get(DouyinIdRepository::class);
        $DouyinIdEntity     = $DouyinIdRepository->findOneByOpenId($UploadSyncEntity->getUnikey());
        
        $video_id           = '';
        if($split_num < 2){
            $VideoUploadResponse        = ApiClient::request('VideoUpload', [
                'open_id'               => $DouyinIdEntity->getOpenId(),
                'access_token'          => $DouyinIdEntity->getAccessToken(),
                'filename'              => $UploadSyncDescEntity->getOriginalName(),
                'mime_type'             => $UploadSyncDescEntity->getMimeType(),
                'filepath'              => \Constant::UPLOAD_ROOT_PATH . DIRECTORY_SEPARATOR . $UploadSyncDescEntity->getPath(),
            ]);
            $video_id   = $VideoUploadResponse->get('video')['video_id'];
        }else{
            $VideoPartInitResponse      = ApiClient::request('VideoPartInit', [
                'open_id'               => $DouyinIdEntity->getOpenId(),
                'access_token'          => $DouyinIdEntity->getAccessToken(),
            ]);
            
            for($i = 1; $i <= $split_num; $i ++){
                $maxlength  = $i == $split_num ? null : $UploadSyncDescEntity->getSize() / $split_num;
                $offset     = $UploadSyncDescEntity->getSize() / $split_num * ($i - 1);
                $path       = \Constant::UPLOAD_ROOT_PATH . DIRECTORY_SEPARATOR . $UploadSyncDescEntity->getPath();
                $f          = fopen($path, "rb");
                $contents   = stream_get_contents($f, $maxlength, $offset);
                $tmpfname   = tempnam(\Constant::UPLOAD_ROOT_PATH, 'tmpf');
                $handle     = fopen($tmpfname, "w");
                fwrite($handle, $contents);
                fclose($handle);
                
                
                $VideoPartUploadResponse    = ApiClient::request('VideoPartUpload', [
                    'open_id'               => $DouyinIdEntity->getOpenId(),
                    'access_token'          => $DouyinIdEntity->getAccessToken(),
                    'upload_id'             => $VideoPartInitResponse->get('upload_id'),
                    'part_number'           => $i,
                    'filename'              => $UploadSyncDescEntity->getOriginalName(),
                    'mime_type'             => $UploadSyncDescEntity->getMimeType(),
                    'filepath'              => $tmpfname,
                ]);
            }
            $VideoPartCompleteResponse  = ApiClient::request('VideoPartComplete', [
                'open_id'               => $DouyinIdEntity->getOpenId(),
                'access_token'          => $DouyinIdEntity->getAccessToken(),
                'upload_id'             => $VideoPartInitResponse->get('upload_id'),
            ]);
            $video_id   = $VideoPartCompleteResponse->get('video')['video_id'];
        }
        
        /**
         * 
         * @var UploadRepository $UploadRepository
         */
        $custom_cover_image_url         = null;
        if(!empty($UploadSyncEntity->getSyncRequest()['cover_image_upload_id'])){
            $UploadRepository           = $this->Container->get(UploadRepository::class);
            $UploadImageEntity          = $UploadRepository->findOneByUploadId($UploadSyncEntity->getSyncRequest()['cover_image_upload_id']);
            $ImageUploadResponse        = ApiClient::request('ImageUpload', [
                'open_id'               => $DouyinIdEntity->getOpenId(),
                'access_token'          => $DouyinIdEntity->getAccessToken(),
                'filepath'              => \Constant::UPLOAD_ROOT_PATH . DIRECTORY_SEPARATOR . $UploadImageEntity->getPath(),
                'filename'              => $UploadImageEntity->getOriginalName(),
                'mime_type'             => $UploadImageEntity->getMimeType(),
            ]);
            $custom_cover_image_url     =  $ImageUploadResponse->get('image')['image_id'];
        }
        
        $VideoCreateResponse            = ApiClient::request('VideoCreate', [
            'open_id'                   => $DouyinIdEntity->getOpenId(),
            'access_token'              => $DouyinIdEntity->getAccessToken(),
            'video_id'                  => $video_id,
            'text'                      => $UploadSyncEntity->getSyncRequest()['text'],
            'custom_cover_image_url'    => $custom_cover_image_url,
            'poi_id'                    => $UploadSyncEntity->getSyncRequest()['poi_id'],
            'poi_name'                  => $UploadSyncEntity->getSyncRequest()['poi_name'],
        ]);
        
        return [
            'VideoCreateResponse'       => $VideoCreateResponse
        ];
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\console\command\CommandInterface::help()
     */
    public function help(): string
    {
        $console    = $_SERVER['SCRIPT_FILENAME'];
        return <<<HELP
    例: php {$console} {$this->getName()}
HELP;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\console\command\CommandInterface::desc()
     */
    public function desc(): string
    {
        return '视频同步';
    }

    /**
     *
     * @return string
     */
    public function getName() : string
    {
        return 'video-sync';
    }
}