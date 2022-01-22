<?php
return [
    'connection'    => [
        'driver'    => Vars::instance()->get('DB_DRIVER'),
        'host'      => Vars::instance()->get('DB_HOST'),
        'port'      => Vars::instance()->get('DB_PORT'),
        'dbname'    => Vars::instance()->get('DB_NAME'),
        'user'      => Vars::instance()->get('DB_USER'),
        'password'  => Vars::instance()->get('DB_PASSWORD'),
        'charset'   => Vars::instance()->get('DB_CHARSET'),
    ],'metadata'    => [
        'path'      => [__DIR__ . '/database'],
        'type'      => 'yaml',
    ],'logger'      => 'listener\\SqlLogger',
    'is_dev'      => true,
];