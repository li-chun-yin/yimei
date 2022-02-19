<?php
namespace library\kuaishou\response;

/**
 *
 * @author 李春寅<licy2013@aliyun.com>
 * @since 2022年2月19日
 */
class ApiUploadFragmentResponse extends ResponseAbstract
{
    /**
     *
{
    "result": 1,
    "checksum": "1e26571bfdd14604d6ece3243edbe729",
    "size": 2367345
}
     */
    public function getFieldNames() : array
    {
        return [
            'checksum',
            'size',
        ];
    }
}