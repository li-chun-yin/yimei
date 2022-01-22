<?php
namespace model\uploadSync;

use asbamboo\database\FactoryInterface;
use asbamboo\database\EntityRepository;

/**
 * 查询管理
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月28日
 */
class Repository
{

    /**
     *
     * @var FactoryInterface
     */
    private $Db;

    /**
     *
     * @var EntityRepository
     */
    private $Repository;

    /**
     *
     * @param FactoryInterface $Db
     */
    public function __construct(FactoryInterface $Db)
    {
        $this->Db           = $Db;
        $this->Repository   = $this->Db->getManager()->getRepository(Entity::class);
    }

    /**
     * 
     * @param string $status
     * @return Entity|NULL
     */
    public function findOneByStatusIng() : ?Entity
    {
        return $this->Repository->findOneBy(['status' => Code::STATUS_ING]);
    }
    
    /**
     *
     * @param string $seq
     * @return Entity|NULL
     */
    public function findOneBySeq(string $seq) : ?Entity
    {
        return $this->Repository->findOneBy(['seq' => $seq]);
    }

    /**
     * 
     * @param string $upload_id
     * @return Entity[]
     */
    public function findByUploadId(string $upload_id) : array
    {
        return $this->Repository->findBy(['upload_id' => $upload_id]);
    }
}