<?php
namespace api\store\handler\v1_0\douyin;

use asbamboo\api\apiStore\ApiClassInterface;
use asbamboo\api\apiStore\ApiRequestParamsInterface;
use asbamboo\api\apiStore\ApiResponseParamsInterface;
use exception\MessageException;
use asbamboo\api\exception\ApiException;
use asbamboo\http\ServerRequestInterface;
use api\store\parameter\v1_0\douyin\idDisabled\IdDisabledResponse;
use model\douyinId\Manager AS DouyinIdManager;
use asbamboo\database\FactoryInterface AS DbFactoryInterface;

/**
 * @name 抖音账号禁用
 * @desc 可以禁用或者启用账号
 * @request api\store\parameter\v1_0\douyin\idDisabled\IdDisabledRequest
 * @response api\store\parameter\v1_0\douyin\idDisabled\IdDisabledResponse
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
    private $DouyinIdRepository, $Request;

    /**
     */
    public function __construct(DouyinIdManager $DouyinIdManager, DbFactoryInterface $Db, ServerRequestInterface $Request){
        $this->DouyinIdManager  = $DouyinIdManager;
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
            $this->DouyinIdManager->loadByOpenId($Params->getOpenId());
            $this->DouyinIdManager->updateDisabled($Params->getDisabled());
            $this->Db->getManager()->flush();
            return new IdDisabledResponse();
        }catch(MessageException $e){
            throw new ApiException($e->getMessage());
        }
    }
}
