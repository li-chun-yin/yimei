<?php
namespace library\kuaishou\request;

use library\kuaishou\gateway\GatewayUriTrait;
use library\kuaishou\request\tool\CreateRequestTrait;
use library\kuaishou\request\tool\UriTrait;

/**
 * 发起上传
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月3日
 */
class OpenapiPhotoPublish implements RequestInterface
{
    use GatewayUriTrait;
    use UriTrait;
    use CreateRequestTrait;

    /**
     * 接口请求的method参数的固定值
     *
     * @var string
     */
    private $path = '/openapi/photo/publish';

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
        return $this->assign_data;
    }

    /**
     *
     * {@inheritDoc}
     * @see RequestInterface::assignData()
     */
    public function assignData(array $assign_data): RequestInterface
    {
        $this->query_data['app_id']         = $assign_data['app_id'];
        $this->query_data['access_token']   = $assign_data['access_token'];
        $this->query_data['upload_token']   = $assign_data['upload_token'];

        unset($assign_data['app_id'], $assign_data['access_token'], $assign_data['upload_token']);

        $this->assign_data['cover']         = $assign_data['cover'];
        $this->assign_data['caption']       = $assign_data['caption'];

        foreach(['stereo_type', 'merchant_product_id'] AS $feild){
            if(array_key_exists($feild, $assign_data)){
                $this->assign_data[$feild]   = $assign_data[$feild];
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