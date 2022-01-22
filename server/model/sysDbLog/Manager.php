<?php
namespace model\sysDbLog;

use asbamboo\security\user\token\UserTokenInterface;
use asbamboo\database\FactoryInterface;
use asbamboo\http\ServerRequestInterface;
use asbamboo\security\user\AnonymousUser;
use exception\SystemException;

class Manager
{
    /**
     *
     * @var FactoryInterface
     */
    private $Db;

    /**
     *
     * @var UserTokenInterface
     */
    private $UserToken;

    /**
     *
     * @var ServerRequestInterface
     */
    private $Request;

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
    public function __construct(FactoryInterface $Db, UserTokenInterface $UserToken, ServerRequestInterface $Request, Repository $Repository)
    {
        $this->Db               = $Db;
        $this->UserToken        = $UserToken;
        $this->Request          = $Request;
        $this->Repository       = $Repository;
    }

    /**
     *
     * @param string|Entity $account
     * @return Entity
     */
    public function load(/*string|Entity*/ $sys_db_log = null) : Entity
    {
        if(is_null($sys_db_log)){
            $this->Entity   = new Entity();
        }else if($sys_db_log instanceof Entity){
            $this->Entity   = $sys_db_log;
        }else{
            $this->Entity   = $this->Repository->findOneBySeq($sys_db_log);
            if(empty($this->Entity)){
                throw new SystemException('数据变更日志不存在。');
            }
        }
        return $this->Entity;
    }

    /**
     * 创建一个数据日志
     *
     * @param array $data
     * @return Manager
     */
    public function create(array $data) : Manager
    {
        $LoginUser  = $this->UserToken->getUser();
        $user_id    = $LoginUser instanceof AnonymousUser ? '' : $LoginUser->getUserId();

        $this->Entity->setType($data['type']);
        $this->Entity->setTable($data['table']);
        $this->Entity->setUniqid($data['uniqid']);
        $this->Entity->setData($data['data']);
        $this->Entity->setRequest($data['request']);
        $this->Entity->setCreateUser($user_id);
        $this->Entity->setCreateTime(time());
        $this->Entity->setCreateIp($this->Request->getClientIp());

        $this->Db->getManager()->persist($this->Entity);

        return $this;
    }
}
