<?php
namespace api\store\handler\v1_0\kuaishou;

use asbamboo\api\apiStore\ApiClassInterface;
use asbamboo\api\apiStore\ApiRequestParamsInterface;
use asbamboo\api\apiStore\ApiResponseParamsInterface;
use exception\MessageException;
use asbamboo\api\exception\ApiException;
use asbamboo\http\ServerRequestInterface;
use api\store\parameter\v1_0\kuaishou\idDisabled\IdDisabledResponse;
use model\kuaishouId\Manager AS KuaishouIdManager;
use asbamboo\database\FactoryInterface AS DbFactoryInterface;

/**
 * @name 快手账号禁用
 * @desc 可以禁用或者启用账号
 * @request api\store\parameter\v1_0\kuaishou\idDisabled\IdDisabledRequest
 * @response api\store\parameter\v1_0\kuaishou\idDisabled\IdDisabledResponse
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2021年8月27日
 */
class IdDisabled implements ApiClassInterface
{
    /**
     *
     * @var ServerRequestInterface $Request
     */
    private $KuaishouIdRepository, $Request;

    /**
     */
    public function __construct(KuaishouIdManager $KuaishouIdManager, DbFactoryInterface $Db, ServerRequestInterface $Request){
        $this->KuaishouIdManager    = $KuaishouIdManager;
        $this->Db                   = $Db;
        $this->Request              = $Request;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\api\apiStore\ApiClassInterface::exec()
     */
    public function exec(ApiRequestParamsInterface $Params): ?ApiResponseParamsInterface
    {
        try {
            $this->KuaishouIdManager->loadByOpenId($Params->getOpenId());
            $this->KuaishouIdManager->updateDisabled($Params->getDisabled());
            $this->Db->getManager()->flush();
            return new IdDisabledResponse();
        }catch(MessageException $e){
            throw new ApiException($e->getMessage());
        }
    }
}
