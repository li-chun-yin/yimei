<?php
namespace library\kuaishou;

use asbamboo\http\ResponseInterface AS HttpResponseInterface;
use exception\SystemException;
use library\kuaishou\response\ResponseInterface;
use library\kuaishou\request\RequestInterface;
use asbamboo\http\Stream;
use asbamboo\http\Response;
use library\kuaishou\request\ClientRequestInterface;

/**
 *
 * @author 李春寅<licy2013@aliyun.com>
 * @since 2022年2月19日
 */
class ApiClient
{
    /**
     *
     * @param string $api_name
     * @param array $assign_data
     * @return ResponseInterface
     */
    public static function request(string $api_name, array $assign_data = []) : ResponseInterface
    {
        $Request        = static::createRequest($api_name);
        $Request        = $Request->assignData($assign_data);
        $ClientRequest  = $Request->create();
        $HttpResponse   = static::sendRequest($ClientRequest);
        $Response       = static::transformResponse($api_name, $HttpResponse);

        return $Response;
    }

    /**
     * 返回一个Http Request实例
     *
     * @param string $api_name
     * @throws SystemException
     * @return RequestInterface
     */
    public static function createRequest(string $api_name) : RequestInterface
    {
        $class_name         = __NAMESPACE__ . "\\request\\{$api_name}";
        if(!class_exists($class_name)){
            throw new SystemException(sprintf('找不到请求快手接口相关的类：%s', $api_name));
        }
        return new $class_name;
    }

    /**
     * 发送请求并且返回得到的响应值
     *
     * @param ClientRequestInterface $HttpRequest
     * @return HttpResponseInterface
     */
    private static function sendRequest(ClientRequestInterface $ClientRequest) : HttpResponseInterface
    {
        $Response               = new Response(new Stream('php://temp', 'w+b'));
        if($ClientRequest->getHttpMethod() == "GET"){
            $Response->getBody()->write(file_get_contents($ClientRequest->getUri()));
        }

        if($ClientRequest->getHttpMethod() == "POST"){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $ClientRequest->getUri());
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $ClientRequest->getBody());
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($ch, $data)use($ClientRequest, $Response){
//                 var_dump('client_request:', $ClientRequest->getUri(), $ClientRequest->getBody(), $data);
                $Response->getBody()->write($data);
            });
            curl_exec($ch);
            curl_close($ch);
        }

        $Response->getBody()->rewind();

        return $Response;
    }

    /**
     *
     * @param string $api_name
     * @param HttpResponseInterface $HttpResponse
     * @throws SystemException
     * @return ResponseInterface
     */
    private static function transformResponse(string $api_name, HttpResponseInterface $HttpResponse) : ResponseInterface
    {
        $response_class     = __NAMESPACE__ . "\\response\\{$api_name}Response";
        if(!class_exists($response_class)){
            throw new SystemException(sprintf('%s接口:找不到转换响应值的类。', $api_name));
        }
        return new $response_class($HttpResponse);
    }
}
