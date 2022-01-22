<?php
namespace listener;

use asbamboo\http\ServerRequestInterface;
use asbamboo\di\ContainerInterface;
use asbamboo\logger\LoggerInterface;
use asbamboo\framework\kernel\Http;
use asbamboo\framework\kernel\KernelInterface;
use asbamboo\http\Client AS HttpClient;
use asbamboo\http\Request;
use asbamboo\http\RequestInterface;
use asbamboo\http\ResponseInterface;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年12月18日
 */
class HttpLog
{
    /**
     *
     * @var ContainerInterface
     */
    private $Container;

    /**
     *
     * @param ContainerInterface $Container
     */
    public function __construct(ContainerInterface $Container)
    {
        $this->Container          = $Container;
    }

    /**
     *
     * @return LoggerInterface
     */
    public function getLogger() : LoggerInterface
    {
        return $this->Container->get(LoggerInterface::class);
    }

    /**
     *
     * @return ServerRequestInterface
     */
    public function getRequest(): ServerRequestInterface
    {
        return $this->Container->get(ServerRequestInterface::class);
    }

    /**
     * 监听kernel.http.request 事件，将请求信息写入日志。
     *
     */
    public function createRequestLog() : void
    {
        $Logger = $this->getLogger();
        $Logger->debug("REQUEST_URI: ". (string) $this->getRequest()->getUri());
        $Logger->debug("REQUEST_PARAMS: ". var_export($this->getRequest()->getRequestParams(), true));
        $Logger->debug("REQUEST_BODY: ". var_export(file_get_contents('php://input'), True));
    }

    /**
     * 监听kernel.http.response 事件，将响应信息写入日志。
     *
     * @param Http $Http
     * @param KernelInterface $Kernel
     * @param ResponseInterface $Response
     */
    public function createResponseLog(Http $Http, KernelInterface $Kernel, ResponseInterface $Response) : void
    {
        $Logger = $this->getLogger();
        $Logger->debug("RESPONSE_BODY: ". (string) $Response->getBody());
    }

    /**
     * 监听kernel.http.exception 事件，将异常信息信息写入日志。
     *
     * @param Http $Http
     * @param \Throwable $e
     */
    public function createExceptionLog(Http $Http, \Throwable $e) : void
    {
        $Logger     = $this->getLogger();
        $Request    = $this->getRequest();
        $Logger->error("HTTP EXCEPTION:" . (string) $e, [
            'REQUEST_URI'       => (string) $Request->getUri(),
            'SERVER_PARAMS'     => var_export($Request->getServerParams(), true),
            'REQUEST_PARAMS'    => var_export($Request->getRequestParams(), true),
        ]);
    }

    /**
     *
     * @param HttpClient $HttpClient
     * @param resource $curl
     * @param Request $Request
     */
    public function createApiRequestLog(HttpClient $HttpClient, /*resource*/ $curl, Request $Request) : void
    {
        $Logger     = $this->getLogger();
        $Logger->info("HTTP CLIENT REQUEST DATA:", [
            'REQUEST_URI'   => (string) $Request->getUri(),
            'HEADERS'       => var_export($Request->getHeaders(), true),
            'METHOD'        => var_export($Request->getMethod(), true),
            'BODY'          => var_export($Request->getBody()->getContents(), true),
        ]);
    }

    /**
     *
     * @param HttpClient $HttpClient
     * @param resource $curl
     * @param RequestInterface $Request
     * @param ResponseInterface $Response
     */
    public function createApiResponseLog(HttpClient $HttpClient, /*resource*/ $curl, RequestInterface $Request, ResponseInterface $Response) : void
    {
        $Logger     = $this->getLogger();
        $Response->getBody()->rewind();
        $Logger->info("HTTP CLIENT RESPONSE DATA:", [
            'HEADERS'       => var_export($Response->getHeaders(), true),
            'BODY'          => var_export($Response->getBody()->getContents(), true),
        ]);
    }
}
