<?php
namespace api\store\handler\v1_0\amap;

use asbamboo\api\apiStore\ApiClassInterface;
use asbamboo\api\apiStore\ApiRequestParamsInterface;
use asbamboo\api\apiStore\ApiResponseParamsInterface;
use exception\MessageException;
use asbamboo\api\exception\ApiException;
use api\store\parameter\v1_0\amap\placeAround\PlaceAroundResponse;
use asbamboo\http\ServerRequestInterface;
use api\store\parameter\v1_0\amap\ipLocation\IpLocationResponse;

/**
 * @name IP定位
 * @desc 根据高德开放api查询，查询IP相关的地址信息
 * @request api\store\parameter\v1_0\amap\ipLocation\IpLocationRequest
 * @response api\store\parameter\v1_0\amap\ipLocation\IpLocationResponse
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2021年8月27日
 */
class IpLocation implements ApiClassInterface
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
            $defult_ip          = $this->Request->getClientIp();
            if($defult_ip == '127.0.0.1'){
                $defult_ip  = "120.204.76.161";
            }
            $json               = file_get_contents('https://restapi.amap.com/v5/ip' . '?' . http_build_query([
                'key'           => \Vars::instance()->get('amap_key'),
                'type'          => $Params->getType(),
                'ip'            => $Params->getIp() ?: $defult_ip,
            ]));
            
            $decoded_json       = json_decode($json, true);
            
            if(empty($decoded_json['status'])){
                throw new MessageException('查询周边信息失败.');
            }
            
            return new IpLocationResponse([
                'country'   => $decoded_json['country'],
                'province'  => $decoded_json['province'],
                'city'      => $decoded_json['city'],
                'district'  => $decoded_json['district'],
                'isp'       => $decoded_json['isp'],
                'location'  => $decoded_json['location'],
                'ip'        => $decoded_json['ip'],
            ]);
            
        }catch(MessageException $e){
            throw new ApiException($e->getMessage());
        }
    }
}
