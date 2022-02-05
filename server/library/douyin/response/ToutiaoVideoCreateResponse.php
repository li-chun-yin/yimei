<?php
namespace library\douyin\response;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月3日
 */
class ToutiaoVideoCreateResponse extends ResponseAbstract
{
    /**
     * 
        {
          "extra": {
            "logid": "202008121419360101980821035705926A",
            "now": 1597213176393,
            "error_code": 0,
            "description": "",
            "sub_error_code": 0,
            "sub_description": ""
          },
          "data": {
            "item_id": "@8hxdhauTCMppanGnM4ltGM780mDqPP+KPpR0qQOmLVAXb/T060zdRmYqig357zEBq6CZRp4NVe6qLIJW/V/x1w=="
          }
        }
     */
    public function getFieldNames() : array
    {
        return [
            "item_id",
        ];
    }
}