<?php
class Vars
{
    const DEFAULT_PARAMTERS                 = [
        /******************************************************************************
         * 系统变量
         *****************************************************************************/
         "SYSTEM_NAME"                      => "server",
        /*****************************************************************************
         * HTTP LOG LEVEL
         *****************************************************************************/
        "HTTP_LOG_LEVEL"                    => 'debug',
        /*****************************************************************************
         * 高德
         *****************************************************************************/
       "amap_key"                           => 'XXXXXXXXXXXXXXXXXXXX',
        /******************************************************************************
         * 数据库
         *****************************************************************************/
        "DB_DRIVER"                         => "pdo_mysql",
        "DB_HOST"                           => "127.0.0.1",
        "DB_PORT"                           => "3306",
        "DB_NAME"                           => "yimei",
        "DB_USER"                           => "root",
        "DB_PASSWORD"                       => "root",
        "DB_CHARSET"                        => "utf8mb4",
        /******************************************************************************
         * 系统发送邮件的email账号
         *****************************************************************************/
        "MAILER_HOST"                       => "smtp.aliyun.com",
        "MAILER_PORT"                       => "465",
        "MAILER_ENCRYPTION"                 => "ssl",
        "MAILER_USER"                       => "",
        "MAILER_PASSWORD"                   => "",
    ];

    /**
     *
     * @var array
     */
    private $parameters;

    private $is_reset = false;

    /**
     * 类的实例
     * @var object
     */
    private static $instance;

    private function __construct()
    {
        if(is_readable($this->getJsonPath())){
            $this->parameters   = json_decode(file_get_contents(), true);
        }
    }

    public static function instance() : self
    {
        if(! static::$instance){
            static::$instance    = new static();
        }
        return static::$instance;
    }

    public function get(string $key) /*: mixed*/
    {
        if(isset($this->parameters[$key])){
            return $this->parameters[$key];
        }
        if(isset(static::DEFAULT_PARAMTERS[$key])){
            return static::DEFAULT_PARAMTERS[$key];
        }
        return null;
    }

    public function set(string $key, /*mixed*/ $value) : bool
    {
        if($this->get($key) != $value){
            $this->parameters[$key] = $value;
            $this->is_reset   = true;
        }
        return true;
    }

    public function getJsonPath()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'vars.json';
    }

    public function __destruct()
    {
        if($this->is_reset == true){
            file_put_contents($this->getJsonPath(), json_encode($this->parameters));
        }
    }
}
