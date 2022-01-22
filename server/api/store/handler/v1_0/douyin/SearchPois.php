<?php
namespace api\store\handler\v1_0\douyin;

use asbamboo\api\apiStore\ApiClassInterface;
use asbamboo\api\apiStore\ApiRequestParamsInterface;
use asbamboo\api\apiStore\ApiResponseParamsInterface;
use exception\MessageException;
use asbamboo\api\exception\ApiException;
use asbamboo\http\ServerRequestInterface;
use library\douyin\ApiClient;
use model\setting\Manager AS SettingManager;
use library\douyin\ClientToken;
use api\store\parameter\v1_0\douyin\searchPois\SearchPoisResponse;

/**
 * @name 获取周边信息
 * @desc 根据高德开放api查询，地址的周边信息.
 * @request api\store\parameter\v1_0\douyin\searchPois\SearchPoisRequest
 * @response api\store\parameter\v1_0\douyin\searchPois\SearchPoisResponse
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2021年8月27日
 */
class SearchPois implements ApiClassInterface
{
    
    private $Request;
    /**
     */
    public function __construct(ServerRequestInterface $Request, SettingManager $SettingManager){
        $this->Request          = $Request;
        $this->SettingManager   = $SettingManager;
    }
    
    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\api\apiStore\ApiClassInterface::exec()
     */
    public function exec(ApiRequestParamsInterface $Params): ?ApiResponseParamsInterface
    {
        try {
            $PoiSearchKeywordResponse   = ApiClient::request('PoiSearchKeyword', [
                'access_token'          => ClientToken::getFreshAccessToken($this->SettingManager),
                'cursor'                => $Params->getCursor(),
                'count'                 => $Params->getCount(),
                'keyword'               => $Params->getKeyword(),
                'city'                  => $Params->getCity(),
            ]);
            return new SearchPoisResponse([
                'cursor'        => $PoiSearchKeywordResponse->get('cursor'),
                'has_more'      => !empty($PoiSearchKeywordResponse->get('has_more')),
                'items'         => $PoiSearchKeywordResponse->get('pois'),
            ]);
            
        }catch(MessageException $e){
            throw new ApiException($e->getMessage());
        }
    }
}
