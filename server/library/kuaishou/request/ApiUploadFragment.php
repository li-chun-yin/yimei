<?php
namespace library\kuaishou\request;

use library\kuaishou\gateway\GatewayUriTrait;
use library\kuaishou\request\tool\CreateRequestTrait;
use library\kuaishou\request\tool\UriTrait;

/**
 * 分片上传
 *
 * @author 李春寅<licy2013@aliyun.com>
 * @since 2022年2月19日
 */
class ApiUploadFragment implements RequestInterface
{
    use GatewayUriTrait;
    use UriTrait;
    use CreateRequestTrait;

    /**
     * 接口请求的method参数的固定值
     *
     * @var string
     */
    private $path = '/api/upload/fragment';

    /**
     *
     * @var array
     */
    private $assign_data = [];

    /**
     * 传入一个数组生成stream body
     *
     * @param array $assign_data
     */
    public function body()
    {
        return $this->contents;
    }

    /**
     * ['upload_http', 'upload_token']
     * {@inheritDoc}
     * @see RequestInterface::assignData()
     */
    public function assignData(array $assign_data): RequestInterface
    {
        $this->setGateway($assign_data['upload_http']);

        $this->query_data['fragment_id']    = $assign_data['fragment_id'];
        $this->query_data['upload_token']   = $assign_data['upload_token'];

        unset($assign_data['upload_http'], $assign_data['fragment_id'], $assign_data['upload_token']);

        $this->assign_data['contents']      = $assign_data['contents'];

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