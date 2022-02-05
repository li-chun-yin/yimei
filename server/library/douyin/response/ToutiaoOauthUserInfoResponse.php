<?php
namespace library\douyin\response;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月3日
 */
class ToutiaoOauthUserInfoResponse extends OauthUserInfoResponse
{
    /**
     *
[
    {
        "data":{
            "avatar":"https://sf3-cdn-tos.toutiaostatic.com/img/user-avatar/851d6aea28f7154036351694e0deb7fe~300x300.image",
            "captcha":"",
            "client_key":"awhvdanwqiq634nm",
            "desc_url":"",
            "description":"",
            "error_code":0,
            "log_id":"202201302147290102121480471C75C873",
            "nickname":"99不二",
            "open_id":"564b4ecc-e553-48ee-8763-7d86cdfce827"
        },
        "message":"success"
    }
]     */
    public function getFieldNames() : array
    {
        return [
            "avatar",
            "nickname",
            "open_id",
        ];
    }
}