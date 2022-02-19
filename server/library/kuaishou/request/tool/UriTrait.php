<?php
namespace library\kuaishou\request\tool;

use asbamboo\http\UriInterface;

/**
 * 处理生成请求http request uri
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年10月11日
 */
trait UriTrait
{
    private $query_data   = [];

    /**
     *
     * @return string
     */
    public function getPath() : string
    {
        return $this->path ?? '/';
    }

    /**
     *
     * @return UriInterface
     */
    public function uri() : UriInterface
    {
        return $this->getGateway()->withPath($this->getPath())->withQuery(http_build_query($this->query_data));
    }
}
