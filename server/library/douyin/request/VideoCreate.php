<?php
namespace library\douyin\request;

use library\douyin\gateway\GatewayUriTrait;
use library\douyin\request\tool\BodyTrait;
use library\douyin\request\tool\CreateRequestTrait;
use library\douyin\request\tool\UriTrait;

/**
 * 分片上传完成
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月3日
 */
class VideoCreate implements RequestInterface
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
    private $path = '/video/create/';

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
            'poi_id',
            'poi_name',
            'micro_app_id',
            'micro_app_title',
            'article_id',
            'article_title',
            'timeliness_label',
            'timeliness_keyword',
            'game_id',
            'game_content',
            'cover_tsp',
            'at_users', // string[]
            'micro_app_url',
            'custom_cover_image_url',
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