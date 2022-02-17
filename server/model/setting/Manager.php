<?php
namespace model\setting;

use asbamboo\database\FactoryInterface;
use asbamboo\http\ServerRequestInterface;
use Doctrine\DBAL\LockMode;
use exception\NotFoundSettingException;

/**
 * 管理账号表的操作
 *  - 控制 增/删/改
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月28日
 */
class Manager
{
    use Validator;

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
     * @throws NotFoundSettingException
     * @return Entity
     */
    public function load(/*int|Entity*/ $account = null) : Entity
    {
        if(is_null($account)){
            $this->Entity   = new Entity();
        }else if($account instanceof Entity){
            $this->Entity   = $account;
        }else{
            $this->Entity   = $this->Repository->findOneBySeq($account);
            if(empty($this->Entity)){
                throw new NotFoundSettingException('配置信息不存在。');
            }
        }
        return $this->Entity;
    }

    public function loadByType(string $type) : Entity
    {
        $this->Entity   = $this->Repository->findOneByType($type);
        if(empty($this->Entity)){
            throw new NotFoundSettingException('该类型配置信息不存在。');
        }
        return $this->Entity;
    }

    /**
     * 创建数据行
     *
     * @return Manager
     */
    public function createByDouyin(array $data) : Manager
    {
        $this->Entity->setData([
            'client_key'    => $data['client_key'],
            'client_secret' => $data['client_secret'],
        ]);
        $this->Entity->setType(Code::TYPE_DOUYIN);

        $this->validateCreateByDouyin();

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
    public function updateByDouyin(array $data) : Manager
    {
        $this->Entity->setData([
            'client_key'    => $data['client_key'],
            'client_secret' => $data['client_secret'],
            'access_token'  => $data['access_token'] ?: null,
            'expires_in'    => $data['expires_in'] ?: null,
        ]);

        $this->validateUpdateByDouyin();

        $this->Entity->setUpdateTime(time());
        $this->Entity->setUpdateIp($this->ServerRequest->getClientIp());

        $this->Db->getManager()->lock($this->Entity, LockMode::OPTIMISTIC);

        return $this;
    }

    /**
     * 创建数据行
     *
     * @return Manager
     */
    public function createByKuaishou(array $data) : Manager
    {
        $this->Entity->setData([
            'app_id'        => $data['app_id'],
            'app_secret'    => $data['app_secret'],
        ]);
        $this->Entity->setType(Code::TYPE_KUAISHOU);

        $this->validateCreateByKuaishou();

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
    public function updateByKuaishou(array $data) : Manager
    {
        $this->Entity->setData([
            'app_id'        => $data['app_id'],
            'app_secret'    => $data['app_secret'],
        ]);

        $this->validateUpdateByKuaishou();

        $this->Entity->setUpdateTime(time());
        $this->Entity->setUpdateIp($this->ServerRequest->getClientIp());

        $this->Db->getManager()->lock($this->Entity, LockMode::OPTIMISTIC);

        return $this;
    }

    /**
     * 启用
     *
     * @return Manager
     */
    public function updateEnable() : Manager
    {
        $this->validateUpdateEnable();

        $this->Entity->setIsEnable(true);
        $this->Entity->setUpdateTime(time());
        $this->Entity->setUpdateUser($this->UserToken->getUser()->getUserId());

        $this->Db->getManager()->lock($this->Entity, LockMode::OPTIMISTIC);

        return $this;
    }

    public function validateCreateByKuaishou()
    {

    }

    public function validateUpdateByKuaishou()
    {

    }
}