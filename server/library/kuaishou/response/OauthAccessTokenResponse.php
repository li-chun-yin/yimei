<?php
namespace library\kuaishou\response;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月3日
 */
class OauthAccessTokenResponse extends ResponseAbstract
{
    /**
     *
{
   "result": 1,
   "access_token": "xxxxxxx",
   "expires_in": 3600,
   "refresh_token": "xxxxxx",
   "refresh_token_expires_in":648000,
   "open_id": "xxxxx",
   "scopes": ["user_info"]

}
     */
    public function getFieldNames() : array
    {
        return [
            "access_token",
            "expires_in",
            "refresh_token_expires_in",
            "refresh_token",
            "open_id",
            "scopes",
        ];
    }
}