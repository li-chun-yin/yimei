<?php
namespace library\douyin\response;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月3日
 */
class XiguaOauthRenewRefreshTokenResponse extends ResponseAbstract
{
    /**
     *
{
  "data": {
    "description": "",
    "error_code": "0",
    "expires_in": "86400",
    "refresh_token": "refresh_token"
  },
  "message": "success"
}
     */
    public function getFieldNames() : array
    {
        return [
            "expires_in",
            "refresh_token",
        ];
    }
}