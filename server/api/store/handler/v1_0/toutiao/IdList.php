<?php
namespace api\store\handler\v1_0\toutiao;

use asbamboo\api\apiStore\ApiClassInterface;
use asbamboo\api\apiStore\ApiRequestParamsInterface;
use asbamboo\api\apiStore\ApiResponseParamsInterface;
use exception\MessageException;
use asbamboo\api\exception\ApiException;
use model\toutiaoId\Repository AS ToutiaoIdRepository;
use asbamboo\http\ServerRequestInterface;
use api\store\parameter\v1_0\toutiao\idList\IdListResponse;
use model\toutiaoId\Entity;

/**
 * @name 今日头条账号列表
 * @desc
 * @request api\store\parameter\v1_0\toutiao\idList\IdListRequest
 * @response api\store\parameter\v1_0\toutiao\idList\IdListResponse
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2021年8月27日
 */
class IdList implements ApiClassInterface
{
    /**
     *
     * @var ToutiaoIdRepository $ToutiaoIdRepository
     * @var ServerRequestInterface $Request
     */
    private $ToutiaoIdRepository, $Request;

    /**
     */
    public function __construct(ToutiaoIdRepository $ToutiaoIdRepository, ServerRequestInterface $Request){
        $this->ToutiaoIdRepository  = $ToutiaoIdRepository;
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
             * @var Entity $toutiaoIdEntity
             */
            $Paginator  = $this->ToutiaoIdRepository->getPageLists($this->Request);
            $items      = [];
            foreach($Paginator->getIterator() AS $toutiaoIdEntity){
                $items[]                = [
                    'open_id'           => $toutiaoIdEntity->getOpenId(),
                    'nickname'          => $toutiaoIdEntity->getNickname(),
                    'avatar'            => $toutiaoIdEntity->getAvatar(),
                    'expire_in_ymdhis'  => date('Y-m-d H:i:s', $toutiaoIdEntity->getRefreshExpiresIn()),
                    'update_time'       => $toutiaoIdEntity->getUpdateTime(),
                    'disabled'          => $toutiaoIdEntity->getDisabled(),
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
