<?php
namespace listener;

use asbamboo\database\SqlLoggerInterface;
use asbamboo\di\ContainerAwareTrait;
use asbamboo\logger\LoggerInterface;

/**
 * 和其他listener不一样，这个因为继承doctrine的原因在config/db.php 里面配置
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2019年4月3日
 */
class SqlLogger implements SqlLoggerInterface
{
    use ContainerAwareTrait;

    /**
     * startquery 方法中标记开始执行Sql的时间
     * 在stopquery 中计算sql一共执行多少时间
     *
     * @var int
     */
    private $sql_mictotime;

    /**
     *
     * @var
     */
    private $tag;

    /**
     *
     * {@inheritDoc}
     * @see \Doctrine\DBAL\Logging\SQLLogger::startQuery()
     */
    public function startQuery($sql, ?array $params = null, ?array $types = null)
    {
        $Logger                 = $this->getLogger();
        if(!$Logger){
            return;
        }
        $sql_data               = 'sql:' . $sql . 'params:' . var_export($params, true) . 'types:' . var_export($types, true);

        $this->sql_mictotime    = microtime(true);
        $this->tag              = uniqid();

        $message                = "SQL_LOG[{$this->tag}]{$sql_data}";

        $Logger->debug($message);
    }

    /**
     *
     * {@inheritDoc}
     * @see \Doctrine\DBAL\Logging\SQLLogger::stopQuery()
     */
    public function stopQuery()
    {
        $Logger                 = $this->getLogger();
        if(!$Logger){
            return;
        }
        $execution_ms           = microtime(true) - $this->sql_mictotime;
        $message                = "SQL_LOG[{$this->tag}]execution_ms:{$execution_ms}";
        $Logger->debug($message);
    }

    /**
     *
     * @return LoggerInterface|NULL
     */
    public function getLogger() : ? LoggerInterface
    {
        return $this->Container->get(LoggerInterface::class);
    }
}