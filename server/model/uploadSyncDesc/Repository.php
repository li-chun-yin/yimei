<?php
namespace model\uploadSyncDesc;

use asbamboo\database\FactoryInterface;
use asbamboo\database\EntityRepository;
use asbamboo\http\ServerRequestInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

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
        $this->Db                   = $Db;
        $this->Repository           = $this->Db->getManager()->getRepository(Entity::class);
    }

    /**
     * 通过upload_id查询一个上传信息
     *
     * @param string $upload_id
     * @return Entity|NULL
     */
    public function findOneByUploadId(string $upload_id) : ?Entity
    {
        return $this->Repository->findOneBy(['upload_id' => $upload_id]);
    }

    /**
     *
     * @param ServerRequestInterface $Request
     * @return array
     */
    public function getPageLists(ServerRequestInterface $Request) : Paginator
    {
        /**
         * request params
         */
        $page               = $Request->getRequestParam('page', 1);
        $limit              = $Request->getRequestParam('limit', 20);
        
        /**
         * query builder
         *
         * @var \Doctrine\ORM\QueryBuilder $queryBuilder
         */
        $queryBuilder   = $this->Repository->createQueryBuilder('t');
        $queryBuilder->orderBy('t.seq', 'DESC');
        $queryBuilder->setFirstResult(($page - 1) * $limit);
        $queryBuilder->setMaxResults($limit);
        
        /**
         * where
         */
        $andx       = $queryBuilder->expr()->andX();
        $has_where  = false;
                
        if($has_where == true){
            $queryBuilder->where($andx);
        }
        
        /**
         * return
         */
        return new Paginator($queryBuilder);
    }
}