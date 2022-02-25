<?php
namespace api\store\handler\v1_0\xigua;

use asbamboo\api\apiStore\ApiClassInterface;
use asbamboo\api\apiStore\ApiRequestParamsInterface;
use asbamboo\api\apiStore\ApiResponseParamsInterface;
use exception\MessageException;
use asbamboo\api\exception\ApiException;
use asbamboo\http\ServerRequestInterface;
use api\store\parameter\v1_0\xigua\idDisabled\IdDisabledResponse;
use model\xiguaId\Manager AS XiguaIdManager;
use asbamboo\database\FactoryInterface AS DbFactoryInterface;

/**
 * @name 西瓜账号禁用
 * @desc 可以禁用或者启用账号
 * @request api\store\parameter\v1_0\xigua\idDisabled\IdDisabledRequest
 * @response api\store\parameter\v1_0\xigua\idDisabled\IdDisabledResponse
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
    private $XiguaIdRepository, $Request;

    /**
     */
    public function __construct(XiguaIdManager $XiguaIdManager, DbFactoryInterface $Db, ServerRequestInterface $Request){
        $this->XiguaIdManager   = $XiguaIdManager;
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
            $this->XiguaIdManager->loadByOpenId($Params->getOpenId());
            $this->XiguaIdManager->updateDisabled($Params->getDisabled());
            $this->Db->getManager()->flush();
            return new IdDisabledResponse();
        }catch(MessageException $e){
            throw new ApiException($e->getMessage());
        }
    }
}
