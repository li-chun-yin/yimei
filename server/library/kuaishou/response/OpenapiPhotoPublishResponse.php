<?php
namespace library\kuaishou\response;

use exception\SystemException;

/**
 *
 * @author 李春寅<licy2013@aliyun.com>
 * @since 2022年2月19日
 */
class OpenapiPhotoPublishResponse extends ResponseAbstract
{
    /**
     *
{
  "result": 1,
  "video_info": {
    "photo_id": "3xwn3kkerxj6g9n",
    "caption": "测试标题",
    "cover": "https://xxx",
    "play_url": "https://xxx",
    "create_time": 1596629598574,
    "like_count": 1,
    "comment_count": 1,
    "view_count": 1,
    "pending": false
  }
}
     */
    public function getFieldNames() : array
    {
        return [
            "video_info",
        ];
    }

    protected function checkResponse(string $json, $decoded_json) : void
    {
        parent::checkResponse($json, $decoded_json);
        if(!isset($decoded_json['video_info']['photo_id'])){
            throw new SystemException(sprintf('快手的响应结果缺少photo_id[%s]', $json));
        }
    }
}