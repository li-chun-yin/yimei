<?php
namespace library\douyin\response;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月3日
 */
class OauthClientTokenResponse extends ResponseAbstract
{
    /**
     * 
        {
          "data": {
            "access_token": "access_token",
            "description": "",
            "error_code": "0",
            "expires_in": "7200"
          },
          "message": "<nil>"
        }
     */
    public function getFieldNames() : array
    {
        return [
            "access_token",
            "description",
            "error_code",
            "expires_in",
        ];
    }
}