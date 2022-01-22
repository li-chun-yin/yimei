<?php
use asbamboo\framework\Event AS FrameworkEvent;
use asbamboo\http\Event AS HttpEvent;
use asbamboo\database\Event AS DatabaseEvent;
use listener\HttpLog;
use asbamboo\di\ContainerInterface;
use listener\DataLog;

/**
 * 事件监听器
 */
return [
    /************************************************************************************************************************************************
     * 监听数据变更，记录日志
     *************************************************************************************************************************************************/
    [
        'name'                  => DatabaseEvent::DATA_CHANGED,
        'class'                 => DataLog::class,
        'method'                => 'createChangeLog',
        'construct_params'      => ['@'.ContainerInterface::class],
    ],
    /************************************************************************************************************************************************/

    /************************************************************************************************************************************************
     * 监听kernel.http，记录日志
     *************************************************************************************************************************************************/
    [
        'name'                  => FrameworkEvent::KERNEL_HTTP_REQUEST,
        'class'                 => HttpLog::class,
        'method'                => 'createRequestLog',
        'construct_params'      => ['@'.ContainerInterface::class],
    ],
    [
        'name'                  => FrameworkEvent::KERNEL_HTTP_RESPONSE,
        'class'                 => HttpLog::class,
        'method'                => 'createResponseLog',
        'construct_params'      => ['@'.ContainerInterface::class],
    ],
    [
        'name'                  => FrameworkEvent::KERNEL_HTTP_EXCEPTION,
        'class'                 => HttpLog::class,
        'method'                => 'createExceptionLog',
        'construct_params'      => ['@'.ContainerInterface::class],
    ],
    [
        'name'                  => HttpEvent::HTTP_CLIENT_SEND_PRE_EXEC,
        'class'                 => HttpLog::class,
        'method'                => 'createApiRequestLog',
        'construct_params'      => ['@'.ContainerInterface::class],
    ],
    [
        'name'                  => HttpEvent::HTTP_CLIENT_SEND_AFTER_EXEC,
        'class'                 => HttpLog::class,
        'method'                => 'createApiResponseLog',
        'construct_params'      => ['@'.ContainerInterface::class],
    ],
    /************************************************************************************************************************************************/
];