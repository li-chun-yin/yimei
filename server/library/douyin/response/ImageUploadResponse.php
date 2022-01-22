<?php
namespace library\douyin\response;

use exception\SystemException;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月3日
 */
class ImageUploadResponse extends ResponseAbstract
{
    /**
     * 
        {
          "data": {
            "description": "",
            "error_code": "0",
            "image": {
              "height": "360",
              "image_id": "<nil>",
              "width": "360"
            }
          },
          "extra": {
            "description": "",
            "error_code": "0",
            "logid": "202008121419360101980821035705926A",
            "now": "1597213176393",
            "sub_description": "",
            "sub_error_code": "0"
          }
        }
     */
    public function getFieldNames() : array
    {
        return [
            "image",
        ];
    }
    
    protected function checkResponse(string $json, $decoded_json) : void
    {
        parent::checkResponse($json, $decoded_json);
        if(!array_key_exists('image_id', $decoded_json['data']['image'])){
            throw new SystemException(sprintf('抖音的响应结果缺少image_id[%s]', $json));
        }
    }
}