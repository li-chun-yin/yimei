<?php
namespace api\store\handler\v1_0\xigua;

use asbamboo\api\apiStore\ApiClassInterface;
use asbamboo\api\apiStore\ApiRequestParamsInterface;
use asbamboo\api\apiStore\ApiResponseParamsInterface;
use exception\MessageException;
use asbamboo\api\exception\ApiException;
use model\xiguaId\Repository AS XiguaIdRepository;
use asbamboo\http\ServerRequestInterface;
use api\store\parameter\v1_0\xigua\idList\IdListResponse;
use model\xiguaId\Entity;

/**
 * @name 西瓜视频账号列表
 * @desc 
 * @request api\store\parameter\v1_0\xigua\idList\IdListRequest
 * @response api\store\parameter\v1_0\xigua\idList\IdListResponse
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2021年8月27日
 */
class IdList implements ApiClassInterface
{
    /**
     * 
     * @var XiguaIdRepository $XiguaIdRepository
     * @var ServerRequestInterface $Request
     */
    private $XiguaIdRepository, $Request;
    
    /**
     */
    public function __construct(XiguaIdRepository $XiguaIdRepository, ServerRequestInterface $Request){
        $this->XiguaIdRepository    = $XiguaIdRepository;
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
             * @var Entity $xiguaIdEntity
             */
            $Paginator  = $this->XiguaIdRepository->getPageLists($this->Request);
            $items      = [];
            foreach($Paginator->getIterator() AS $xiguaIdEntity){
                $items[]            = [
                    'open_id'       => $xiguaIdEntity->getOpenId(),
                    'nickname'      => $xiguaIdEntity->getNickname(),
                    'avatar'        => $xiguaIdEntity->getAvatar(),
                    'update_time'   => $xiguaIdEntity->getUpdateTime(),
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
