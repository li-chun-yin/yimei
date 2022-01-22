<?php
namespace model\uploadSync;

use asbamboo\database\FactoryInterface;
use exception\SystemException;
use Doctrine\DBAL\LockMode;

/**
 * 管理账号表的操作
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
     * @return Entity
     */
    public function load(/*int|Entity*/ $upload_sync = null) : Entity
    {
        if(is_null($upload_sync)){
            $this->Entity   = new Entity();
        }else if($upload_sync instanceof Entity){
            $this->Entity   = $upload_sync;
        }else{
            $this->Entity   = $this->Repository->findOneBySeq($upload_sync);
            if(empty($this->Entity)){
                throw new SystemException('视频同步信息不存在。');
            } 
        }
        return $this->Entity;
    }

    /**
     * 创建数据行
     *
     * @param array $data
     * @return Manager
     */
    public function create(array $data) : Manager
    {
        $this->Entity->setUnikey($data['unikey']);
        $this->Entity->setUploadId($data['upload_id']);
        $this->Entity->setStatus(Code::STATUS_ING);
        $this->Entity->setType($data['type']);
        $this->Entity->setSyncRequest($data['sync_request']);
        $this->Entity->setSyncResponse([]);
        
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
    public function updateSyncDone(array $data) : Manager
    {
        $this->Entity->setSyncResponse($data['sync_response']);
        $this->Entity->setStatus(Code::STATUS_DONE);
        
        $this->validateUpdateSyncDone();
        
        $this->Entity->setUpdateTime(time());
        
        $this->Db->getManager()->lock($this->Entity, LockMode::OPTIMISTIC);
        
        return $this;
    }

    
    public function validateCreate()
    {        
    }
    
    public function validateUpdateSyncDone()
    {
        
    }
}