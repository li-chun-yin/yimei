<?php
namespace api\store\handler\v1_0\douyin;

use asbamboo\api\apiStore\ApiClassInterface;
use asbamboo\api\apiStore\ApiRequestParamsInterface;
use asbamboo\api\apiStore\ApiResponseParamsInterface;
use exception\MessageException;
use asbamboo\api\exception\ApiException;
use model\douyinId\Repository AS DouyinIdRepository;
use asbamboo\http\ServerRequestInterface;
use api\store\parameter\v1_0\douyin\idList\IdListResponse;
use model\douyinId\Entity;

/**
 * @name 抖音账号列表
 * @desc
 * @request api\store\parameter\v1_0\douyin\idList\IdListRequest
 * @response api\store\parameter\v1_0\douyin\idList\IdListResponse
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2021年8月27日
 */
class IdList implements ApiClassInterface
{
    /**
     *
     * @var DouyinIdRepository $DouyinIdRepository
     * @var ServerRequestInterface $Request
     */
    private $DouyinIdRepository, $Request;

    /**
     */
    public function __construct(DouyinIdRepository $DouyinIdRepository, ServerRequestInterface $Request){
        $this->DouyinIdRepository   = $DouyinIdRepository;
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
             * @var Entity $douyinIdEntity
             */
            $Paginator  = $this->DouyinIdRepository->getPageLists($this->Request);
            $items      = [];
            foreach($Paginator->getIterator() AS $douyinIdEntity){
                $items[]            = [
                    'open_id'       => $douyinIdEntity->getOpenId(),
                    'nickname'      => $douyinIdEntity->getNickname(),
                    'avatar'        => $douyinIdEntity->getAvatar(),
                    'disabled'      => $douyinIdEntity->getDisabled(),
                    'update_time'   => $douyinIdEntity->getUpdateTime(),
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
