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

    private $http_header = null;

    /**
     *
     * @return ClientRequestInterface
     */
    public function create() : ClientRequestInterface
    {
        return new class ($this->uri(), $this->body(), $this->http_method, $this->http_header) implements ClientRequestInterface{

            private $uri, $body, $method, $header;

            public function __construct($uri, $body, $method, $header)
            {
                $this->uri          = (string)$uri;
                $this->body         = $body;
                $this->http_method  = $method;
                $this->http_header  = $header;
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

            public function getHttpHeader() : ?array
            {
                return $this->http_header;
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

    /**
     *
     * @param array $header
     * @return self
     */
    public function httpHeader(array $header) : self
    {
        $this->http_header = $header;

        return $this;
    }
}