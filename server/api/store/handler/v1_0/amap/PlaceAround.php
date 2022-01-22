<?php
namespace api\store\handler\v1_0\amap;

use asbamboo\api\apiStore\ApiClassInterface;
use asbamboo\api\apiStore\ApiRequestParamsInterface;
use asbamboo\api\apiStore\ApiResponseParamsInterface;
use exception\MessageException;
use asbamboo\api\exception\ApiException;
use api\store\parameter\v1_0\amap\placeAround\PlaceAroundResponse;
use asbamboo\http\ServerRequestInterface;

/**
 * @name 获取周边信息
 * @desc 根据高德开放api查询，地址的周边信息.
 * @request api\store\parameter\v1_0\amap\placeAround\PlaceAroundRequest
 * @response api\store\parameter\v1_0\amap\placeAround\PlaceAroundResponse
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2021年8月27日
 */
class PlaceAround implements ApiClassInterface
{

    private $Request;
    /**
     */
    public function __construct(ServerRequestInterface $Request){
        $this->Request  = $Request;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\api\apiStore\ApiClassInterface::exec()
     */
    public function exec(ApiRequestParamsInterface $Params): ?ApiResponseParamsInterface
    {
        try {           
            $json               = file_get_contents('https://restapi.amap.com/v5/place/around' . '?' . http_build_query([
                'key'           => \Vars::instance()->get('amap_key'),
                'keywords'      => $Params->getKeywords(),
                'location'      => implode(',', [$Params->getLongitude(), $Params->getLatitude()]),
            ]));
            
            $decoded_json       = json_decode($json, true);
            
            if(empty($decoded_json['status'])){
                throw new MessageException('查询周边信息失败.');
            }
            
            return new PlaceAroundResponse([
                'total'         => $decoded_json['count'],
                'items'         => $decoded_json['pois'],
            ]);
            
        }catch(MessageException $e){
            throw new ApiException($e->getMessage());
        }
    }
}
