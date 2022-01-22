<?php
namespace library\douyin\response;

use asbamboo\http\ResponseInterface AS HttpResponseInterface;
use exception\SystemException;

/**
 * 响应结果公共参数
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年10月12日
 */
abstract class ResponseAbstract implements ResponseInterface
{
    private $data = [];
    
    /**
     * 响应值data应包含的字段
     */
    abstract public function getFieldNames() : array;
    
    /**
     *
     * @param HttpResponseInterface $Response
     */
    public function __construct(HttpResponseInterface $Response)
    {
        $this->parseResponse($Response);
    }

    /**
     *
     * {@inheritDoc}
     * @see ResponseInterface::get()
     */
    public function get(string $key)
    {
        return $this->data[$key];
    }
    /**
     * 解析响应
     *
     * @param HttpResponseInterface $Response
     */
    protected function parseResponse(HttpResponseInterface $Response) : void
    {
        $json           = $Response->getBody()->getContents();
        $decoded_json   = json_decode($json, true);

        $this->checkResponse($json, $decoded_json);

        $this->data = $decoded_json['data'];
    }

    /**
     * 验证签名规则参阅支付宝的文档，和支付宝的demo
     *
     * @param string $json
     * @param array|string|mixed $decoded_json
     * @throws SystemException
     */
    protected function checkResponse(string $json, $decoded_json) : void
    {
        if(empty($decoded_json)){
            throw new SystemException(sprintf('抖音的响应结果异常[%s]', $json));
        }
        
        if(!empty($decoded_json['error_code'])){
            throw new SystemException(sprintf('抖音服务器提示:', $decoded_json['description']));
        }
        
        foreach($this->getFieldNames() AS $field){
            if(!array_key_exists($field, $decoded_json['data'])){
                throw new SystemException(sprintf('抖音的响应结果有字段缺失[%s]', $json));
            }
        }
    }
    
}