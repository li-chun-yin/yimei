<?php
namespace library\douyin\response;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月3日
 */
class XiguaVideoPartInitResponse extends ResponseAbstract
{
    /**
     * 
        {
          "data": {
            "description": "",
            "error_code": "0",
            "upload_id": "@8hxdhauTCM"
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
            "upload_id",
        ];
    }
}