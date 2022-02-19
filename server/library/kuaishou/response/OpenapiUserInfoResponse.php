<?php
namespace library\kuaishou\response;

use exception\SystemException;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月3日
 */
class OpenapiUserInfoResponse extends ResponseAbstract
{
    /**
     *
{
   "result": 1,
   "user_info": {
	"name":"xxxx",
	"sex":"M",
	"fan":12,
	"follow":17,
	"head":"http://xxxx.png",
	"bigHead":"http://yyyyy.png",
	"city":"河北省石家庄市"
    }
}
     */
    public function getFieldNames() : array
    {
        return [
            "user_info",
        ];
    }

    protected function checkResponse(string $json, $decoded_json) : void
    {
        parent::checkResponse($json, $decoded_json);
        if(!isset($decoded_json['user_info']['name'])){
            throw new SystemException(sprintf('快手的响应结果缺少name[%s]', $json));
        }
        if(!isset($decoded_json['user_info']['sex'])){
            throw new SystemException(sprintf('快手的响应结果缺少sex[%s]', $json));
        }
        if(!isset($decoded_json['user_info']['fan'])){
            throw new SystemException(sprintf('快手的响应结果缺少fan[%s]', $json));
        }
        if(!isset($decoded_json['user_info']['follow'])){
            throw new SystemException(sprintf('快手的响应结果缺少follow[%s]', $json));
        }
        if(!isset($decoded_json['user_info']['head'])){
            throw new SystemException(sprintf('快手的响应结果缺少head[%s]', $json));
        }
        if(!isset($decoded_json['user_info']['bigHead'])){
            throw new SystemException(sprintf('快手的响应结果缺少bigHead[%s]', $json));
        }
        if(!isset($decoded_json['user_info']['city'])){
            throw new SystemException(sprintf('快手的响应结果缺少city[%s]', $json));
        }
    }
}