<?php
return  [
    ['id' => 'api', 'path' => '/api' , 'callback' => 'asbamboo\\api\\Controller:api', 'default_params' => ['version'=>'', 'api_name'=>'']],
    ['id' => 'api_doc', 'path' => '/api-doc' , 'callback' => 'asbamboo\\api\\Controller:doc', 'default_params' => ['document_name'=>'Sharer api']],
    ['id' => 'api_test', 'path' => '/api-test' , 'callback' => 'asbamboo\\api\\Controller:testTool'],
    ['id' => 'api_upload_file', 'path' => '/upload/file' , 'callback' => 'asbamboo\\api\\Controller:api', 'default_params' => ['version'=>'', 'api_name'=>'upload.file']],
    ['id' => 'douyin_code', 'path' => '/douyin/code' , 'callback' => 'controller\\Douyin:code'],
    ['id' => 'toutiao_code', 'path' => '/toutiao/code' , 'callback' => 'controller\\Toutiao:code'],
    ['id' => 'xigua_code', 'path' => '/xigua/code' , 'callback' => 'controller\\Xigua:code'],
    ['id' => 'upload_read', 'path' => '/upload/read' , 'callback' => 'controller\\Upload:read'],
];