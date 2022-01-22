<?php
namespace library\douyin\response;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月3日
 */
class OauthUserInfoResponse extends ResponseAbstract
{
    /**
     * 
    {
      "data": {
        "avatar": "https://example.com/x.jpeg",
        "city": "上海",
        "country": "中国",
        "description": "",
        "e_account_role": "<nil>",
        "error_code": "0",
        "gender": "<nil>",
        "nickname": "张伟",
        "open_id": "0da22181-d833-447f-995f-1beefea5bef3",
        "province": "上海",
        "union_id": "1ad4e099-4a0c-47d1-a410-bffb4f2f64a4"
      }
    }
     */
    public function getFieldNames() : array
    {
        return [
            "avatar",
            "city",
            "country",
            "description",
            "e_account_role",
            "error_code",
            "gender",
            "nickname",
            "open_id",
            "province",
            "union_id",
        ];
    }
}