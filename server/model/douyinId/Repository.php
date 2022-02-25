<?php
namespace model\douyinId;

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
        $this->Db           = $Db;
        $this->Repository   = $this->Db->getManager()->getRepository(Entity::class);
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
     * @param string $open_id
     * @return Entity|NULL
     */
    public function findOneByOpenId(string $open_id) : ?Entity
    {
        return $this->Repository->findOneBy(['open_id' => $open_id]);
    }

    /**
     *
     * 默认查询可用的账号
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
        $disabled           = $Request->getRequestParam('disabled', '0');

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

        if(strlen($disabled) == 1){
            $andx->add($queryBuilder->expr()->eq('t.disabled', ':disabled'));
            $queryBuilder->setParameter('disabled', $disabled);
            $has_where = true;
        }

        if($has_where == true){
            $queryBuilder->where($andx);
        }

        /**
         * return
         */
        return new Paginator($queryBuilder);
    }

    /**
     * 返回可用的open_id集合
     * @return array
     */
    public function getAllOpenIds() : array
    {
        $data = $this->Repository->createQueryBuilder('t')->where('t.disabled = 0')->select(['t.open_id'])->getQuery()->getArrayResult();
        return empty($data) ? [] : array_column($data, 'open_id');
    }
}