<?php
namespace library\douyin\response;

use exception\SystemException;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月3日
 */
class XiguaVideoPartCompleteResponse extends ResponseAbstract
{
    /**
     * 
        {
          "data": {
            "description": "",
            "error_code": "0",
            "video": {
              "height": "1280",
              "video_id": "v0200f450000bn8c6aa0ifki8fikg1b0",
              "width": "720"
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
            "video",
        ];
    }

    protected function checkResponse(string $json, $decoded_json) : void
    {
        parent::checkResponse($json, $decoded_json);
        if(!isset($decoded_json['data']['video']['video_id'])){
            throw new SystemException(sprintf('抖音的响应结果缺少video_id[%s]', $json));
        }
    }
}