<?php
use asbamboo\framework\kernel\Http;

header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Headers:X-Requested-With,Content-Type,__USER_TOKEN__,Authorization,ignorecanceltoken");
header("Access-Control-Allow-Methods:PUT,POST,GET,DELETE,OPTIONS");

$autoload   = require_once dirname(__DIR__) . '/vendor/autoload.php';
$autoload->addPsr4('', dirname(__DIR__));

$is_debug   = true;

(new Http())->run(new AppKernel($is_debug));
