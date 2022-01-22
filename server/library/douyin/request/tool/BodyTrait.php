<?php
namespace library\douyin\request\tool;

use asbamboo\http\StreamInterface;
use asbamboo\http\Stream;

/**
 * 处理生成请求http request body
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年10月11日
 */
trait BodyTrait
{
    private $assign_data = [];
    
    public function body()
    {
        return http_build_query($this->assign_data);
    }
}
