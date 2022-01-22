<?php
namespace model\setting;

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
     * @param string $setting_seq
     * @return Entity|NULL
     */
    public function findOneBySeq(string $setting_seq) : ?Entity
    {
        return $this->Repository->findOneBy(['seq' => $setting_seq]);
    }
    
    /**
     *
     * @param string $setting_type
     * @return Entity|NULL
     */
    public function findOneByType(string $setting_type) : ?Entity
    {
        return $this->Repository->findOneByType(['type' => $setting_type]);
    }
}