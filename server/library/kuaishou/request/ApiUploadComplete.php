<?php
namespace library\kuaishou\request;

use library\kuaishou\gateway\GatewayUriTrait;
use library\kuaishou\request\tool\CreateRequestTrait;
use library\kuaishou\request\tool\UriTrait;
use library\kuaishou\request\tool\BodyTrait;

/**
 * 分片上传
 *
 * @author 李春寅<licy2013@aliyun.com>
 * @since 2022年2月19日
 */
class ApiUploadComplete implements RequestInterface
{
    use GatewayUriTrait;
    use UriTrait;
    use BodyTrait;
    use CreateRequestTrait;

    /**
     * 接口请求的method参数的固定值
     *
     * @var string
     */
    private $path = '/api/upload/complete';

    /**
     * ['upload_http', 'upload_token']
     * {@inheritDoc}
     * @see RequestInterface::assignData()
     */
    public function assignData(array $assign_data): RequestInterface
    {
        $this->setGateway($assign_data['upload_http']);

        $this->query_data['fragment_count'] = $assign_data['fragment_count'];
        $this->query_data['upload_token']   = $assign_data['upload_token'];

        unset($assign_data['upload_http'], $assign_data['fragment_count'], $assign_data['upload_token']);

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