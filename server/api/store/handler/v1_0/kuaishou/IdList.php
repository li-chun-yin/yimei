<?php
namespace api\store\handler\v1_0\kuaishou;

use asbamboo\api\apiStore\ApiClassInterface;
use asbamboo\api\apiStore\ApiRequestParamsInterface;
use asbamboo\api\apiStore\ApiResponseParamsInterface;
use exception\MessageException;
use asbamboo\api\exception\ApiException;
use model\kuaishouId\Repository AS KuaishouIdRepository;
use asbamboo\http\ServerRequestInterface;
use api\store\parameter\v1_0\kuaishou\idList\IdListResponse;
use model\kuaishouId\Entity;

/**
 * @name 快手账号列表
 * @desc
 * @request api\store\parameter\v1_0\kuaishou\idList\IdListRequest
 * @response api\store\parameter\v1_0\kuaishou\idList\IdListResponse
 *
 * @author 李春寅<licy2013@aliyun.com>
 * @since 2022年2月19日
 */
class IdList implements ApiClassInterface
{
    /**
     *
     * @var KuaishouIdRepository $KuaishouIdRepository
     * @var ServerRequestInterface $Request
     */
    private $KuaishouIdRepository, $Request;

    /**
     */
    public function __construct(KuaishouIdRepository $KuaishouIdRepository, ServerRequestInterface $Request){
        $this->KuaishouIdRepository = $KuaishouIdRepository;
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
            /**
             *
             * @var Entity $KuaishouIdEntity
             */
            $Paginator  = $this->KuaishouIdRepository->getPageLists($this->Request);
            $items      = [];
            foreach($Paginator->getIterator() AS $KuaishouIdEntity){
                $items[]            = [
                    'open_id'       => $KuaishouIdEntity->getOpenId(),
                    'name'          => $KuaishouIdEntity->getName(),
                    'head'          => $KuaishouIdEntity->getHead(),
                    'update_time'   => $KuaishouIdEntity->getUpdateTime(),
                    'disabled'      => $KuaishouIdEntity->getDisabled(),
                ];
            }
            return new IdListResponse([
                'items'     => $items,
                'total'     => $Paginator->count(),
            ]);
        }catch(MessageException $e){
            throw new ApiException($e->getMessage());
        }
    }
}
