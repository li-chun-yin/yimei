<?php

Class ApiCode
{
    /**
     * 类的实例
     * @var object
     */
    private static $instance;
    
    private $codes;
    
    private function __construct()
    {
        $this->codes   = json_decode(file_get_contents($this->getApiCodeJson()), true);
    }
    
    public static function instance() : self
    {
        if(! static::$instance){
            static::$instance    = new static();
        }
        return static::$instance;
    }
    
    public function get(string $key) : int
    {
        return $this->codes[$key];
    }
    
    public function getApiCodeJson()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'api-code.json';
    }
}