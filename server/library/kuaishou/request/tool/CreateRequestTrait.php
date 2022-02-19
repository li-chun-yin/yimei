<?php
namespace library\kuaishou\request\tool;

use library\kuaishou\request\ClientRequestInterface;

/**
 * 公共的创建Request对象的方法
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年10月12日
 */
trait CreateRequestTrait
{
    private $http_method = "POST";

    /**
     *
     * @return ClientRequestInterface
     */
    public function create() : ClientRequestInterface
    {
        return new class ($this->uri(), $this->body(), $this->http_method) implements ClientRequestInterface{

            private $uri, $body, $method;

            public function __construct($uri, $body, $method)
            {
                $this->uri          = (string)$uri;
                $this->body         = $body;
                $this->http_method  = $method;
            }

            public function getUri() : string
            {
                return $this->uri;
            }

            public function getBody()
            {
                return $this->body;
            }

            public function getHttpMethod() : string
            {
                return $this->http_method;
            }
        };
    }

    /**
     *
     * @param string $method
     * @return self
     */
    public function method(string $method) : self
    {
        $this->http_method = $method;

        return $this;
    }
}