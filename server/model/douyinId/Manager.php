<?php
namespace model\douyinId;

use asbamboo\database\FactoryInterface;
use asbamboo\http\ServerRequestInterface;
use Doctrine\DBAL\LockMode;
use exception\SystemException;

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
     * @var ServerRequestInterface
     */
    private $ServerRequest;

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
    public function __construct(FactoryInterface $Db, ServerRequestInterface $ServerRequest, Repository $Repository)
    {
        $this->Db               = $Db;
        $this->ServerRequest    = $ServerRequest;
        $this->Repository       = $Repository;
    }

    /**
     *
     * @param int|Entity $account
     * @return Entity
     */
    public function load(/*int|Entity*/ $douyin_id = null) : Entity
    {
        if(is_null($douyin_id)){
            $this->Entity   = new Entity();
        }else if($douyin_id instanceof Entity){
            $this->Entity   = $douyin_id;
        }else{
            $this->Entity   = $this->Repository->findOneBySeq($douyin_id);
            if(empty($this->Entity)){
                throw new SystemException('抖音账号不存在。');
            } 
        }
        return $this->Entity;
    }
    
    /**
     * 
     * @param string $open_id
     * @return Entity
     */
    public function loadByOpenId(string $open_id) : Entity
    {
        $this->Entity   = $this->Repository->findOneByOpenId($open_id);
        if(empty($this->Entity)){
            throw new SystemException('抖音账号不存在。');
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
        $this->Entity->setOpenId($data['open_id']);
        $this->Entity->setScope($data['scope']);
        $this->Entity->setAccessToken($data['access_token']);
        $this->Entity->setExpiresIn($data['expires_in']);
        $this->Entity->setRefreshExpiresIn($data['refresh_expires_in']);
        $this->Entity->setRefreshToken($data['refresh_token']);
        

        $this->validateCreate();

        $this->Entity->setCreateTime(time());
        $this->Entity->setCreateIp($this->ServerRequest->getClientIp());
        $this->Entity->setUpdateTime(time());
        $this->Entity->setUpdateIp($this->ServerRequest->getClientIp());

        $this->Db->getManager()->persist($this->Entity);

        return $this;
    }

    /**
     *
     * @return Manager
     */
    public function updateUserInfo(array $data) : Manager
    {
     
        $this->Entity->setAvatar($data['avatar']);
        $this->Entity->setCity($data['city']);
        $this->Entity->setCountry($data['country']);
        $this->Entity->setEAccountRole($data['e_account_role']);
        $this->Entity->setGender($data['gender']);
        $this->Entity->setNickname($data['nickname']);
        $this->Entity->setProvince($data['province']);
        $this->Entity->setUnionId($data['union_id']);

        $this->validateUpdateUserInfo();

        $this->Entity->setUpdateTime(time());
        $this->Entity->setUpdateIp($this->ServerRequest->getClientIp());
        
        $this->Db->getManager()->lock($this->Entity, LockMode::OPTIMISTIC);

        return $this;
    }

    /**
     * 重新授权
     * 
     * @param array $data
     * @return Manager
     */
    public function updateReauth(array $data) : Manager
    {
        
        $this->Entity->setScope($data['scope']);
        $this->Entity->setAccessToken($data['access_token']);
        $this->Entity->setExpiresIn($data['expires_in']);
        $this->Entity->setRefreshExpiresIn($data['refresh_expires_in']);
        $this->Entity->setRefreshToken($data['refresh_token']);
        
        $this->validateUpdateReauth();
        
        $this->Entity->setUpdateTime(time());
        $this->Entity->setUpdateIp($this->ServerRequest->getClientIp());
        
        $this->Db->getManager()->lock($this->Entity, LockMode::OPTIMISTIC);
        
        return $this;
    }
    
    public function validateCreate()
    {
        
    }

    public function validateUpdateUserInfo()
    {
        
    }
    
    public function validateUpdateReauth()
    {
        
    }
}