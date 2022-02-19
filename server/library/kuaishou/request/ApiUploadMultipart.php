<?php
namespace library\kuaishou\request;

use library\kuaishou\gateway\GatewayUriTrait;
use library\kuaishou\request\tool\CreateRequestTrait;
use library\kuaishou\request\tool\UriTrait;

/**
 * 视频上传
 *
 * @author 李春寅<licy2013@aliyun.com>
 * @since 2022年2月19日
 */
class ApiUploadMultipart implements RequestInterface
{
    use GatewayUriTrait;
    use UriTrait;
    use CreateRequestTrait;

    /**
     * 接口请求的method参数的固定值
     *
     * @var string
     */
    private $path = '/api/upload/multipart';

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
        return ['file' => new \CURLFile(realpath($this->assign_data['filepath']), $this->assign_data['mime_type'], $this->assign_data['filename'])];
    }

    /**
     * ['upload_http', 'upload_token']
     * {@inheritDoc}
     * @see RequestInterface::assignData()
     */
    public function assignData(array $assign_data): RequestInterface
    {
        $this->setGateway($assign_data['upload_http']);

        $this->query_data['upload_token']   = $assign_data['upload_token'];

        unset($assign_data['upload_http'], $assign_data['upload_token']);

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