<?php
namespace library\douyin\response;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月3日
 */
class PoiBaseQueryAmapResponse extends ResponseAbstract
{
    /**
     * 
        {
          "data": {
            "address": "<nil>",
            "amap_id": "<nil>",
            "city": "<nil>",
            "description": "",
            "error_code": "0",
            "latitude": "<nil>",
            "longitude": "<nil>",
            "poi_id": "<nil>",
            "poi_name": "<nil>"
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
            "address",
            "amap_id",
            "city",
            "error_code",
            "latitude",
            "longitude",
            "poi_id",
            "poi_name",
        ];
    }
}