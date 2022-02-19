<?php
namespace library\kuaishou\response;

/**
 *
 * @author 李春寅<licy2013@aliyun.com>
 * @since 2022年2月19日
 */
class OpenapiPhotoStartUploadResponse extends ResponseAbstract
{
    /**
     *
{
  "result": 1,
  "upload_token": "3xwn3kkerxj6g9n",
  "endpoint" : "uploader.test.gifshow.com"
}
     */
    public function getFieldNames() : array
    {
        return [
            "upload_token",
            "endpoint",
        ];
    }
}