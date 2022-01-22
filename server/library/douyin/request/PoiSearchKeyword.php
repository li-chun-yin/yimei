<?php
namespace library\douyin\request;

use library\douyin\gateway\GatewayUriTrait;
use library\douyin\request\tool\BodyTrait;
use library\douyin\request\tool\CreateRequestTrait;
use library\douyin\request\tool\UriTrait;

/**
 * 换取应用授权令牌
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月3日
 */
class PoiSearchKeyword implements RequestInterface
{
    use GatewayUriTrait;
    use BodyTrait;
    use UriTrait;
    use CreateRequestTrait;
    
    /**
     * 接口请求的method参数的固定值
     *
     * @var string
     */
    private $path = '/poi/search/keyword/';
    
    public function __construct()
    {
        $this->http_method  = 'GET';
    }

    /**
     *
     * {@inheritDoc}
     * @see RequestInterface::assignData()
     */
    public function assignData(array $assign_data): RequestInterface
    {
        $this->query_data['access_token']  = $assign_data['access_token'];
        $this->query_data['count']         = $assign_data['count'];
        $this->query_data['keyword']       = $assign_data['keyword'];
        foreach(['cursor', 'city'] AS $k){
            if(isset($assign_data[$k])){
                $this->query_data[$k]          = $assign_data[$k];
            }
        }
        
        return $this;
    }

    /**
     *
     * @return array|NULL
     */
    public function getAssignData() : ?array
    {
        return $this->assign_data;
    }
}