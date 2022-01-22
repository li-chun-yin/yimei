<?php
namespace library\douyin\response;

use exception\SystemException;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月3日
 */
class PoiSearchKeywordResponse extends ResponseAbstract
{
    /**
     * 
        {
          "data": {
            "cursor": "<nil>",
            "description": "",
            "error_code": "0",
            "has_more": "<nil>",
            "pois": [
              {
                "address": "东城区",
                "city": "北京市",
                "city_code": "110101",
                "country": "中国",
                "country_code": "CN",
                "district": "东城区",
                "location": "116.401179,39.902768",
                "poi_id": "6601131771805304836",
                "poi_name": "北京市",
                "province": "北京市"
              }
            ]
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
            "pois",
            "cursor",
            "has_more",
        ];
    }
    
    protected function checkResponse(string $json, $decoded_json) : void
    {
        parent::checkResponse($json, $decoded_json);
        foreach($decoded_json['data']['pois'] AS $poi){
            foreach(['country_code', 'poi_id', 'district', 'location', 'poi_name', 'province', 'address', 'city', 'city_code' , 'country'] AS $k){
                if(!array_key_exists($k, $poi)){
                    throw new SystemException(sprintf('抖音的响应结果缺少关键字段pois[%s]', $json));
                }                
            }
        }
    }
}