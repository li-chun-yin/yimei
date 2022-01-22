<?php
namespace model\uploadSyncDesc;

use asbamboo\database\FactoryInterface;
use exception\MessageException;
use Doctrine\DBAL\LockMode;

/**
 * 管理表的操作
 *  - 控制 增/删/改
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月28日
 */
class Manager
{
    /**
     *
     * @var FactoryInterface
     */
    private $Db;

    /**
     *
     * @var Repository
     */
    private $Repository;

    /**
     *
     * @var Entity
     */
    private $Entity;

    /**
     *
     * @param FactoryInterface $Db
     */
    public function __construct(FactoryInterface $Db, Repository $Repository)
    {
        $this->Db               = $Db;
        $this->Repository       = $Repository;
    }

    /**
     *
     * @param int|Entity $account
     * @throws MessageException
     * @return Entity
     */
    public function load(/*int|Entity*/ $UploadSyncDesc = null) : Entity
    {
        if(is_null($UploadSyncDesc)){
            $this->Entity   = new Entity();
        }else if($UploadSyncDesc instanceof Entity){
            $this->Entity   = $UploadSyncDesc;
        }else{
            $this->Entity   = $this->Repository->findOneByUploadId($UploadSyncDesc);
            if(empty($this->Entity)){
                throw new MessageException('上传信息不存在。');
            }
        }
        return $this->Entity;
    }    
    
    /**
     * 创建数据行
     *
     * @return Manager
     */
    public function create(array $data) : Manager
    {
        $this->Entity->setMimeType($data['mime_type']);
        $this->Entity->setOriginalName($data['original_name']);
        $this->Entity->setSize($data['size']);
        $this->Entity->setUploadId($data['upload_id']);
        $this->Entity->setPath($data['path']);
        $this->Entity->setStatus(Code::STATUS_WAIT);
        $this->Entity->setSyncData([]);
        
        $this->validateCreate();

        $this->Entity->setCreateTime(time());
        $this->Entity->setUpdateTime(time());
        
        $this->Db->getManager()->persist($this->Entity);

        return $this;
    }

    /**
     *
     * @param array $data
     * @return Manager
     */
    public function updateSyncData(array $data) : Manager
    {
        $this->Entity->setSyncData(array_merge($this->Entity->getSyncData(), [
            'text'                  => isset($data['text']) ? $data['text'] : $this->Entity->getSyncData()['text'],
            'poi_id'                => isset($data['poi_id']) ? $data['poi_id'] : $this->Entity->getSyncData()['poi_id'],
            'poi_name'              => isset($data['poi_name']) ? $data['poi_name'] : $this->Entity->getSyncData()['poi_name'],
            'cover_image_upload_id' => isset($data['cover_image_upload_id']) ? $data['cover_image_upload_id'] : $this->Entity->getSyncData()['cover_image_upload_id'],
        ]));
        
        $this->validateUpdateSyncData();
        
        $this->Entity->setUpdateTime(time());
        
        $this->Db->getManager()->lock($this->Entity, LockMode::OPTIMISTIC);
        
        return $this;
    }
    
    /**
     * 
     * @param array $data
     * @return Manager
     */
    public function updateStatusIng(array $data) : Manager
    {
        $this->Entity->setStatus(Code::STATUS_ING);
        $this->Entity->setSyncData($data['sync_data']);
        
        $this->validateUpdateStatusIng();
        
        $this->Entity->setUpdateTime(time());
        
        $this->Db->getManager()->lock($this->Entity, LockMode::OPTIMISTIC);
        
        return $this;
    }

    /**
     *
     * @return Manager
     */
    public function updateStatusDone() : Manager
    {
        $this->Entity->setStatus(Code::STATUS_DONE);
        
        $this->validateUpdateStatusDone();
        
        $this->Entity->setUpdateTime(time());
        
        $this->Db->getManager()->lock($this->Entity, LockMode::OPTIMISTIC);
        
        return $this;
    }

    /**
     *
     * @return Manager
     */
    public function updateStatusPart() : Manager
    {
        $this->Entity->setStatus(Code::STATUS_PART);
        
        $this->validateUpdateStatusPart();
        
        $this->Entity->setUpdateTime(time());
        
        $this->Db->getManager()->lock($this->Entity, LockMode::OPTIMISTIC);
        
        return $this;
    }
    
    /**
     * 验证
     *
     * @throws MessageException
     */
    public function validateCreate()
    {
    }
    
    public function validateUpdateStatusIng()
    {
        
    }

    public function validateUpdateStatusDone()
    {
        
    }
    
    public function validateUpdateStatusPart()
    {
        
    }
    
    public function validateUpdateSyncData()
    {
        
    }
}