<?php
namespace library\douyin\response;

use exception\SystemException;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月3日
 */
class VideoPartUploadResponse extends ResponseAbstract
{
    /**
     * 
        {
          "data": {
            "description": "",
            "error_code": "0"
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
//             "description",
//             "error_code",
        ];
    }
}