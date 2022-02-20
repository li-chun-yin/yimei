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
class ToutiaoVideoPartComplete implements RequestInterface
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
    private $path = '/toutiao/video/part/upload/';

    /**
     *
     * {@inheritDoc}
     * @see RequestInterface::assignData()
     */
    public function assignData(array $assign_data): RequestInterface
    {
        $this->query_data['open_id']       = $assign_data['open_id'];
        $this->query_data['access_token']  = $assign_data['access_token'];
        $this->query_data['upload_id']      = $assign_data['upload_id'];

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