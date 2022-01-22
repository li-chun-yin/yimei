<?php
use asbamboo\framework\config\RouterConfig;
use asbamboo\framework\config\EventListenerConfig;
use asbamboo\api\apiStore\ApiStore;
use asbamboo\api\apiStore\validator\TimestampChecker;
use asbamboo\api\apiStore\ApiRequestUri;
use asbamboo\api\apiStore\ApiRequestUris;
use asbamboo\framework\config\DbConfig;
use asbamboo\session\Session;
use asbamboo\session\handler\PdoHandler;
use asbamboo\logger\Logger;
use asbamboo\logger\handler\FileHandler;

return [
    RouterConfig::class         =>
        ['init_params' => ['configs' => include __DIR__ . DIRECTORY_SEPARATOR . 'router.php']],
    ApiStore::class             =>
        ['init_params'  => [
            'namespace' => 'api\\store\\handler',
            'dir'       => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'store' . DIRECTORY_SEPARATOR . 'handler'
        ]],
    TimestampChecker::class     =>
        ['class' => TimestampChecker::class],
    ApiRequestUri::class        =>
        ['init_params' => ['http://' . ($_SERVER['HTTP_HOST'] ?? 'xxx')  . '/api', '测试请求地址', 'test']],
    ApiRequestUris::class       =>
        ['init_params' => ['@'.ApiRequestUri::class]],

    /*************************************************************************************************************
     * 数据库配置
     *************************************************************************************************************/
    DbConfig::class     =>
        ['init_params'  => ['configs' => include __DIR__ . DIRECTORY_SEPARATOR . 'db.php']],
    /************************************************************************************************************/

    /*************************************************************************************************************
     * SESSION
     *************************************************************************************************************/
    Session::class              => [
        'init_params'           => [
            'sessionHandler'    => new PdoHandler(new PDO(
                'mysql:host=' . Vars::instance()->get('DB_HOST'). ';port='.Vars::instance()->get('DB_PORT').';dbname=' . Vars::instance()->get('DB_NAME'),
                Vars::instance()->get('DB_USER'),
                Vars::instance()->get('DB_PASSWORD')
             )),
            'option'            => ['cookie_domain'=>$_SERVER['SERVER_NAME'], 'name' => md5('asbamboo_user')],
            'is_start'          => false        // 在api hander 处理时手动调用 start方法
        ],
    ],
    /************************************************************************************************************/

    /*************************************************************************************************************
     * 权限配置
     *************************************************************************************************************/
//     UserProvider::class     =>
//         ['class'            => UserProvider::class],
//     Login::class            =>
//         ['init_params'      => ['UserProvider' => '@'.UserProvider::class]],
    /************************************************************************************************************/


    /*************************************************************************************************************
     * 日志配置
     *************************************************************************************************************/
    FileHandler::class  =>
    ['init_params'  => ['path' => dirname(__DIR__). DIRECTORY_SEPARATOR . 'vars' . DIRECTORY_SEPARATOR . 'request.log', Vars::instance()->get('HTTP_LOG_LEVEL')]],
    Logger::class     =>
    ['init_params'  => ['@'.FileHandler::class],],
    /************************************************************************************************************/

    /*************************************************************************************************************
     * 事件监听器配置
     *************************************************************************************************************/
    EventListenerConfig::class  =>
    ['init_params' => ['configs' => include __DIR__ . DIRECTORY_SEPARATOR . 'listener.php']],
    /************************************************************************************************************/
];
