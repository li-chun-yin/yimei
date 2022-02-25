<?php
namespace api\store\handler\v1_0\toutiao;

use asbamboo\api\apiStore\ApiClassInterface;
use asbamboo\api\apiStore\ApiRequestParamsInterface;
use asbamboo\api\apiStore\ApiResponseParamsInterface;
use exception\MessageException;
use asbamboo\api\exception\ApiException;
use asbamboo\http\ServerRequestInterface;
use api\store\parameter\v1_0\toutiao\idDisabled\IdDisabledResponse;
use model\toutiaoId\Manager AS ToutiaoIdManager;
use asbamboo\database\FactoryInterface AS DbFactoryInterface;

/**
 * @name 头条账号禁用
 * @desc 可以禁用或者启用账号
 * @request api\store\parameter\v1_0\toutiao\idDisabled\IdDisabledRequest
 * @response api\store\parameter\v1_0\toutiao\idDisabled\IdDisabledResponse
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
    private $ToutiaoIdRepository, $Request;

    /**
     */
    public function __construct(ToutiaoIdManager $ToutiaoIdManager, DbFactoryInterface $Db, ServerRequestInterface $Request){
        $this->ToutiaoIdManager = $ToutiaoIdManager;
        $this->Db               = $Db;
        $this->Request          = $Request;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\api\apiStore\ApiClassInterface::exec()
     */
    public function exec(ApiRequestParamsInterface $Params): ?ApiResponseParamsInterface
    {
        try {
            $this->ToutiaoIdManager->loadByOpenId($Params->getOpenId());
            $this->ToutiaoIdManager->updateDisabled($Params->getDisabled());
            $this->Db->getManager()->flush();
            return new IdDisabledResponse();
        }catch(MessageException $e){
            throw new ApiException($e->getMessage());
        }
    }
}
