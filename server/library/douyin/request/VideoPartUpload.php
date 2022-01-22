<?php
namespace library\douyin\request;

use library\douyin\gateway\GatewayUriTrait;
use library\douyin\request\tool\UriTrait;
use library\douyin\request\tool\CreateRequestTrait;

/**
 * 上传视频
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月3日
 */
class VideoPartUpload implements RequestInterface
{
    use CreateRequestTrait;
    use GatewayUriTrait;
    use UriTrait;

    /**
     * 接口请求的method参数的固定值
     *
     * @var string
     */
    private $path = '/video/part/upload/';

    private $assign_data = [];
    
    /**
     * 传入一个数组生成stream body
     *
     * @param array $assign_data
     */
    public function body()
    {
        return ['video' => new \CURLFile(realpath($this->assign_data['filepath']), $this->assign_data['mime_type'], $this->assign_data['filename'])];
    }
    
    /**
     *
     * {@inheritDoc}
     * @see RequestInterface::assignData()
     */
    public function assignData(array $assign_data): RequestInterface
    {
        $this->query_data['open_id']        = $assign_data['open_id'];
        $this->query_data['access_token']   = $assign_data['access_token'];
        $this->query_data['upload_id']      = $assign_data['upload_id'];
        $this->query_data['part_number']    = $assign_data['part_number'];
        unset($assign_data['open_id']);
        unset($assign_data['access_token']);
        unset($assign_data['upload_id']);
        unset($assign_data['part_number']);
        
        $this->assign_data['filepath']      = $assign_data['filepath'];
        $this->assign_data['filename']      = $assign_data['filename'];
        $this->assign_data['mime_type']     = $assign_data['mime_type'];
        
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