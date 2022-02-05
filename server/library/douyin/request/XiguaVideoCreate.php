<?php
namespace library\douyin\request;

use library\douyin\gateway\GatewayUriTrait;
use library\douyin\request\tool\BodyTrait;
use library\douyin\request\tool\CreateRequestTrait;
use library\douyin\request\tool\UriTrait;

/**
 * 创建西瓜视频
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月3日
 */
class XiguaVideoCreate implements RequestInterface
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
    private $path = '/xigua/video/create/';

    /**
     *
     * {@inheritDoc}
     * @see RequestInterface::assignData()
     */
    public function assignData(array $assign_data): RequestInterface
    {
        $this->query_data['open_id']       = $assign_data['open_id'];
        $this->query_data['access_token']  = $assign_data['access_token'];
        
        $this->assign_data['video_id']     = $assign_data['video_id'];
        foreach([
            'text',
            'abstract', // 视频简介
            'claim_origin', // 是否原创
            'cover_tsp', //从视频中截取封面的时间，用该帧作为封面（单位为毫秒）
            'praise', // 是否给视频开通可以赞赏的入口（授权账号需要在西瓜视频端内开通「实名认证」）
        ] AS $t){            
            if(isset($assign_data[$t])){    
                $this->assign_data[$t]          = $assign_data[$t];
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