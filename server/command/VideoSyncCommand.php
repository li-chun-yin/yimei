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
use model\toutiaoId\Repository AS ToutiaoIdRepository;
use model\xiguaId\Repository AS XiguaIdRepository;
use model\kuaishouId\Repository AS KuaishouIdRepository;
use model\upload\Repository AS UploadRepository;
use library\douyin\ApiClient;
use library\model\UploadSyncDescStatus;
use model\setting\Repository AS SettingRepository;
use model\setting\Code AS SettingCode;
use library\kuaishou\ApiClient AS KuaishouApiClient;
use exception\SystemException;

/**
 * 视频发布到第三方平台
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月28日
 */
class VideoSyncCommand extends CommandAbstract
{
    use ContainerAwareTrait;

    const DOUYIN_SPLIT_SIZE     = 5 * 1024 * 1024;
    const TOUTIAO_SPLIT_SIZE    = 5 * 1024 * 1024;
    const XIGUA_SPLIT_SIZE      = 5 * 1024 * 1024;
    const KUAISHOU_SPLIT_SIZE   = 10 * 1024 * 1024;

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
            $upload_id      = $this->getOptionValueByProcessor('upload_id', $Processor);
            $while          = empty($upload_id); // 如果传入$upload_id参数，name不需要循环处理

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

            $start_memory   = memory_get_usage() . "\n"; // 36640
            $Processor->output()->print('Start Memory:' . $start_memory / 1024 / 1024 . "M \r\n");
            /**
             * @var UploadSyncEntity $UploadSyncEntity
             */
            do{
                try{
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
                            try{
                                $Processor->output()->print('正在同步:' . $UploadSyncEntity->getUploadId() . "\r\n");
                                $Processor->output()->print('同步类型:' . Code::TYPES[$UploadSyncEntity->getType()] . "\r\n");
                                $Processor->output()->print('同步open id:' . $UploadSyncEntity->getUnikey() . "\r\n");
                                if($UploadSyncEntity->getStatus() == Code::STATUS_DONE){
                                    $Processor->output()->print("已经同步过的视频.\r\n");
                                    continue;
                                }

                                $UploadSyncManager->load($UploadSyncEntity);
                                $sync_response  = $this->{'sync' . $UploadSyncEntity->getType()}($UploadSyncEntity, $UploadSyncDescEntity, $Processor);
                                $UploadSyncManager->updateSyncDone([
                                    'sync_response' => $sync_response,
                                ]);
                                $this->Db->getManager()->flush();

                                $Processor->output()->print("同步完成.\r\n");
                            }catch(SystemException $e){
                                $Processor->output()->print($e->__toString() . "\r\n");
                            }
                        }

                        $this->Db->getManager()->getUnitOfWork()->clear();
                        $UploadSyncDescEntity   = $UploadSyncDescManager->load($UploadSyncDescEntity->getUploadId());
                        $UploadSyncDescStatus->check($UploadSyncDescEntity);
                        $this->Db->getManager()->flush();
                    }
                } catch (SystemException $e){
                    $Processor->output()->print($e->__toString() . "\r\n");
                }

                $peak_memory    = memory_get_peak_usage();
                $now_memory     = memory_get_usage();
                $Processor->output()->print('Peak Memory:' . $peak_memory / 1024 / 1024 . "M \r\n");
                $Processor->output()->print('Now Memory:' . $now_memory / 1024 / 1024 . "M \r\n");
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

                unlink($tmpfname);
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
            'poi_id'                    => $UploadSyncEntity->getSyncRequest()['poi_id'] ?? '',
            'poi_name'                  => $UploadSyncEntity->getSyncRequest()['poi_name'] ?? '',
        ]);

        return [
            'VideoCreateResponse'       => $VideoCreateResponse->get(),
        ];
    }

    /**
     * 今日头条类型的同步
     */
    private function sync1(UploadSyncEntity $UploadSyncEntity, UploadSyncDescEntity $UploadSyncDescEntity, ProcessorInterface $Processor)
    {
        $split_num          = floor($UploadSyncDescEntity->getSize() / self::TOUTIAO_SPLIT_SIZE);

        /**
         *
         * @var ToutiaoIdRepository $ToutiaoIdRepository
         */
        $ToutiaoIdRepository = $this->Container->get(ToutiaoIdRepository::class);
        $ToutiaoIdEntity     = $ToutiaoIdRepository->findOneByOpenId($UploadSyncEntity->getUnikey());

        $video_id           = '';
        if($split_num < 2){
            $VideoUploadResponse        = ApiClient::request('ToutiaoVideoUpload', [
                'open_id'               => $ToutiaoIdEntity->getOpenId(),
                'access_token'          => $ToutiaoIdEntity->getAccessToken(),
                'filename'              => $UploadSyncDescEntity->getOriginalName(),
                'mime_type'             => $UploadSyncDescEntity->getMimeType(),
                'filepath'              => \Constant::UPLOAD_ROOT_PATH . DIRECTORY_SEPARATOR . $UploadSyncDescEntity->getPath(),
            ]);
            $video_id   = $VideoUploadResponse->get('video')['video_id'];
        }else{
            $VideoPartInitResponse      = ApiClient::request('ToutiaoVideoPartInit', [
                'open_id'               => $ToutiaoIdEntity->getOpenId(),
                'access_token'          => $ToutiaoIdEntity->getAccessToken(),
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


                $VideoPartUploadResponse    = ApiClient::request('ToutiaoVideoPartUpload', [
                    'open_id'               => $ToutiaoIdEntity->getOpenId(),
                    'access_token'          => $ToutiaoIdEntity->getAccessToken(),
                    'upload_id'             => $VideoPartInitResponse->get('upload_id'),
                    'part_number'           => $i,
                    'filename'              => $UploadSyncDescEntity->getOriginalName(),
                    'mime_type'             => $UploadSyncDescEntity->getMimeType(),
                    'filepath'              => $tmpfname,
                ]);

                unlink($tmpfname);
            }
            $VideoPartCompleteResponse  = ApiClient::request('ToutiaoVideoPartComplete', [
                'open_id'               => $ToutiaoIdEntity->getOpenId(),
                'access_token'          => $ToutiaoIdEntity->getAccessToken(),
                'upload_id'             => $VideoPartInitResponse->get('upload_id'),
            ]);
            $video_id   = $VideoPartCompleteResponse->get('video')['video_id'];
        }

        $VideoCreateResponse            = ApiClient::request('ToutiaoVideoCreate', [
            'open_id'                   => $ToutiaoIdEntity->getOpenId(),
            'access_token'              => $ToutiaoIdEntity->getAccessToken(),
            'video_id'                  => $video_id,
            'text'                      => $UploadSyncEntity->getSyncRequest()['text'],
        ]);

        return [
            'VideoCreateResponse'       => $VideoCreateResponse->get(),
        ];
    }

    /**
     * 西瓜视频的同步
     */
    private function sync2(UploadSyncEntity $UploadSyncEntity, UploadSyncDescEntity $UploadSyncDescEntity, ProcessorInterface $Processor)
    {
        $split_num          = floor($UploadSyncDescEntity->getSize() / self::XIGUA_SPLIT_SIZE);

        /**
         *
         * @var XiguaIdRepository $XiguaIdRepository
         */
        $XiguaIdRepository = $this->Container->get(XiguaIdRepository::class);
        $XiguaIdEntity     = $XiguaIdRepository->findOneByOpenId($UploadSyncEntity->getUnikey());

        $video_id           = '';
        if($split_num < 2){
            $VideoUploadResponse        = ApiClient::request('XiguaVideoUpload', [
                'open_id'               => $XiguaIdEntity->getOpenId(),
                'access_token'          => $XiguaIdEntity->getAccessToken(),
                'filename'              => $UploadSyncDescEntity->getOriginalName(),
                'mime_type'             => $UploadSyncDescEntity->getMimeType(),
                'filepath'              => \Constant::UPLOAD_ROOT_PATH . DIRECTORY_SEPARATOR . $UploadSyncDescEntity->getPath(),
            ]);
            $video_id   = $VideoUploadResponse->get('video')['video_id'];
        }else{
            $VideoPartInitResponse      = ApiClient::request('XiguaVideoPartInit', [
                'open_id'               => $XiguaIdEntity->getOpenId(),
                'access_token'          => $XiguaIdEntity->getAccessToken(),
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


                $VideoPartUploadResponse    = ApiClient::request('XiguaVideoPartUpload', [
                    'open_id'               => $XiguaIdEntity->getOpenId(),
                    'access_token'          => $XiguaIdEntity->getAccessToken(),
                    'upload_id'             => $VideoPartInitResponse->get('upload_id'),
                    'part_number'           => $i,
                    'filename'              => $UploadSyncDescEntity->getOriginalName(),
                    'mime_type'             => $UploadSyncDescEntity->getMimeType(),
                    'filepath'              => $tmpfname,
                ]);

                unlink($tmpfname);
            }
            $VideoPartCompleteResponse  = ApiClient::request('XiguaVideoPartComplete', [
                'open_id'               => $XiguaIdEntity->getOpenId(),
                'access_token'          => $XiguaIdEntity->getAccessToken(),
                'upload_id'             => $VideoPartInitResponse->get('upload_id'),
            ]);
            $video_id   = $VideoPartCompleteResponse->get('video')['video_id'];
        }

        $VideoCreateResponse            = ApiClient::request('XiguaVideoCreate', [
            'open_id'                   => $XiguaIdEntity->getOpenId(),
            'access_token'              => $XiguaIdEntity->getAccessToken(),
            'video_id'                  => $video_id,
            'text'                      => $UploadSyncEntity->getSyncRequest()['text'] ?? '',
            'abstract'                  => $UploadSyncEntity->getSyncRequest()['abstract'] ?? '',
            'claim_origin'              => $UploadSyncEntity->getSyncRequest()['claim_origin'] ?? false,
            'praise'                    => $UploadSyncEntity->getSyncRequest()['praise'] ?? false,
        ]);

        return [
            'VideoCreateResponse'       => $VideoCreateResponse->get(),
        ];
    }

    /**
     * 快手的同步
     */
    private function sync3(UploadSyncEntity $UploadSyncEntity, UploadSyncDescEntity $UploadSyncDescEntity, ProcessorInterface $Processor)
    {
        $split_num          = floor($UploadSyncDescEntity->getSize() / self::KUAISHOU_SPLIT_SIZE);

        /**
         * @var SettingRepository $SettingRepository
         * @var KuaishouIdRepository $KuaishouIdRepository
         */
        $SettingRepository      = $this->Container->get(SettingRepository::class);
        $KuaishouIdRepository   = $this->Container->get(KuaishouIdRepository::class);
        $KuaishouIdEntity       = $KuaishouIdRepository->findOneByOpenId($UploadSyncEntity->getUnikey());
        $SettingEntity          = $SettingRepository->findOneByType(SettingCode::TYPE_KUAISHOU);
//
        $OpenapiPhotoStartUploadResonse = KuaishouApiClient::request('OpenapiPhotoStartUpload', [
            'app_id'                    => $SettingEntity->getData()['app_id'],
            'access_token'              => $KuaishouIdEntity->getAccessToken(),
        ]);

        $video_id           = '';
        if($split_num < 2){
            $ApiUploadMultipartResponse = KuaishouApiClient::request('ApiUploadMultipart', [
                'upload_http'           => 'http://' . $OpenapiPhotoStartUploadResonse->get('endpoint'),
                'upload_token'          => $OpenapiPhotoStartUploadResonse->get('upload_token'),
                'filename'              => $UploadSyncDescEntity->getOriginalName(),
                'mime_type'             => $UploadSyncDescEntity->getMimeType(),
                'filepath'              => \Constant::UPLOAD_ROOT_PATH . DIRECTORY_SEPARATOR . $UploadSyncDescEntity->getPath(),
            ]);
        }else{
            $maxlength  = self::KUAISHOU_SPLIT_SIZE;
            $path       = \Constant::UPLOAD_ROOT_PATH . DIRECTORY_SEPARATOR . $UploadSyncDescEntity->getPath();
            $f          = fopen($path, "rb");
            for($i = 0; !feof($f); $i ++){
                $contents   = fread($f, $maxlength);

                $ApiUploadFragmentResponse  = KuaishouApiClient::request('ApiUploadFragment', [
                    'upload_http'           => 'http://' . $OpenapiPhotoStartUploadResonse->get('endpoint'),
                    'fragment_id'           => $i,
                    'upload_token'          => $OpenapiPhotoStartUploadResonse->get('upload_token'),
                    'contents'              => $contents,
                    'mime_type'             => $UploadSyncDescEntity->getMimeType(),
                ]);
            }
            fclose($f);

            $ApiUploadCompleteResponse  = KuaishouApiClient::request('ApiUploadComplete', [
                'upload_http'           => 'http://' . $OpenapiPhotoStartUploadResonse->get('endpoint'),
                'fragment_count'        => $i,
                'upload_token'          => $OpenapiPhotoStartUploadResonse->get('upload_token'),
            ]);
        }

        $UploadRepository   = $this->Container->get(UploadRepository::class);
        $UploadImageEntity  = $UploadRepository->findOneByUploadId($UploadSyncEntity->getSyncRequest()['cover_image_upload_id']);
        $filepath           = realpath(\Constant::UPLOAD_ROOT_PATH . DIRECTORY_SEPARATOR . $UploadImageEntity->getPath());
        $filename           = $UploadImageEntity->getOriginalName();
        $mime_type          = $UploadImageEntity->getMimeType();
        $cover              = new \CURLFile($filepath, $mime_type, $filename);

        $OpenapiPhotoPublishResponse    = KuaishouApiClient::request('OpenapiPhotoPublish', [
            'app_id'                    => $SettingEntity->getData()['app_id'],
            'access_token'              => $KuaishouIdEntity->getAccessToken(),
            'upload_token'              => $OpenapiPhotoStartUploadResonse->get('upload_token'),
            'cover'                     => $cover,
            'caption'                   => $UploadSyncEntity->getSyncRequest()['caption'],
        ]);

        return [
            'OpenapiPhotoPublishResponse'   => $OpenapiPhotoPublishResponse->get(),
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
        return '视频发布';
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