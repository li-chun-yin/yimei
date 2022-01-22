<?php
namespace library\douyin\response;

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
        "data": {
            "access_token": "access_token", 接口调用凭证	
            "description": "", 错误码描述	
            "error_code": "0", 错误码	
            "expires_in": "86400", access_token接口调用凭证超时时间，单位（秒)	
            "open_id": "aaa-bbb-ccc", 授权用户唯一标识	
            "refresh_expires_in": "86400", refresh_token凭证超时时间，单位（秒)	
            "refresh_token": "refresh_token", 用户刷新access_token	
            "scope": "user_info" 用户授权的作用域(Scope)，使用逗号（,）分隔，开放平台几乎几乎每个接口都需要特定的Scope。	
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
            "open_id",
            "refresh_expires_in",
            "refresh_token",
            "scope",
        ];
    }
}