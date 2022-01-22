<?php

use asbamboo\database\Factory;
use asbamboo\database\Connection;

$autoload   = require __DIR__ . '/vendor/autoload.php';
$autoload->addPsr4('', __DIR__);

/*************************************************************************************************************************************************
 * sqllite
*************************************************************************************************************************************************/
// replace with mechanism to retrieve EntityManager in your app
// $DbFactory          = new Factory();
// $sqpath             = __DIR__ . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'db.sqlite';
// $sqmetadata         = __DIR__ . DIRECTORY_SEPARATOR . 'dev' . DIRECTORY_SEPARATOR . 'database';
// $sqmetadata_type    = Connection::MATADATA_YAML;
// $sqdir              = dirname($sqpath);

// if(!is_file($sqpath)){
//     @mkdir($sqdir, 0700, true);
//     @file_put_contents($sqpath, '');
// }
// $DbFactory->addConnection(Connection::create([
//     'driver'    => 'pdo_sqlite',
//     'path'      => $sqpath
// ], $sqmetadata, $sqmetadata_type));
/************************************************************************************************************************************************/


/*************************************************************************************************************************************************
 * mysql
*************************************************************************************************************************************************/
include __DIR__ . DIRECTORY_SEPARATOR . 'Vars.php';

// $model_dirs = scandir(__DIR__ . DIRECTORY_SEPARATOR . 'model');
// foreach($model_dirs AS $model){
//     if(in_array($model, ['.', '..'])){
//         continue;
//     }
//     include __DIR__ . DIRECTORY_SEPARATOR . 'model' .  DIRECTORY_SEPARATOR  . "{$model}/Entity.php";
// }

$DbFactory          = new Factory();
$sqmetadata         = __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR  . 'database';
$sqmetadata_type    = Connection::MATADATA_YAML;
$DbFactory->addConnection(Connection::create([
    'driver'    => Vars::instance()->get('DB_DRIVER'),
    'host'      => Vars::instance()->get('DB_HOST'),
    'port'      => Vars::instance()->get('DB_PORT'),
    'dbname'    => Vars::instance()->get('DB_NAME'),
    'user'      => Vars::instance()->get('DB_USER'),
    'password'  => Vars::instance()->get('DB_PASSWORD'),
    'charset'   => Vars::instance()->get('DB_CHARSET'),
], $sqmetadata, $sqmetadata_type, true /*dev*/));


/************************************************************************************************************************************************/
$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($DbFactory->getManager()->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($DbFactory->getManager())
));
return $helperSet;